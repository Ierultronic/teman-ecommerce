<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
            <p class="text-sm text-gray-500 mt-1">Latest customer orders</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
            View all
            <i data-feather="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>
    
    <div class="space-y-3">
        @forelse($orders as $order)
            <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 font-bold text-lg">{{ $loop->iteration }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-gray-900">Order #{{ $order['id'] ?? 'N/A' }}</h4>
                        <p class="text-xs text-gray-500 mt-1 break-words">
                            {{ $order['customer_name'] ?? 'Unknown Customer' }} • 
                            {{ isset($order['created_at']) ? \Carbon\Carbon::parse($order['created_at'])->diffForHumans() : 'Unknown date' }}
                        </p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold text-gray-900">${{ number_format($order['total_price'] ?? 0, 2) }}</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium mt-1
                            @if(($order['status'] ?? '') === 'pending') bg-yellow-100 text-yellow-800
                            @elseif(($order['status'] ?? '') === 'processing') bg-blue-100 text-blue-800
                            @elseif(($order['status'] ?? '') === 'shipped') bg-purple-100 text-purple-800
                            @elseif(($order['status'] ?? '') === 'delivered') bg-green-100 text-green-800
                            @elseif(($order['status'] ?? '') === 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($order['status'] ?? 'unknown') }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">
                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <span class="text-gray-500 font-bold text-lg">ORDERS</span>
                </div>
                <p class="text-lg font-medium">No recent orders</p>
                <p class="text-sm">Orders will appear here once customers start placing them</p>
            </div>
        @endforelse
    </div>
</div>