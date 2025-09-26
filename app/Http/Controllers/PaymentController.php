<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function fpxCallback(Request $request)
    {
        // Handle FPX payment callback
        // This would typically verify the payment with your gateway provider
        
        Log::info('FPX Callback received', $request->all());
        
        // Example verification logic (replace with actual gateway verification)
        $orderId = $request->input('order_id');
        $status = $request->input('status');
        $reference = $request->input('reference');
        
        if ($orderId && $status === 'completed') {
            $order = Order::find($orderId);
            
            if ($order) {
                $order->update([
                    'status' => 'paid',
                    'payment_reference' => $reference,
                    'payment_verified_at' => now(),
                ]);
                
                Log::info('Order payment verified', ['order_id' => $orderId]);
            }
        }
        
        return response()->json(['status' => 'success']);
    }
    
    public function fpxSuccess(Request $request, Order $order)
    {
        // Handle successful FPX payment redirect
        return view('payment.success', compact('order'));
    }
}
