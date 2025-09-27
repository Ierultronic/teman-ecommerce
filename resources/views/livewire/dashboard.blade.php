<div class="space-y-8">
    <!-- Dashboard Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back!</h1>
                <p class="text-gray-600 mt-1">Here's what's happening with your store today.</p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="refreshDashboard" class="inline-flex items-center px-4 py-2 border border-gray-200 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i data-feather="refresh-cw" class="w-4 h-4 mr-2"></i>
                    Refresh
                </button>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @livewire('sales-overview')
        @livewire('order-stats')
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @livewire('product-stats')
        @livewire('recent-orders')
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @livewire('top-products')
        @livewire('low-stock-alert')
    </div>
</div>