@extends('admin.layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders Management')

@section('content')
    @php
        // Safe variables for null checks
        $ordersCount = $orders ? $orders->count() : 0;
        $ordersTotal = $orders ? $orders->total() : 0;
        $currentStatus = request('status', '');
        $currentSearch = request('search', '');
    @endphp
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i data-feather="shopping-cart" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $ordersTotal }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i data-feather="clock" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $orders && $ordersCount > 0 ? $orders->where('status', 'pending')->count() : 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i data-feather="check-circle" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $orders && $ordersCount > 0 ? $orders->where('status', 'delivered')->count() : 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i data-feather="dollar-sign" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-semibold text-gray-900">RM{{ number_format($orders && $ordersCount > 0 ? $orders->sum('total_price') : 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Orders List</h3>
                    <p class="text-sm text-gray-500">Manage customer orders and track their status</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex space-x-2">
                        <!-- Search Input -->
                        <div class="relative">
                            <input name="search" 
                                   type="text" 
                                   value="{{ $currentSearch }}"
                                   placeholder="Search orders..." 
                                   class="border border-gray-300 rounded-lg pl-10 pr-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-feather="search" class="h-4 w-4 text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Status Filter -->
                        <select name="status" 
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="pending" {{ $currentStatus === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $currentStatus === 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $currentStatus === 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $currentStatus === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $currentStatus === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        
                        <!-- Filter Button -->
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <i data-feather="filter" class="w-4 h-4 mr-1"></i>
                            Filter
                        </button>
                        
                        <!-- Clear Filters Button -->
                        @if($currentStatus || $currentSearch)
                            <a href="{{ route('admin.orders.index') }}" 
                               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                <i data-feather="x" class="w-4 h-4 mr-1"></i>
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if($orders && $ordersCount > 0)
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3">
                                        <i data-feather="shopping-bag" class="w-5 h-5 text-primary-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->orderItems->count() }} item{{ $order->orderItems->count() !== 1 ? 's' : '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <i data-feather="user" class="w-4 h-4 text-gray-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                                        @if($order->customer_phone)
                                            <div class="text-xs text-gray-400">{{ $order->customer_phone }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">RM{{ number_format($order->total_price, 2) }}</div>
                                @if($order->orderItems->count() > 0)
                                    <div class="text-xs text-gray-500">{{ $order->orderItems->count() }} item{{ $order->orderItems->count() !== 1 ? 's' : '' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'clock'],
                                        'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'settings'],
                                        'shipped' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'truck'],
                                        'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'check-circle'],
                                        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'x-circle']
                                    ];
                                    $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                    <i data-feather="{{ $config['icon'] }}" class="w-3 h-3 mr-1"></i>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $order->created_at->format('H:ia') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                        <i data-feather="eye" class="w-3 h-3 mr-1"></i>
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i data-feather="shopping-cart" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
                                    <p class="text-gray-500 mb-4">
                                        @if($currentStatus || $currentSearch)
                                            No orders match your current filters. Try adjusting your search criteria.
                                        @else
                                            Orders will appear here when customers make purchases
                                        @endif
                                    </p>
                                    @if($currentStatus || $currentSearch)
                                        <a href="{{ route('admin.orders.index') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <i data-feather="refresh-cw" class="w-4 h-4 mr-2"></i>
                                            Clear Filters
                                        </a>
                                    @else
                                        <a href="{{ route('store.index') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                            <i data-feather="external-link" class="w-4 h-4 mr-2"></i>
                                            View Store
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        @if($orders && $ordersCount > 0 && $orders->hasPages())
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@endsection
