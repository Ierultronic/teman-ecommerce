<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Order Payment Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #dc2626;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .order-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .order-item {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            color: #dc2626;
            text-align: right;
            margin-top: 15px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #92400e;
        }
        .status-processing {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .admin-actions {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .admin-actions h3 {
            color: #dc2626;
            margin-top: 0;
        }
        .admin-actions a {
            display: inline-block;
            background-color: #dc2626;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }
        .admin-actions a:hover {
            background-color: #b91c1c;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Order Payment Received</h1>
        <p>Customer has completed payment for their order</p>
    </div>
    
    <div class="content">
        <p><strong>Admin Notification:</strong> A customer has successfully completed payment for their order.</p>
        
        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order Number:</strong> #{{ $order->id }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Status:</strong> 
                <span class="status status-processing">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
            </p>
            <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
            @if($order->payment_reference)
                <p><strong>Payment Reference:</strong> {{ $order->payment_reference }}</p>
            @endif
            @if($order->payment_verified_at)
                <p><strong>Payment Verified:</strong> {{ $order->payment_verified_at->format('F j, Y \a\t g:i A') }}</p>
            @endif
        </div>
        
        <div class="order-details">
            <h3>Customer Information</h3>
            <p><strong>Name:</strong> {{ $order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            @if($order->customer_phone)
                <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
            @endif
            <p><strong>Address:</strong><br>
                {{ $order->customer_address_line_1 }}<br>
                @if($order->customer_address_line_2)
                    {{ $order->customer_address_line_2 }}<br>
                @endif
                {{ $order->customer_city }}, {{ $order->customer_state }} {{ $order->customer_postal_code }}<br>
                {{ $order->customer_country }}
            </p>
        </div>
        
        <div class="order-details">
            <h3>Order Items</h3>
            @foreach($orderItems as $item)
                <div class="order-item">
                    <strong>{{ $item->product->name }}</strong>
                    @if($item->productVariant)
                        <br><small>Variant: {{ $item->productVariant->name }}</small>
                    @endif
                    <br>Quantity: {{ $item->quantity }} × RM{{ number_format($item->price, 2) }}
                    <br><strong>Subtotal: RM{{ number_format($item->quantity * $item->price, 2) }}</strong>
                </div>
            @endforeach
            
            @if($order->total_discount > 0)
                <div class="order-item">
                    <strong>Discount Applied:</strong> -RM{{ number_format($order->total_discount, 2) }}
                </div>
            @endif
            
            <div class="total">
                <strong>Total: RM{{ number_format($order->total_price, 2) }}</strong>
            </div>
        </div>
        
        @if($order->order_notes)
            <div class="order-details">
                <h3>Order Notes</h3>
                <p>{{ $order->order_notes }}</p>
            </div>
        @endif
        
        <div class="admin-actions">
            <h3>Admin Actions Required</h3>
            <p>Please process this order:</p>
            <a href="{{ config('app.url') }}/admin/orders/{{ $order->id }}">View Order Details</a>
            <a href="{{ config('app.url') }}/admin/orders">View All Orders</a>
        </div>
        
        <p><strong>Next Steps:</strong></p>
        <ul>
            <li>Verify payment details</li>
            <li>Prepare order for shipping</li>
            <li>Update order status as needed</li>
            <li>Contact customer if required</li>
        </ul>
        
        <div class="footer">
            <p>This is an automated notification for admin use only.</p>
        </div>
    </div>
</body>
</html>
