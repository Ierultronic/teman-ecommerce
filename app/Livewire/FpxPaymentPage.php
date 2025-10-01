<?php

namespace App\Livewire;

use App\Mail\AdminOrderNotificationMail;
use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class FpxPaymentPage extends Component
{
    public $order;
    public $showSuccess = false;
    public $showError = false;
    public $errorMessage = '';

    public function mount($orderId)
    {
        $this->order = Order::findOrFail($orderId);
        
        // Check if order is already paid
        if ($this->order->status === 'processing') {
            $this->showSuccess = true;
        }
    }

    public function initiatePayment()
    {
        try {
            // This would integrate with your chosen FPX gateway
            // For now, we'll simulate the payment process
            
            // Example integration with Billplz (you would need to install their SDK)
            /*
            $billplz = new Billplz();
            $bill = $billplz->createBill([
                'collection_id' => config('services.billplz.collection_id'),
                'email' => $this->order->customer_email,
                'name' => $this->order->customer_name,
                'amount' => $this->order->total_price * 100, // Convert to cents
                'description' => 'Order #' . $this->order->id,
                'callback_url' => route('payment.fpx.callback'),
                'redirect_url' => route('payment.fpx.success', ['order' => $this->order->id]),
            ]);
            
            return redirect($bill['url']);
            */
            
            // For demo purposes, simulate successful payment
            $this->simulatePayment();
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to initiate payment: ' . $e->getMessage();
            $this->showError = true;
        }
    }

    private function simulatePayment()
    {
        // Simulate payment success for demo
        $this->order->update([
            'status' => 'processing',
            'payment_reference' => 'FPX-' . time(),
            'payment_verified_at' => now(),
        ]);
        
        // Send order confirmation email to customer
        try {
            Mail::to($this->order->customer_email)->send(new OrderConfirmationMail($this->order));
        } catch (\Exception $e) {
            // Log email error but don't fail the payment
            Log::error('Failed to send order confirmation email', [
                'order_id' => $this->order->id,
                'customer_email' => $this->order->customer_email,
                'error' => $e->getMessage()
            ]);
        }
        
        // Send admin notification email
        try {
            $adminEmail = config('mail.from.address');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new AdminOrderNotificationMail($this->order));
            }
        } catch (\Exception $e) {
            // Log admin email error but don't fail the payment
            Log::error('Failed to send admin notification email', [
                'order_id' => $this->order->id,
                'admin_email' => config('mail.from.address'),
                'error' => $e->getMessage()
            ]);
        }
        
        $this->showSuccess = true;
    }

    public function render()
    {
        return view('livewire.fpx-payment-page');
    }
}
