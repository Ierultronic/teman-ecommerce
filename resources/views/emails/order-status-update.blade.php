<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update</title>
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
            background-color: #2563eb;
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
            color: #2563eb;
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
        .status-shipped {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order Status Update</h1>
    </div>
    <div class="content">
        <p>Dear {{ $order->customer_name }},</p>
        <p>Your order <strong>#{{ $order->id }}</strong> status has been updated to:
            <span class="status status-{{ strtolower($newStatus) }}">{{ ucfirst($newStatus) }}</span>
        </p>
        @if(strtolower($newStatus) === 'shipped')
            <p>Your order has been shipped! You will receive your items soon. Thank you for shopping with us.</p>
        @elseif(strtolower($newStatus) === 'cancelled')
            <p>Your order has been cancelled. If you have any questions, please contact our support team.</p>
        @endif
        <div class="order-details">
            <h3>Order Information</h3>
            <p><strong>Order Number:</strong> #{{ $order->id }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
            <p><strong>Status:</strong> <span class="status status-{{ strtolower($newStatus) }}">{{ ucfirst($newStatus) }}</span></p>
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
            <div class="total">
                <strong>Total: RM{{ number_format($order->total_price, 2) }}</strong>
            </div>
        </div>
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
