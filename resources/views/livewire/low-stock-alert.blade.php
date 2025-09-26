@if(!empty($lowStockProducts))
    <div class="bg-white rounded-xl shadow-sm border border-red-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-200 rounded-xl flex items-center justify-center">
                    <span class="text-red-700 font-bold text-lg">!</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Low Stock Alert</h3>
                    <p class="text-sm text-gray-500">Critical inventory warnings</p>
                </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                {{ count($lowStockProducts) }} items
            </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($lowStockProducts as $variant)
                <div class="p-4 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100 transition-colors group">
                    <div class="space-y-3">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-900 leading-tight break-words">
                                {{ $variant['product'] ? $variant['product']['name'] : 'Unknown Product' }}
                            </h4>
                            <p class="text-xs text-gray-600 mt-1">{{ $variant['variant_name'] ?? 'No variant name' }}</p>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    {{ $variant['stock'] ?? 0 }} left
                                </span>
                            </div>
                            @if($variant['product'] && isset($variant['product']['id']))
                                <a href="{{ route('admin.products.edit', $variant['product']['id']) }}" 
                                   class="text-xs text-primary-600 hover:text-primary-700 font-medium underline">
                                    Restock
                                </a>
                            @else
                                <span class="text-xs text-gray-500">No link</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6 text-center">
            <a href="{{ route('admin.products.index') }}?filter=low_stock" 
               class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                <span class="mr-2 font-bold">MANAGE</span>
                Manage Inventory
            </a>
        </div>
    </div>
@endif