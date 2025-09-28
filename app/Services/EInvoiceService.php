<?php

namespace App\Services;

use App\Models\Order;
use Carbon\Carbon;

class EInvoiceService
{
    /**
     * Generate LHDN compliant e-invoice data
     */
    public function generateEInvoiceData(Order $order): array
    {
        $invoiceData = [
            'invoice_number' => $this->generateInvoiceNumber($order),
            'invoice_date' => Carbon::now()->format('Y-m-d'),
            'invoice_time' => Carbon::now()->format('H:i:s'),
            'due_date' => Carbon::now()->addDays(30)->format('Y-m-d'),
            
            // Seller Information (Your Business)
            'seller' => [
                'name' => config('einvoice.seller.name', config('app.name', 'Your Business Name')),
                'registration_number' => config('einvoice.seller.registration_number', ''),
                'tax_identification_number' => config('einvoice.seller.tax_identification_number', ''),
                'address' => [
                    'line1' => config('einvoice.seller.address.line1', ''),
                    'line2' => config('einvoice.seller.address.line2', ''),
                    'city' => config('einvoice.seller.address.city', ''),
                    'state' => config('einvoice.seller.address.state', ''),
                    'postal_code' => config('einvoice.seller.address.postal_code', ''),
                    'country' => config('einvoice.seller.address.country', 'Malaysia'),
                ],
                'contact' => [
                    'phone' => config('einvoice.seller.contact.phone', ''),
                    'email' => config('einvoice.seller.contact.email', ''),
                ]
            ],
            
            // Buyer Information (Customer)
            'buyer' => [
                'name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
                'address' => [
                    'line1' => $order->customer_address_line_1,
                    'line2' => $order->customer_address_line_2,
                    'city' => $order->customer_city,
                    'state' => $order->customer_state,
                    'postal_code' => $order->customer_postal_code,
                    'country' => $order->customer_country ?? 'Malaysia',
                ]
            ],
            
            // Invoice Items
            'items' => [],
            'subtotal' => 0,
            'tax_amount' => 0,
            'total_amount' => $order->total_price,
            'currency' => 'MYR',
            
            // Payment Information
            'payment_method' => $order->payment_method,
            'payment_reference' => $order->payment_reference,
            'payment_date' => $order->payment_verified_at ? $order->payment_verified_at->format('Y-m-d') : null,
            
            // Additional Information
            'order_id' => $order->id,
            'order_notes' => $order->order_notes,
        ];
        
        // Process order items
        foreach ($order->orderItems as $item) {
            $itemData = [
                'product_name' => $item->product ? $item->product->name : 'Deleted Product',
                'variant_name' => $item->productVariant ? $item->productVariant->variant_name : null,
                'description' => $item->product ? $item->product->description : 'Product no longer available',
                'quantity' => $item->quantity,
                'unit_price' => $item->price,
                'line_total' => $item->price * $item->quantity,
                'tax_rate' => 0, // Assuming no tax for now, can be configured
                'tax_amount' => 0,
            ];
            
            $invoiceData['items'][] = $itemData;
            $invoiceData['subtotal'] += $itemData['line_total'];
        }
        
        return $invoiceData;
    }
    
    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber(Order $order): string
    {
        $prefix = config('einvoice.invoice_prefix', 'INV');
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        
        return sprintf('%s-%s%s-%06d', $prefix, $year, $month, $order->id);
    }
    
