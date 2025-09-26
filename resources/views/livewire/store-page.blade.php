<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Our Products</h1>
                    <p class="text-gray-600 mt-1">Discover our amazing collection</p>
                </div>
                @if(count($cart) > 0)
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Items in cart</div>
                            <div class="text-2xl font-bold text-orange-600">{{ $this->getCartCount() }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Total</div>
                            <div class="text-2xl font-bold text-green-600">RM{{ number_format($this->getCartTotal(), 2) }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Success Message -->
    <div x-data="{ show: false, message: '', orderId: '' }" 
         x-show="show" 
         x-on:order-placed.window="show = true; message = $event.detail.message; orderId = $event.detail.orderId; setTimeout(() => show = false, 15000)"
         class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4">
        
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-semibold text-green-800 mb-2">Order Confirmed!</h3>
                    <p class="text-green-700 mb-4" x-text="message"></p>
                    <div class="bg-green-100 border border-green-300 rounded-lg p-4 mb-4" x-show="orderId">
                        <p class="text-sm text-green-800 font-medium">Order ID: <span class="font-mono text-green-900 bg-green-200 px-3 py-1 rounded-lg" x-text="orderId"></span></p>
                        <p class="text-xs text-green-600 mt-2">Please save this reference number for tracking your order</p>
                    </div>
                    <div class="text-sm text-green-600 space-y-1">
                        <p>• You will receive an email confirmation shortly</p>
                        <p>• Our team will process your order within 24 hours</p>
                        <p>• Use the Order ID above to track your order status</p>
                    </div>
                </div>
                <button @click="show = false" class="text-green-400 hover:text-green-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden group cursor-pointer"
                     wire:click="openProductModal({{ $product->id }})">
                    
                    <!-- Product Image -->
                    <div class="relative overflow-hidden">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-gray-500 text-xs">No Image</p>
                                </div>
                            </div>
                        @endif
                        

                        <!-- Quick View Overlay -->
                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="bg-white/90 backdrop-blur-sm rounded-full p-3">
                                    <svg class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-5">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                        
                        <!-- Price -->
                        <div class="mb-4">
                            <div class="text-2xl font-bold text-green-600">RM{{ number_format($product->price, 2) }}</div>
                            @if($product->variants->count() > 0)
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $product->variants->count() }} variant{{ $product->variants->count() > 1 ? 's' : '' }} available
                                </div>
                            @endif
                        </div>

                        <!-- Stock Summary -->
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Available:</span>
                            <span class="font-medium text-gray-700">
                                @php
                                    $totalStock = $this->getTotalStock($product->id);
                                @endphp
                                {{ $totalStock > 0 ? $totalStock . ' items' : 'Unavailable' }}
                            </span>
                        </div>

                        <!-- Quick Add Button (if only one variant) -->
                        @if($product->variants->count() == 1 && $product->variants->first()->stock > 0)
                            <button class="w-full mt-4 bg-orange-600 text-white py-2 px-4 rounded-xl hover:bg-orange-700 transition-colors font-medium"
                                    wire:click.stop="addToCart({{ $product->id }}, {{ $product->variants->first()->id }}, 1)">
                                Quick Add
                            </button>
                        @else
                            <button class="w-full mt-4 bg-gray-100 text-gray-700 py-2 px-4 rounded-xl hover:bg-gray-200 transition-colors font-medium"
                                    wire:click.stop="openProductModal({{ $product->id }})">
                                View Details
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-16">
                    <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Products Available</h3>
                    <p class="text-gray-600">Check back later for new products!</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Shopping Cart -->
    @if(count($cart) > 0)
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-xl p-4 z-40">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01" />
                        </svg>
                        <span class="text-sm text-gray-600">Items in cart: <span class="font-semibold text-orange-600">{{ $this->getCartCount() }}</span></span>
                    </div>
                    <div class="text-right">
                        <div class="text-sm text-gray-600">Total</div>
                        <div class="text-2xl font-bold text-green-600">RM{{ number_format($this->getCartTotal(), 2) }}</div>
                    </div>
                </div>
                <button wire:click="$set('showOrderForm', true)" 
                        class="bg-orange-600 text-white px-8 py-3 rounded-xl hover:bg-orange-700 transition-colors font-semibold shadow-lg hover:shadow-xl">
                    Proceed to Checkout
                </button>
            </div>
        </div>
    @endif

    <!-- Product Detail Modal -->
    <x-product-modal 
        :show="$showProductModal" 
        :product="$selectedProduct"
        :selectedVariant="$selectedVariant"
        :quantity="$quantity"
        :currentVariantId="$currentVariantId"
        :currentVariant="$currentVariant"
        :currentStock="$currentStock"
        wire:key="product-modal-{{ $showProductModal ? 'open' : 'closed' }}"
    />

    <!-- Order Form Modal -->
    <x-order-modal 
        :show="$showOrderForm" 
        :cart="$cart" 
        :cartTotal="$this->getCartTotal()"
        wire:key="order-modal-{{ $showOrderForm ? 'open' : 'closed' }}"
    />

    <!-- Bottom Toast Notification -->
    <div x-data="{ show: false, message: '', orderId: '' }" 
         x-show="show" 
         x-on:order-placed.window="show = true; message = $event.detail.message; orderId = $event.detail.orderId; setTimeout(() => show = false, 10000)"
         class="fixed bottom-20 left-1/2 transform -translate-x-1/2 bg-white border border-green-200 text-gray-800 px-8 py-6 rounded-xl shadow-2xl z-50 max-w-lg w-full mx-4"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 transform translate-y-full scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform translate-y-full scale-95">
        
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Thank You!</h3>
            <p class="text-sm text-gray-600 mb-3" x-text="message"></p>
            <div class="bg-green-50 border border-green-200 rounded-lg p-3 mb-4" x-show="orderId">
                <p class="text-xs text-green-700 font-medium">Order ID: <span class="font-mono text-green-800" x-text="orderId"></span></p>
                <p class="text-xs text-green-600 mt-1">Please save this for your records</p>
            </div>
            <div class="text-xs text-gray-500 mb-4">
                <p>We'll send you an email confirmation shortly.</p>
                <p>You can track your order status using the Order ID above.</p>
            </div>
            <button @click="show = false" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                Continue Shopping
            </button>
        </div>
    </div>
</div>
