@extends('admin.layouts.app')

@section('title', 'Order #' . $order->id)

@section('page-title', 'Order Details')

@section('content')
@if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
        <i data-feather="check-circle" class="w-5 h-5 mr-2 text-green-600"></i>
        {{ session('success') }}
    </div>
@endif

<!-- Order Header -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Order #{{ $order->id }}</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Order details and customer information</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'pending_verification') bg-orange-100 text-orange-800
                    @elseif($order->status === 'paid') bg-green-100 text-green-800
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
                @if(in_array($order->status, ['paid', 'processing', 'shipped', 'delivered']))
                    <a href="{{ route('admin.orders.e-invoice', $order) }}" 
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <i data-feather="file-text" class="w-4 h-4 mr-2"></i>
                        Generate E-Invoice
                    </a>
                @endif
            </div>
        </div>
    </div>
    
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Customer Email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_email }}</dd>
            </div>
            @if($order->customer_phone)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Customer Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_phone }}</dd>
                </div>
            @endif
            <div>
                <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('d/m/Y H:ia') }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
                <dd class="mt-1 text-lg font-semibold text-green-600">RM{{ number_format($order->total_price, 2) }}</dd>
            </div>
            @if($order->payment_method)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ strtoupper($order->payment_method) }}</dd>
                </div>
            @endif
            @if($order->payment_reference)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Reference</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->payment_reference }}</dd>
                </div>
            @endif
            @if($order->payment_verified_at)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Payment Verified</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->payment_verified_at->format('M d, Y H:i') }}</dd>
                </div>
            @endif
        </dl>
    </div>
</div>

<!-- Customer Address Information -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Customer Address</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Billing address information</p>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            @if($order->customer_address_line_1)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Address Line 1</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_address_line_1 }}</dd>
                </div>
            @endif
            @if($order->customer_address_line_2)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Address Line 2</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_address_line_2 }}</dd>
                </div>
            @endif
            @if($order->customer_city)
                <div>
                    <dt class="text-sm font-medium text-gray-500">City</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_city }}</dd>
                </div>
            @endif
            @if($order->customer_state)
                <div>
                    <dt class="text-sm font-medium text-gray-500">State</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_state }}</dd>
                </div>
            @endif
            @if($order->customer_postal_code)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Postal Code</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_postal_code }}</dd>
                </div>
            @endif
            @if($order->customer_country)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_country }}</dd>
                </div>
            @endif
        </dl>
    </div>
</div>

<!-- Shipping Address Information -->
@if($order->shipping_name || $order->shipping_address_line_1)
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Shipping Address</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Delivery address information</p>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            @if($order->shipping_name)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Shipping Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_name }}</dd>
                </div>
            @endif
            @if($order->shipping_email)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Shipping Email</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_email }}</dd>
                </div>
            @endif
            @if($order->shipping_phone)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Shipping Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_phone }}</dd>
                </div>
            @endif
            @if($order->shipping_address_line_1)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Address Line 1</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_address_line_1 }}</dd>
                </div>
            @endif
            @if($order->shipping_address_line_2)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Address Line 2</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_address_line_2 }}</dd>
                </div>
            @endif
            @if($order->shipping_city)
                <div>
                    <dt class="text-sm font-medium text-gray-500">City</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_city }}</dd>
                </div>
            @endif
            @if($order->shipping_state)
                <div>
                    <dt class="text-sm font-medium text-gray-500">State</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_state }}</dd>
                </div>
            @endif
            @if($order->shipping_postal_code)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Postal Code</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_postal_code }}</dd>
                </div>
            @endif
            @if($order->shipping_country)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Country</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $order->shipping_country }}</dd>
                </div>
            @endif
        </dl>
    </div>
</div>
@endif

<!-- Order Notes -->
@if($order->order_notes)
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Order Notes</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Additional information or special instructions</p>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $order->order_notes }}</div>
    </div>
</div>
@endif

<!-- Order Items -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Order Items</h3>
    </div>
    <div class="border-t border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Variant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name ?? 'Deleted Product' }}" class="w-10 h-10 object-cover rounded-md mr-3">
                                    @else
                                        <div class="w-10 h-10 bg-gray-200 rounded-md mr-3 flex items-center justify-center">
                                            <span class="text-gray-400 text-xs">No Image</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item->product ? $item->product->name : 'Deleted Product' }}
                                            @if(!$item->product)
                                                <span class="text-xs text-red-500">(Product Deleted)</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($item->productVariant)
                                    {{ $item->productVariant->variant_name }}
                                    @if($item->productVariant->trashed())
                                        <span class="text-xs text-red-500">(Variant Deleted)</span>
                                    @endif
                                @else
                                    <span class="text-gray-500">No Variant</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->quantity }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                RM{{ number_format($item->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                RM{{ number_format($item->price * $item->quantity, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Payment Verification (for QR payments) -->
@if($order->status === 'pending_verification' && $order->payment_method === 'qr')
<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Payment Verification</h3>
        <p class="mt-1 text-sm text-gray-500">Verify the payment receipt and reference number</p>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        @if($order->payment_receipt_path)
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Payment Receipt</h4>
                <div class="border border-gray-200 rounded-lg p-4">
                    <img src="{{ Storage::url($order->payment_receipt_path) }}" 
                         alt="Payment Receipt" 
                         class="max-w-full h-auto max-h-96 mx-auto">
                </div>
            </div>
        @endif
        
        @if($order->payment_reference)
            <div class="mb-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Payment Reference</h4>
                <p class="text-sm text-gray-900 font-mono bg-gray-50 p-2 rounded">{{ $order->payment_reference }}</p>
            </div>
        @endif
        
        <div class="flex items-center space-x-4">
            <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="verified" value="1">
                <button type="submit" 
                        class="text-white px-4 py-2 rounded-md bg-green-600 hover:bg-green-700 transition-colors"
                        onclick="event.preventDefault(); confirmAction('Verify Payment', 'Are you sure you want to verify this payment?', () => this.closest('form').submit(), 'success');">
                    <i data-feather="check" class="w-4 h-4 mr-2 inline"></i>
                    Verify Payment
                </button>
            </form>
            
            <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="verified" value="0">
                <button type="submit" 
                        class="text-white px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 transition-colors"
                        onclick="event.preventDefault(); confirmAction('Reject Payment', 'Are you sure you want to reject this payment?', () => this.closest('form').submit(), 'danger', 'This will cancel the order and notify the customer.');">
                    <i data-feather="x" class="w-4 h-4 mr-2 inline"></i>
                    Reject Payment
                </button>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Update Status -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Update Order Status</h3>
    </div>
    <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="flex items-center space-x-4">
                <select name="status" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="pending_verification" {{ $order->status === 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                    <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="text-white px-4 py-2 rounded-md bg-primary-600 hover:bg-primary-700 transition-colors">
                    <i data-feather="save" class="w-4 h-4 mr-2 inline"></i>
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Back Button -->
<div class="mt-6">
    <a href="{{ route('admin.orders.index') }}" 
       class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
        <i data-feather="arrow-left" class="w-4 h-4 mr-2 inline"></i>
        Back to Orders
    </a>
</div>
@endsection
