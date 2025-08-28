<div class="px-4 sm:px-6 lg:px-8">
    <!-- Inline Success Message -->
    <div x-data="{ show: false, message: '', orderId: '' }" 
         x-show="show" 
         x-on:order-placed.window="show = true; message = $event.detail.message; orderId = $event.detail.orderId; setTimeout(() => show = false, 15000)"
         class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 transform transition-all duration-500"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4">
        
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-medium text-green-800 mb-2">Order Confirmed!</h3>
                <p class="text-green-700 mb-3" x-text="message"></p>
                <div class="bg-green-100 border border-green-300 rounded-md p-3 mb-3" x-show="orderId">
                    <p class="text-sm text-green-800 font-medium">Order ID: <span class="font-mono text-green-900 bg-green-200 px-2 py-1 rounded" x-text="orderId"></span></p>
                    <p class="text-xs text-green-600 mt-1">Please save this reference number for tracking your order</p>
                </div>
                <div class="text-sm text-green-600">
                    <p>• You will receive an email confirmation shortly</p>
                    <p>• Our team will process your order within 24 hours</p>
                    <p>• Use the Order ID above to track your order status</p>
                </div>
            </div>
            <button @click="show = false" class="text-green-400 hover:text-green-600">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($product->image)
                    <div class="relative">
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        <!-- Stock Status Badge -->
                        <div class="absolute top-2 right-2">
                            @if($selectedVariant[$product->id] !== null && $selectedVariant[$product->id] !== '')
                                @php
                                    $currentStock = $this->getDisplayStock($product->id);
                                @endphp
                                @if($currentStock > 0)
                                    @if($currentStock <= 5)
                                        <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">Low Stock</span>
                                    @else
                                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">In Stock</span>
                                    @endif
                                @else
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Out of Stock</span>
                                @endif
                            @else
                                <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded-full">Select Variant</span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="relative w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                        <!-- Stock Status Badge -->
                        <div class="absolute top-2 right-2">
                            @if($selectedVariant[$product->id] !== null && $selectedVariant[$product->id] !== '')
                                @php
                                    $currentStock = $this->getDisplayStock($product->id);
                                @endphp
                                @if($currentStock > 0)
                                    @if($currentStock <= 5)
                                        <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">Low Stock</span>
                                    @else
                                        <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">In Stock</span>
                                    @endif
                                @else
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Out of Stock</span>
                                @endif
                            @else
                                <span class="bg-gray-500 text-white text-xs px-2 py-1 rounded-full">Select Variant</span>
                            @endif
                        </div>
                    </div>
                @endif
                
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->description, 100) }}</p>
                    <p class="text-2xl font-bold text-green-600 mb-4">RM{{ number_format($product->price, 2) }}</p>
                    
                    <!-- Stock Information -->
                    <div class="mb-4">
                        @if($product->variants->count() > 0)
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="text-sm text-gray-600">Available Stock:</span>
                                @if($selectedVariant[$product->id] !== null && $selectedVariant[$product->id] !== '')
                                    @php
                                        $displayStock = $this->getDisplayStock($product->id);
                                    @endphp
                                    @if($displayStock > 0)
                                        <span class="text-sm font-medium {{ $displayStock <= 5 ? 'text-orange-600' : 'text-green-600' }}">
                                            {{ $displayStock }} available
                                            @if($displayStock <= 5)
                                                <span class="text-xs">(Low stock!)</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-sm font-medium text-red-600">Out of stock</span>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-500">Select variant to see stock</span>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Stock:</span>
                                <span class="text-sm font-medium text-gray-500">No variants available</span>
                            </div>
                        @endif
                    </div>
                    
                    @if($product->variants->count() > 0)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Variant: *</label>
                            <select wire:change="selectVariant({{ $product->id }}, $event.target.value)" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                                <option value="">Please select a variant</option>
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}" {{ $variant->stock <= 0 ? 'disabled' : '' }} {{ ($selectedVariant[$product->id] ?? '') == $variant->id ? 'selected' : '' }}>
                                        {{ $variant->variant_name }} 
                                        @if($variant->stock > 0)
                                            ({{ $variant->stock }} in stock)
                                        @else
                                            (Out of stock)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('variant') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    @endif
                    
                    <!-- Quantity Input -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity:</label>
                        <div class="flex items-center space-x-2">
                            <button wire:click="decrementQuantity({{ $product->id }})" 
                                    class="w-8 h-8 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300 transition-colors flex items-center justify-center"
                                    {{ (($selectedVariant[$product->id] === null || $selectedVariant[$product->id] === '') || ($quantity[$product->id] ?? 1) <= 1) ? 'disabled' : '' }}>
                                -
                            </button>
                            <input type="number" wire:model="quantity.{{ $product->id }}" 
                                    class="w-16 text-center border border-gray-300 rounded-md px-2 py-1 {{ ($selectedVariant[$product->id] === null || $selectedVariant[$product->id] === '') ? 'bg-gray-100 cursor-not-allowed' : '' }}" 
                                    min="1" max="{{ $this->getDisplayStock($product->id) }}" 
                                    value="{{ $quantity[$product->id] ?? 1 }}"
                                    {{ ($selectedVariant[$product->id] === null || $selectedVariant[$product->id] === '') ? 'disabled' : '' }}>
                            <button wire:click="incrementQuantity({{ $product->id }})" 
                                    class="w-8 h-8 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300 transition-colors flex items-center justify-center"
                                    {{ (($selectedVariant[$product->id] === null || $selectedVariant[$product->id] === '') || ($quantity[$product->id] ?? 1) >= $this->getDisplayStock($product->id)) ? 'disabled' : '' }}>
                                +
                            </button>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            @if($selectedVariant[$product->id] !== null && $selectedVariant[$product->id] !== '')
                                Max: {{ $this->getDisplayStock($product->id) }} available
                            @else
                                Please select a variant first
                            @endif
                        </div>
                    </div>
                    
                    <!-- Cart Status -->
                    @if($selectedVariant[$product->id] !== null && $selectedVariant[$product->id] !== '' && $this->getCartQuantity($product->id, $selectedVariant[$product->id]) > 0)
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-700">
                                    In cart: {{ $this->getCartQuantity($product->id, $selectedVariant[$product->id]) }} 
                                    ({{ $product->variants->find($selectedVariant[$product->id])->variant_name ?? 'Unknown' }})
                                </span>
                                <span class="text-sm font-medium text-blue-700">
                                    RM{{ number_format(($product->price * $this->getCartQuantity($product->id, $selectedVariant[$product->id])), 2) }}
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex items-center space-x-2">
                        @if($selectedVariant[$product->id] !== null && $selectedVariant[$product->id] !== '' && $this->getCartQuantity($product->id, $selectedVariant[$product->id]) > 0)
                            <button wire:click="removeFromCart('{{ $this->getCartKey($product->id, $selectedVariant[$product->id]) }}')" 
                                    class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                                Remove from Cart
                            </button>
                            <button wire:click="updateCart({{ $product->id }}, {{ $selectedVariant[$product->id] }}, {{ $quantity[$product->id] ?? 1 }})" 
                                    class="flex-1 {{ $this->getDisplayStock($product->id) > 0 ? 'bg-orange-600 hover:bg-orange-700' : 'bg-gray-400 cursor-not-allowed' }} text-white px-4 py-2 rounded-md transition-colors"
                                    {{ $this->getDisplayStock($product->id) <= 0 ? 'disabled' : '' }}>
                                {{ $this->getDisplayStock($product->id) > 0 ? 'Update Cart' : 'Out of Stock' }}
                            </button>
                        @else
                            <button wire:click="addToCart({{ $product->id }}, {{ $selectedVariant[$product->id] ?? 'null' }}, {{ $quantity[$product->id] ?? 1 }})" 
                                    class="flex-1 {{ (($selectedVariant[$product->id] !== null && $selectedVariant[$product->id] !== '') && $this->getDisplayStock($product->id) > 0) ? 'bg-orange-600 hover:bg-orange-700' : 'bg-gray-400 cursor-not-allowed' }} text-white px-4 py-2 rounded-md transition-colors"
                                    {{ (($selectedVariant[$product->id] === null || $selectedVariant[$product->id] === '') || $this->getDisplayStock($product->id) <= 0) ? 'disabled' : '' }}>
                                {{ (($selectedVariant[$product->id] === null || $selectedVariant[$product->id] === '')) ? 'Select Variant' : ($this->getDisplayStock($product->id) > 0 ? 'Add to Cart' : 'Out of Stock') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500 text-lg">No products available.</p>
            </div>
        @endforelse
    </div>

    <!-- Shopping Cart -->
    @if(count($cart) > 0)
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t shadow-lg p-4">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Items in cart: {{ $this->getCartCount() }}</span>
                    <span class="text-lg font-semibold text-green-600">Total: RM{{ number_format($this->getCartTotal(), 2) }}</span>
                </div>
                <div class="flex space-x-2">
                    <button wire:click="$set('showOrderForm', true)" class="bg-orange-600 text-white px-6 py-3 rounded-md hover:bg-orange-700 transition-colors">
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    @endif


    <!-- Order Form Modal -->
    @if($showOrderForm)
        
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-[9999]">
            <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-2xl">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Complete Your Order</h3>
                
                <!-- Cart Summary -->
                <div class="mb-4 p-4 bg-gray-50 rounded-md">
                    <h4 class="font-medium text-gray-900 mb-2">Order Summary:</h4>
                    @foreach($cart as $item)
                        <div class="flex justify-between text-sm mb-1">
                            <span>{{ $item['product_name'] }} ({{ $item['variant_name'] }}) x {{ $item['quantity'] }}</span>
                            <span>RM{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                    @endforeach
                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between font-semibold">
                            <span>Total:</span>
                            <span>RM{{ number_format($this->getCartTotal(), 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Details Form -->
                <form wire:submit.prevent="placeOrder">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" wire:model="customerName" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                            @error('customerName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" wire:model="customerEmail" class="w-full border border-gray-300 rounded-md px-3 py-2" required>
                            @error('customerEmail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" wire:model="customerPhone" class="w-full border border-gray-300 rounded-md px-3 py-2">
                            @error('customerPhone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    @error('order') <div class="text-red-500 text-sm mt-2">{{ $message }}</div> @enderror

                    <div class="flex space-x-3 mt-6">
                        <button type="button" wire:click="$set('showOrderForm', false)" 
                                class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                                class="flex-1 bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors">
                            <span wire:loading.remove>Place Order</span>
                            <span wire:loading class="flex items-center justify-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

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
