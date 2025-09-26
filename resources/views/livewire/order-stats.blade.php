<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Order Statistics</h3>
            <p class="text-sm text-gray-500 mt-1">Current order status overview</p>
        </div>
    </div>
    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="text-center p-4 bg-blue-50 rounded-lg">
            
            <p class="text-sm text-blue-700 font-medium">Total Orders</p>
            <p class="text-2xl font-bold text-blue-900 mt-1">{{ number_format($stats['total_orders'] ?? 0) }}</p>
        </div>
        
        <div class="text-center p-4 bg-yellow-50 rounded-lg">
            <p class="text-sm text-yellow-700 font-medium">Pending</p>
            <p class="text-2xl font-bold text-yellow-900 mt-1">{{ number_format($stats['pending_orders'] ?? 0) }}</p>
        </div>
        
        <div class="text-center p-4 bg-green-50 rounded-lg">
            <p class="text-sm text-green-700 font-medium">Delivered</p>
            <p class="text-2xl font-bold text-green-900 mt-1">{{ number_format($stats['delivered_orders'] ?? 0) }}</p>
        </div>
        
        <div class="text-center p-4 bg-red-50 rounded-lg">
            <p class="text-sm text-red-700 font-medium">Cancelled</p>
            <p class="text-2xl font-bold text-red-900 mt-1">{{ number_format($stats['cancelled_orders'] ?? 0) }}</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-indigo-700 font-medium">Today's Orders</p>
                    <p class="text-xl font-bold text-indigo-900">{{ number_format($stats['today_orders'] ?? 0) }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-green-700 font-medium">Today's Revenue</p>
                    <p class="text-xl font-bold text-green-900">${{ number_format($stats['today_revenue'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>