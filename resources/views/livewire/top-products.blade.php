<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Top Selling Products</h3>
            <p class="text-sm text-gray-500 mt-1">Best-performing products</p>
        </div>
        <a href="{{ route('admin.products.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1">
            View all
            <i data-feather="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>
    
    <div class="space-y-3">
        @forelse($topProducts as $index => $product)
            <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors group">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center text-sm font-bold text-primary-600 flex-shrink-0">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        @if(!empty($product['image']))
                            <img src="{{ asset('storage/' . $product['image']) }}" alt="{{ $product['name'] ?? 'Product' }}" class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-purple-600 font-bold text-lg">P</span>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900 leading-tight break-words">
                                {{ $product['name'] ?? 'Unknown Product' }}
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">{{ $product['order_items_count'] ?? 0 }} orders</p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-bold text-gray-900">${{ number_format($product['price'] ?? 0, 2) }}</p>
                        <div class="w-20 bg-gray-200 rounded-full h-2 mt-1">
                            @php
                                $maxOrders = $topProducts[0]['order_items_count'] ?? 1;
                                $currentOrders = $product['order_items_count'] ?? 0;
                                $percentage = min(100, ($currentOrders / $maxOrders) * 100);
                            @endphp
                            <div class="bg-primary-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">
                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-4">
                    <span class="text-gray-500 font-bold text-lg">PRODUCTS</span>
                </div>
                <p class="text-lg font-medium">No products available</p>
                <p class="text-sm">Add products to see top performers here</p>
            </div>
        @endforelse
    </div>
</div>