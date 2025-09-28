<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Invoice - {{ $invoiceData['invoice_number'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            line-height: 1.2;
            color: #000;
            margin: 0;
            padding: 10px;
            background-color: #ffffff;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }
        
        .company-info {
            flex: 1;
        }
        
        .company-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
            color: #000;
        }
        
        .company-address {
            font-size: 8px;
            line-height: 1.1;
            margin-bottom: 2px;
        }
        
        .company-contact {
            font-size: 8px;
            margin-bottom: 1px;
        }
        
        .einvoice-header {
            text-align: right;
            flex: 1;
        }
        
        .einvoice-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 6px;
            text-align: right;
        }
        
        .einvoice-details {
            font-size: 8px;
            text-align: right;
        }
        
        .einvoice-details div {
            margin-bottom: 1px;
        }
        
        .einvoice-label {
            font-weight: bold;
            display: inline-block;
            width: 140px;
            text-align: left;
        }
        
        .supplier-buyer-section {
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
        }
        
        .supplier-info, .buyer-info {
            flex: 1;
            border: 1px solid #000;
            padding: 6px;
        }
        
        .section-title {
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 4px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
        }
        
        .info-row {
            margin-bottom: 2px;
            font-size: 8px;
        }
        
        .info-label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            border: 1px solid #000;
        }
        
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 4px 3px;
            text-align: left;
            font-size: 8px;
        }
        
        .items-table th {
            background-color: #000;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 12px;
        }
        
        .summary-table {
            width: 280px;
            border-collapse: collapse;
        }
        
        .summary-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 8px;
        }
        
        .summary-table .label {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #e0e0e0;
        }
        
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 12px;
            font-size: 7px;
        }
        
        .digital-signature {
            flex: 1;
        }
        
        .signature-label {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .signature-value {
            font-family: monospace;
            font-size: 6px;
            word-break: break-all;
            line-height: 1.0;
        }
        
        .validation-info {
            text-align: right;
            flex: 1;
        }
        
        .qr-code {
            width: 60px;
            height: 60px;
            border: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6px;
            text-align: center;
        }
        
        .page-container {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <div class="company-name">{{ $invoiceData['seller']['name'] }}</div>
                <div class="company-address">
                    @if($invoiceData['seller']['address']['line1'])
                        {{ $invoiceData['seller']['address']['line1'] }}
                    @endif
                    @if($invoiceData['seller']['address']['line2'])
                        , {{ $invoiceData['seller']['address']['line2'] }}
                    @endif
                    @if($invoiceData['seller']['address']['city'] || $invoiceData['seller']['address']['state'])
                        , {{ $invoiceData['seller']['address']['city'] }}{{ $invoiceData['seller']['address']['city'] && $invoiceData['seller']['address']['state'] ? ', ' : '' }}{{ $invoiceData['seller']['address']['state'] }}
                    @endif
                    @if($invoiceData['seller']['address']['postal_code'] || $invoiceData['seller']['address']['country'])
                        , {{ $invoiceData['seller']['address']['postal_code'] }}{{ $invoiceData['seller']['address']['postal_code'] && $invoiceData['seller']['address']['country'] ? ', ' : '' }}{{ $invoiceData['seller']['address']['country'] }}
                    @endif
                </div>
                @if($invoiceData['seller']['contact']['phone'])
                <div class="company-contact">Contact Number: {{ $invoiceData['seller']['contact']['phone'] }}</div>
                @endif
                @if($invoiceData['seller']['contact']['email'])
                <div class="company-contact">Email: {{ $invoiceData['seller']['contact']['email'] }}</div>
                @endif
            </div>
            
            <div class="einvoice-header">
                <div class="einvoice-title">E-INVOICE</div>
                <div class="einvoice-details">
                    <div><span class="einvoice-label">e-Invoice Type:</span> 01 - Invoice</div>
                    <div><span class="einvoice-label">e-Invoice version:</span> 1.0</div>
                    <div><span class="einvoice-label">e-Invoice code:</span> {{ $invoiceData['invoice_number'] }}</div>
                    <div><span class="einvoice-label">Unique Identifier No:</span> {{ $invoiceData['order_id'] }}-{{ date('Y') }}-{{ str_pad($invoiceData['order_id'], 7, '0', STR_PAD_LEFT) }}</div>
                    <div><span class="einvoice-label">Original Invoice Ref. No.:</span> Not Applicable</div>
                    <div><span class="einvoice-label">Invoice Date and Time:</span> {{ \Carbon\Carbon::parse($invoiceData['invoice_date'])->format('Y-m-d H:i:s') }}</div>
                </div>
            </div>
        </div>

        <!-- Supplier and Buyer Information -->
        <div class="supplier-buyer-section">
            <div class="supplier-info">
                <div class="section-title">Supplier TIN: {{ $invoiceData['seller']['tax_identification_number'] ?: 'Not Provided' }}</div>
                @if($invoiceData['seller']['registration_number'])
                <div class="info-row">
                    <span class="info-label">Supplier Registration Number:</span> {{ $invoiceData['seller']['registration_number'] }}
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Supplier SST ID:</span> {{ config('einvoice.seller.sst_id', 'Not Provided') }}
                </div>
                <div class="info-row">
                    <span class="info-label">Supplier MSIC code:</span> {{ config('einvoice.seller.msic_code', 'Not Provided') }}
                </div>
                <div class="info-row">
                    <span class="info-label">Supplier business activity description:</span> {{ config('einvoice.seller.business_activity', 'Retail sale of products') }}
                </div>
            </div>
            
            <div class="buyer-info">
                <div class="section-title">Buyer TIN: {{ config('einvoice.buyer.default_tin', 'Not Provided') }}</div>
                <div class="info-row">
                    <span class="info-label">Buyer Name:</span> {{ $invoiceData['buyer']['name'] }}
                </div>
                <div class="info-row">
                    <span class="info-label">Buyer Identification Number:</span> {{ config('einvoice.buyer.default_id_number', 'Not Provided') }}
                </div>
                <div class="info-row">
                    <span class="info-label">Buyer Address:</span> 
                    @if($invoiceData['buyer']['address']['line1'])
                        {{ $invoiceData['buyer']['address']['line1'] }}
                    @endif
                    @if($invoiceData['buyer']['address']['line2'])
                        , {{ $invoiceData['buyer']['address']['line2'] }}
                    @endif
                    @if($invoiceData['buyer']['address']['city'] || $invoiceData['buyer']['address']['state'])
                        , {{ $invoiceData['buyer']['address']['city'] }}{{ $invoiceData['buyer']['address']['city'] && $invoiceData['buyer']['address']['state'] ? ', ' : '' }}{{ $invoiceData['buyer']['address']['state'] }}
                    @endif
                    @if($invoiceData['buyer']['address']['postal_code'] || $invoiceData['buyer']['address']['country'])
                        , {{ $invoiceData['buyer']['address']['postal_code'] }}{{ $invoiceData['buyer']['address']['postal_code'] && $invoiceData['buyer']['address']['country'] ? ', ' : '' }}{{ $invoiceData['buyer']['address']['country'] }}
                    @endif
                </div>
                @if($invoiceData['buyer']['phone'])
                <div class="info-row">
                    <span class="info-label">Buyer Contact Number (Mobile):</span> {{ $invoiceData['buyer']['phone'] }}
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Buyer Email:</span> {{ $invoiceData['buyer']['email'] }}
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Classification</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                    <th>Disc</th>
                    <th>Tax Rate</th>
                    <th>Tax Amount</th>
                    <th>Total Product / Service Price (incl. tax)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoiceData['items'] as $index => $item)
                <tr>
                    <td class="text-center">003</td>
                    <td>
                        {{ $item['product_name'] }}
                        @if($item['variant_name'])
                            - {{ $item['variant_name'] }}
                        @endif
                    </td>
                    <td class="text-center">{{ $item['quantity'] }}</td>
                    <td class="text-right">RM{{ number_format($item['unit_price'], 2) }}</td>
                    <td class="text-right">RM{{ number_format($item['line_total'], 2) }}</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-center">-</td>
                    <td class="text-right">RM{{ number_format($item['line_total'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td class="label">Subtotal:</td>
                    <td class="text-right">RM{{ number_format($invoiceData['subtotal'], 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Total excluding tax:</td>
                    <td class="text-right">RM{{ number_format($invoiceData['subtotal'], 2) }}</td>
                </tr>
                <tr>
                    <td class="label">Tax amount:</td>
                    <td class="text-right">RM{{ number_format($invoiceData['tax_amount'], 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label">Total including tax:</td>
                    <td class="text-right">RM{{ number_format($invoiceData['total_amount'], 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label">Total payable amount:</td>
                    <td class="text-right">RM{{ number_format($invoiceData['total_amount'], 2) }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="digital-signature">
                <div class="signature-label">Digital Signature:</div>
                <div class="signature-value">9e83e05bbf9b5db17ac0deec3b7ce6cba983f6dc50531c7a919f28d5fb369etc3</div>
                <div style="margin-top: 6px;">
                    <div class="signature-label">Date and Time of Validation:</div>
                    <div>{{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}</div>
                </div>
                <div style="margin-top: 6px;">
                    <div class="signature-label">Document Description:</div>
                    <div>This document is a visual presentation of the e-Invoice</div>
                </div>
            </div>
            
            <div class="validation-info">
                <div class="qr-code">
                    QR CODE<br>
                    PLACEHOLDER
                </div>
            </div>
        </div>
    </div>
</body>
</html>
