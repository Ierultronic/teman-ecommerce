<div class="w-full">
    @if($loading)
        <!-- Loading State -->
        <div class="flex items-center justify-center h-32 bg-gray-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <svg class="animate-spin h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600">Loading promotions...</span>
            </div>
        </div>
    @elseif(count($promotions) > 0)
        <!-- Promotions Display -->
        <div class="space-y-4">
            @foreach($promotions as $promotion)
                <div class="bg-orange-50 border border-orange-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                    <!-- Promotion Header -->
                    <div class="relative">
                        <!-- Banner Image or Default -->
                        <div class="h-32 bg-orange-500 relative overflow-hidden">
                            @if($promotion['banner_image'])
                                <img src="{{ Storage::url($promotion['banner_image']) }}" 
                                     alt="{{ $promotion['title'] }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <!-- Default gradient background -->
                                <div class="absolute inset-0 bg-gradient-to-r from-purple-500 to-blue-500"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                            @endif
                            
                            <!-- Promotional Badge -->
                            <div class="absolute top-3 left-3">
                                <span class="bg-white/90 text-orange-700 px-2 py-1 rounded-full text-xs font-medium shadow-sm">
                                    🔥 Hot Deal
                                </span>
                            </div>
                            
                            <!-- Expiry Notice -->
                            @if($promotion['expires_soon'] && $promotion['days_remaining'] > 0)
                                <div class="absolute top-3 right-3">
                                    <span class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-medium shadow-sm animate-pulse">
                                        {{ $promotion['days_remaining'] }} days left!
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Promotion Content -->
                    <div class="p-4">
                        <!-- Title and Description -->
                        <div class="mb-3">
                            <h3 class="text-lg font-semibold text-orange-900 mb-2">{{ $promotion['title'] }}</h3>
                            @if($promotion['description'])
                                <p class="text-sm text-orange-700 line-clamp-2">{{ $promotion['description'] }}</p>
                            @endif
                        </div>
                        
                        <!-- Promotion Type and Details -->
                        <div class="space-y-2 mb-4">
                            @switch($promotion['type'])
                                @case('buy_x_get_y')
                                    <div class="flex items-center space-x-2 text-sm">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                        <span class="text-orange-700">Buy {{ $promotion['rules']['buy_quantity'] ?? 'X' }} Get {{ $promotion['rules']['get_quantity'] ?? 'Y' }} Free</span>
                                    </div>
                                    @break
                                @case('buy_x_get_percentage')
                                    <div class="flex items-center space-x-2 text-sm">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <span class="text-orange-700">{{ $promotion['rules']['discount_percentage'] ?? 'X' }}% Discount</span>
                                    </div>
                                    @break
                                @case('bulk_discount')
                                    <div class="flex items-center space-x-2 text-sm">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <span class="text-orange-700">Bulk Discount Available</span>
                                    </div>
                                    @break
                                @case('category_discount')
                                    <div class="flex items-center space-x-2 text-sm">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 7l-.71.71a2 2 0 01-2.83-2.83L7 7zm0 0l5.66 5.66a2 2 0 002.83-2.83L7 7z" />
                                        </svg>
                                        <span class="text-orange-700">Category Special</span>
                                    </div>
                                    @break
                            @endswitch
                            
                            @if($promotion['minimum_amount'])
                                <div class="flex items-center space-x-2 text-sm text-orange-600">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    <span>Min. spend RM{{ number_format($promotion['minimum_amount'], 2) }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Call to Action -->
                        <div class="flex items-center justify-between">
                            <div class="text-xs text-orange-600">
                                @if($promotion['ends_at'])
                                    <span>Valid until {{ \Carbon\Carbon::parse($promotion['ends_at'])->format('M d, Y') }}</span>
                                @else
                                    <span>Always available</span>
                                @endif
                            </div>
                            
                            <button class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium">
                                Shop Now
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- No Promotions State -->
        <div class="text-center py-8 bg-gray-50 rounded-lg">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <p class="text-gray-600 mb-2">No active promotions</p>
            <p class="text-sm text-gray-500">Check back later for exciting deals!</p>
            
            <button wire:click="refreshPromotions" 
                    class="mt-3 text-orange-600 hover:text-orange-700 text-sm font-medium underline">
                Refresh
            </button>
        </div>
    @endif
</div>
