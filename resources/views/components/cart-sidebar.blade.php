@props(['show' => false, 'cart' => [], 'cartTotal' => 0, 'cartCount' => 0])

<div x-data="{ show: @entangle('showCartSidebar') }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 overflow-hidden"
     @click.self="show = false; $wire.closeCartSidebar()">
    
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Sidebar -->
    <div class="absolute right-0 top-0 h-full w-full max-w-sm sm:max-w-md bg-white shadow-2xl"
         :class="show ? 'translate-x-0' : 'translate-x-full'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-x-full"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-x-0"
         x-transition:leave-end="opacity-0 transform translate-x-full">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Shopping Cart</h2>
            <button @click="show = false; $wire.closeCartSidebar()" 
                    class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        
        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-6">
            @if(count($cart) > 0)
                <div class="space-y-4">
                    @foreach($cart as $key => $item)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl">
                            <!-- Product Image -->
                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if(isset($item['product_image']) && $item['product_image'])
                                    <img src="{{ Storage::url($item['product_image']) }}" 
                                         alt="{{ $item['product_name'] }}" 
                                         class="w-full h-full object-cover rounded-lg">
                                @else
                                    <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>
                            
                            <!-- Product Details -->
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-gray-900 truncate">{{ $item['product_name'] }}</h3>
                                @if($item['variant_name'])
                                    <p class="text-xs text-gray-500">{{ $item['variant_name'] }}</p>
                                @endif
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-sm font-semibold text-orange-600">RM{{ number_format($item['price'], 2) }}</span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm text-gray-600">Qty: {{ $item['quantity'] }}</span>
                                        <button wire:click="removeFromCart('{{ $key }}')" 
                                                class="p-1 text-red-500 hover:text-red-700 hover:bg-red-50 rounded transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9z"/>
                        
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                    <p class="text-gray-500">Add some products to get started!</p>
                </div>
            @endif
        </div>
        
        <!-- Footer -->
        @if(count($cart) > 0)
            <div class="border-t border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-lg font-semibold text-gray-900">Total</span>
                    <span class="text-2xl font-bold text-orange-600">RM{{ number_format($cartTotal, 2) }}</span>
                </div>
                <button @click="show = false; $wire.closeCartSidebar(); $wire.$set('showOrderForm', true)" 
                        class="w-full bg-orange-600 text-white py-3 px-4 rounded-xl hover:bg-orange-700 transition-colors font-semibold">
                    Proceed to Checkout
                </button>
            </div>
        @endif
    </div>
</div>
