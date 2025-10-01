<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
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
            background-color: #f97316;
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
            color: #f97316;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Confirmation</h1>
        <p>Thank you for your payment!</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $order->customer_name }},</p>
        
        <p>We have received your payment and your order is now being processed. Here are the details:</p>
        
        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order Number:</strong> #{{ $order->id }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Status:</strong> 
                <span class="status status-pending">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
            </p>
            <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
            @if($order->payment_reference)
                <p><strong>Payment Reference:</strong> {{ $order->payment_reference }}</p>
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
        
        <p>Your order is now being processed and will be shipped soon. We will update you on the shipping status. If you have any questions, please don't hesitate to contact us.</p>
        
        <p>Thank you for your business!</p>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
