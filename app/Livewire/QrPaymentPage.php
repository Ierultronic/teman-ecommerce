<?php

namespace App\Livewire;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\WebsiteSettings;
use App\Services\ReceiptProcessingService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class QrPaymentPage extends Component
{
    use WithFileUploads;

    public $order;
    public $receipt;
    public $paymentReference = '';
    public $showSuccess = false;
    public $extractedReference = '';
    public $isExtracting = false;

    public function mount($orderId)
    {
        $this->order = Order::findOrFail($orderId);
        
        // Check if order is already paid
        if ($this->order->status === 'processing') {
            $this->showSuccess = true;
        }
    }

    public function uploadReceipt()
    {
        $this->validate([
            'receipt' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB max
            'paymentReference' => 'required|string|max:255',
        ]);

        try {
            $receiptService = new ReceiptProcessingService();
            
            // Validate the receipt
            $validation = $receiptService->validateReceipt($this->receipt);
            if (!$validation['valid']) {
                $this->addError('receipt', implode(', ', $validation['errors']));
                return;
            }
            
            // Process the receipt
            $result = $receiptService->processReceipt($this->receipt, $this->order->id);
            
            if (!$result['success']) {
                $this->addError('receipt', 'Failed to process receipt: ' . $result['error']);
                return;
            }
            
            // Use extracted reference ID if available, otherwise use manual input
            $finalReference = $result['reference_id'] ?? $this->paymentReference;
            
            // Update order with receipt and reference
            $this->order->update([
                'payment_receipt_path' => $result['file_path'],
                'payment_reference' => $finalReference,
                'status' => 'pending_verification'
            ]);

            $this->showSuccess = true;
            
            // Send order confirmation email
            try {
                Mail::to($this->order->customer_email)->send(new OrderConfirmationMail($this->order));
            } catch (\Exception $e) {
                // Log email error but don't fail the receipt upload
                Log::error('Failed to send order confirmation email', [
                    'order_id' => $this->order->id,
                    'customer_email' => $this->order->customer_email,
                    'error' => $e->getMessage()
                ]);
            }
            
            $message = 'Receipt uploaded successfully!';
            if ($result['reference_id']) {
                $message .= ' Reference ID automatically extracted: ' . $result['reference_id'];
            }
            $message .= ' We will verify your payment shortly. A confirmation email has been sent to ' . $this->order->customer_email . '.';
            
            session()->flash('message', $message);
            
        } catch (\Exception $e) {
            $this->addError('receipt', 'Failed to upload receipt: ' . $e->getMessage());
        }
    }

    public function extractReference()
    {
        if (!$this->receipt) {
            $this->addError('receipt', 'Please select a receipt first.');
            return;
        }

        $this->isExtracting = true;
        
        try {
            $receiptService = new ReceiptProcessingService();
            
            // Validate the receipt
            $validation = $receiptService->validateReceipt($this->receipt);
            if (!$validation['valid']) {
                $this->addError('receipt', implode(', ', $validation['errors']));
                $this->isExtracting = false;
                return;
            }
            
            // Process the receipt to extract reference
            $result = $receiptService->processReceipt($this->receipt, $this->order->id);
            
            if ($result['success'] && $result['reference_id']) {
                $this->extractedReference = $result['reference_id'];
                $this->paymentReference = $result['reference_id'];
                session()->flash('extraction-success', 'Reference ID extracted successfully: ' . $result['reference_id']);
            } else {
                $this->addError('extraction', 'Could not extract reference ID from the image. Please enter it manually.');
            }
            
        } catch (\Exception $e) {
            $this->addError('extraction', 'Failed to extract reference ID: ' . $e->getMessage());
        }
        
        $this->isExtracting = false;
    }

    public function generateQrData()
    {
        $settings = WebsiteSettings::current();
        
        // Generate QR data using dynamic settings from database
        $qrData = [
            'bank' => $settings->qr_bank_name ?? 'Maybank',
            'account' => $settings->qr_account_number ?? '1234567890',
            'name' => $settings->qr_account_holder_name ?? 'Teman Ecommerce',
            'amount' => $this->order->total_price,
            'reference' => 'ORDER-' . $this->order->id,
        ];

        return $qrData;
    }

    public function render()
    {
        $settings = WebsiteSettings::current();
        $qrData = $this->generateQrData();
        
        return view('livewire.qr-payment-page', [
            'qrData' => $qrData,
            'qrImageUrl' => $settings->qr_image_url
        ]);
    }
}