    /**
     * Generate XML format for LHDN submission
     */
    public function generateXML(Order $order): string
    {
        $data = $this->generateEInvoiceData($order);
        
        $xml = new \SimpleXMLElement('<Invoice></Invoice>');
        
        // Basic invoice information
        $xml->addChild('InvoiceNumber', htmlspecialchars($data['invoice_number']));
        $xml->addChild('InvoiceDate', $data['invoice_date']);
        $xml->addChild('InvoiceTime', $data['invoice_time']);
        $xml->addChild('DueDate', $data['due_date']);
        $xml->addChild('Currency', $data['currency']);
        
        // Seller information
        $seller = $xml->addChild('Seller');
        $seller->addChild('Name', htmlspecialchars($data['seller']['name']));
        $seller->addChild('RegistrationNumber', htmlspecialchars($data['seller']['registration_number']));
        $seller->addChild('TaxIdentificationNumber', htmlspecialchars($data['seller']['tax_identification_number']));
        
        $sellerAddress = $seller->addChild('Address');
        $sellerAddress->addChild('Line1', htmlspecialchars($data['seller']['address']['line1']));
        $sellerAddress->addChild('Line2', htmlspecialchars($data['seller']['address']['line2']));
        $sellerAddress->addChild('City', htmlspecialchars($data['seller']['address']['city']));
        $sellerAddress->addChild('State', htmlspecialchars($data['seller']['address']['state']));
        $sellerAddress->addChild('PostalCode', htmlspecialchars($data['seller']['address']['postal_code']));
        $sellerAddress->addChild('Country', htmlspecialchars($data['seller']['address']['country']));
        
        $sellerContact = $seller->addChild('Contact');
        $sellerContact->addChild('Phone', htmlspecialchars($data['seller']['contact']['phone']));
        $sellerContact->addChild('Email', htmlspecialchars($data['seller']['contact']['email']));
        
        // Buyer information
        $buyer = $xml->addChild('Buyer');
        $buyer->addChild('Name', htmlspecialchars($data['buyer']['name']));
        $buyer->addChild('Email', htmlspecialchars($data['buyer']['email']));
        $buyer->addChild('Phone', htmlspecialchars($data['buyer']['phone']));
        
        $buyerAddress = $buyer->addChild('Address');
        $buyerAddress->addChild('Line1', htmlspecialchars($data['buyer']['address']['line1']));
        $buyerAddress->addChild('Line2', htmlspecialchars($data['buyer']['address']['line2']));
        $buyerAddress->addChild('City', htmlspecialchars($data['buyer']['address']['city']));
        $buyerAddress->addChild('State', htmlspecialchars($data['buyer']['address']['state']));
        $buyerAddress->addChild('PostalCode', htmlspecialchars($data['buyer']['address']['postal_code']));
        $buyerAddress->addChild('Country', htmlspecialchars($data['buyer']['address']['country']));
        
        // Invoice items
        $items = $xml->addChild('Items');
        foreach ($data['items'] as $item) {
            $itemNode = $items->addChild('Item');
            $itemNode->addChild('ProductName', htmlspecialchars($item['product_name']));
            if ($item['variant_name']) {
                $itemNode->addChild('VariantName', htmlspecialchars($item['variant_name']));
            }
            $itemNode->addChild('Description', htmlspecialchars($item['description']));
            $itemNode->addChild('Quantity', $item['quantity']);
            $itemNode->addChild('UnitPrice', number_format($item['unit_price'], 2, '.', ''));
            $itemNode->addChild('LineTotal', number_format($item['line_total'], 2, '.', ''));
            $itemNode->addChild('TaxRate', $item['tax_rate']);
            $itemNode->addChild('TaxAmount', number_format($item['tax_amount'], 2, '.', ''));
        }
        
        // Summary
        $summary = $xml->addChild('Summary');
        $summary->addChild('Subtotal', number_format($data['subtotal'], 2, '.', ''));
        $summary->addChild('TaxAmount', number_format($data['tax_amount'], 2, '.', ''));
        $summary->addChild('TotalAmount', number_format($data['total_amount'], 2, '.', ''));
        
        // Payment information
        if ($data['payment_method']) {
            $payment = $xml->addChild('Payment');
            $payment->addChild('Method', htmlspecialchars($data['payment_method']));
            if ($data['payment_reference']) {
                $payment->addChild('Reference', htmlspecialchars($data['payment_reference']));
            }
            if ($data['payment_date']) {
                $payment->addChild('Date', $data['payment_date']);
            }
        }
        
        return $xml->asXML();
    }
    
    /**
     * Generate JSON format for LHDN submission
     */
    public function generateJSON(Order $order): string
    {
        $data = $this->generateEInvoiceData($order);
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
