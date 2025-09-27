<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\ReceiptProcessingService;
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
            
            $message = 'Receipt uploaded successfully!';
            if ($result['reference_id']) {
                $message .= ' Reference ID automatically extracted: ' . $result['reference_id'];
            }
            $message .= ' We will verify your payment shortly.';
            
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
        // Generate QR data for Malaysian bank account
        // This would typically include bank account details and amount
        $qrData = [
            'bank' => 'Maybank',
            'account' => '1234567890',
            'name' => 'Teman Ecommerce',
            'amount' => $this->order->total_price,
            'reference' => 'ORDER-' . $this->order->id,
        ];

        return $qrData;
    }

    public function render()
    {
        $qrData = $this->generateQrData();
        
        return view('livewire.qr-payment-page', [
            'qrData' => $qrData
        ]);
    }
}
