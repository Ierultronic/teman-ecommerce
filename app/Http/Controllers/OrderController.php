<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.product', 'orderItems.productVariant'])
            ->latest()
            ->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:150',
            'customer_email' => 'required|email|max:150',
            'customer_phone' => 'nullable|string|max:30',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variant_id' => 'nullable|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $totalPrice = 0;
            $orderItems = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $variant = null;
                
                if (isset($item['variant_id'])) {
                    // Use atomic stock check with row locking
                    $variant = $product->variants()
                        ->where('id', $item['variant_id'])
                        ->where('stock', '>=', $item['quantity'])
                        ->lockForUpdate()
                        ->first();
                    
                    if (!$variant) {
                        throw new \Exception("Insufficient stock for variant. Item may have been sold out.");
                    }
                }

                $price = $product->price;
                $totalPrice += $price * $item['quantity'];

                $orderItems[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'quantity' => $item['quantity'],
                    'price' => $price,
                ];
            }

            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            foreach ($orderItems as $item) {
                $order->orderItems()->create($item);
                
                // Update stock atomically - this will fail if stock goes negative
                if (isset($item['product_variant_id'])) {
                    $affectedRows = ProductVariant::where('id', $item['product_variant_id'])
                        ->where('stock', '>=', $item['quantity'])
                        ->decrement('stock', $item['quantity']);
                    
                    if ($affectedRows === 0) {
                        throw new \Exception("Insufficient stock for variant. Item may have been sold out.");
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show(Order $order)
    {
        $order->load(['orderItems.product', 'orderItems.productVariant']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
