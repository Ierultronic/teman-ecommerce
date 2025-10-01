<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\EInvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\OrderStatusUpdateMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['orderItems.product', 'orderItems.productVariant']);
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_email', 'like', '%' . $search . '%');
            });
        }
        
        $orders = $query->latest()->paginate(15);
        
        return View::make('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.product', 'orderItems.productVariant', 'orderDiscounts.discountable']);
        return View::make('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,pending_verification,paid,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        // Send email to customer if status is shipped or cancelled
        if (in_array($request->status, ['shipped', 'cancelled'])) {
            try {
                Mail::to($order->customer_email)->send(new OrderStatusUpdateMail($order, $request->status));
            } catch (\Exception $e) {
                Log::error('Failed to send order status update email', [
                    'order_id' => $order->id,
                    'customer_email' => $order->customer_email,
                    'new_status' => $request->status,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return Redirect::back()->with('success', 'Order status updated successfully!');
    }

    public function verifyPayment(Request $request, Order $order)
    {
        $request->validate([
            'verified' => 'required|boolean'
        ]);

        if ($request->verified) {
            $order->update([
                'status' => 'processing',
                'payment_verified_at' => now(),
                'payment_verified_by' => Auth::id(),
            ]);
            
            $message = 'Payment verified successfully! Order is now being processed.';
        } else {
            $order->update([
                'status' => 'cancelled',
                'payment_verified_at' => now(),
                'payment_verified_by' => Auth::id(),
            ]);
            
            // Send cancellation email to customer
            try {
                Mail::to($order->customer_email)->send(new OrderStatusUpdateMail($order, 'cancelled'));
            } catch (\Exception $e) {
                Log::error('Failed to send order status update email (cancelled)', [
                    'order_id' => $order->id,
                    'customer_email' => $order->customer_email,
                    'new_status' => 'cancelled',
                    'error' => $e->getMessage()
                ]);
            }
            $message = 'Payment rejected. Order cancelled.';
        }

        return Redirect::back()->with('success', $message);
    }

    public function generateEInvoice(Order $order)
    {
        $order->load(['orderItems.product', 'orderItems.productVariant']);
        
        // Check if order is eligible for e-invoice generation
        if (!in_array($order->status, ['paid', 'processing', 'shipped', 'delivered'])) {
            return Redirect::back()->with('error', 'E-invoice can only be generated for paid orders.');
        }
        
        $eInvoiceService = new EInvoiceService();
        $invoiceData = $eInvoiceService->generateEInvoiceData($order);
        
        // Generate PDF in landscape mode
        $pdf = Pdf::loadView('admin.orders.e-invoice', compact('order', 'invoiceData'))
            ->setPaper('A4', 'landscape');
        
        $filename = 'e-invoice-' . $invoiceData['invoice_number'] . '.pdf';
        
        return $pdf->download($filename);
    }
}
