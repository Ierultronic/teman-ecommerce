<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>Admin - Order #{{ $order->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center space-x-8">
                        <img src="{{ asset('images/logo.png') }}" alt="Store Logo" class="w-10 h-10 rounded-lg mr-3">
                        <h1 class="text-3xl font-bold text-gray-900">Admin Panel</h1>
                        <nav class="flex space-x-4">
                            <a href="{{ route('admin.products.index') }}" class="text-gray-600 hover:text-gray-900">Products</a>
                            <a href="{{ route('admin.orders.index') }}" class="text-blue-600 font-medium">Orders</a>
                        </nav>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('store.index') }}" class="text-gray-600 hover:text-gray-900">View Store</a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
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
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('M d, Y H:i') }}</dd>
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
                            <select name="status" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class=" text-white px-4 py-2 rounded-md bg-orange-600 hover:bg-orange-700 transition-colors">
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
                    ← Back to Orders
                </a>
            </div>
        </main>
    </div>
</body>
</html>
