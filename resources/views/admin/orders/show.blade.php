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
                    @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
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
        </dl>
    </div>
</div>

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
