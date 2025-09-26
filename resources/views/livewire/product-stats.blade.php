<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Product Statistics</h3>
            <p class="text-sm text-gray-500 mt-1">Inventory and product insights</p>
        </div>
    </div>
    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="text-center p-4 bg-purple-50 rounded-lg">
            <p class="text-sm text-purple-700 font-medium">Total Products</p>
            <p class="text-2xl font-bold text-purple-900 mt-1">{{ number_format($stats['total_products'] ?? 0) }}</p>
        </div>
        
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="text-sm text-green-700 font-medium">Active</p>
            <p class="text-2xl font-bold text-green-900 mt-1">{{ number_format($stats['active_products'] ?? 0) }}</p>
        </div>
        
        <div class="text-center p-4 bg-orange-50 rounded-lg">
            <p class="text-sm text-orange-700 font-medium">Low Stock</p>
            <p class="text-2xl font-bold text-orange-900 mt-1">{{ number_format($stats['low_stock_variants'] ?? 0) }}</p>
        </div>
        
        <div class="text-center p-4 bg-red-50 rounded-lg">
            <p class="text-sm text-red-700 font-medium">Out of Stock</p>
            <p class="text-2xl font-bold text-red-900 mt-1">{{ number_format($stats['out_of_stock_variants'] ?? 0) }}</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-indigo-700 font-medium">Total Variants</p>
                    <p class="text-xl font-bold text-indigo-900">{{ number_format($stats['total_variants'] ?? 0) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-teal-50 to-teal-100 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-teal-700 font-medium">Avg Stock Level</p>
                    <p class="text-xl font-bold text-teal-900">{{ number_format($stats['average_stock_level'] ?? 0, 1) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>