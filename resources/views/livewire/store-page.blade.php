<div class="px-4 sm:px-6 lg:px-8">
    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($products as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                @if($product->image)
                    <div class="relative">
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        <!-- Stock Status Badge -->
                        <div class="absolute top-2 right-2">
                            @if($product->total_stock > 0)
                                @if($product->total_stock <= 5)
                                    <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">Low Stock</span>
                                @else
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">In Stock</span>
                                @endif
                            @else
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="relative w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No Image</span>
                        <!-- Stock Status Badge -->
                        <div class="absolute top-2 right-2">
                            @if($product->total_stock > 0)
                                @if($product->total_stock <= 5)
                                    <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">Low Stock</span>
                                @else
                                    <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">In Stock</span>
                                @endif
                            @else
                                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                @endif
                
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->description, 100) }}</p>
                    <p class="text-2xl font-bold text-green-600 mb-4">${{ number_format($product->price, 2) }}</p>
                    
                    <!-- Stock Information -->
                    <div class="mb-4">
                        @if($product->variants->count() > 0)
                            <div class="flex items-center space-x-2 mb-2">
                                <span class="text-sm text-gray-600">Available Stock:</span>
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
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Variant:</label>
                            <select wire:model="selectedVariant.{{ $product->id }}" class="w-full border border-gray-300 rounded-md px-3 py-2">
                                <option value="">Base Product</option>
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}" {{ $variant->stock <= 0 ? 'disabled' : '' }}>
                                        {{ $variant->variant_name }} 
                                        @if($variant->stock > 0)
                                            ({{ $variant->stock }} in stock)
                                        @else
                                            (Out of stock)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    
                    <!-- Quantity Input -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity:</label>
                        <div class="flex items-center space-x-2">
                            <button wire:click="decrementQuantity({{ $product->id }})" 
                                    class="w-8 h-8 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300 transition-colors flex items-center justify-center"
                                    {{ ($quantity[$product->id] ?? 1) <= 1 ? 'disabled' : '' }}>
                                -
                            </button>
                            <input type="number" wire:model="quantity.{{ $product->id }}" 
                                    class="w-16 text-center border border-gray-300 rounded-md px-2 py-1" 
                                    min="1" max="{{ $this->getDisplayStock($product->id) }}" 
                                    value="{{ $quantity[$product->id] ?? 1 }}">
                            <button wire:click="incrementQuantity({{ $product->id }})" 
                                    class="w-8 h-8 bg-gray-200 text-gray-600 rounded-md hover:bg-gray-300 transition-colors flex items-center justify-center"
                                    {{ ($quantity[$product->id] ?? 1) >= $this->getDisplayStock($product->id) ? 'disabled' : '' }}>
                                +
                            </button>
                        </div>
                        <div class="text-xs text-gray-500 mt-1">
                            Max: {{ $this->getDisplayStock($product->id) }} available
                        </div>
                    </div>
                    
                    <!-- Cart Status -->
                    @if($this->getCartQuantity($product->id, $selectedVariant[$product->id] ?? null) > 0)
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-blue-700">
                                    In cart: {{ $this->getCartQuantity($product->id, $selectedVariant[$product->id] ?? null) }} 
                                    @if($selectedVariant[$product->id])
                                        ({{ $product->variants->find($selectedVariant[$product->id])->variant_name ?? 'Unknown' }})
                                    @else
                                        (Base Product)
                                    @endif
                                </span>
                                <span class="text-sm font-medium text-blue-700">
                                    ${{ number_format(($product->price * $this->getCartQuantity($product->id, $selectedVariant[$product->id] ?? null)), 2) }}
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex items-center space-x-2">
                        @if($this->getCartQuantity($product->id, $selectedVariant[$product->id] ?? null) > 0)
                            <button wire:click="removeFromCart('{{ $this->getCartKey($product->id, $selectedVariant[$product->id] ?? null) }}')" 
                                    class="flex-1 bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors">
                                Remove from Cart
                            </button>
                            <button wire:click="addToCart({{ $product->id }}, {{ $selectedVariant[$product->id] ?? 'null' }}, {{ $quantity[$product->id] ?? 1 }})" 
                                    class="flex-1 {{ $this->getDisplayStock($product->id) > 0 ? 'bg-orange-600 hover:bg-orange-700' : 'bg-gray-400 cursor-not-allowed' }} text-white px-4 py-2 rounded-md transition-colors"
                                    {{ $this->getDisplayStock($product->id) <= 0 ? 'disabled' : '' }}>
                                {{ $this->getDisplayStock($product->id) > 0 ? 'Update Cart' : 'Out of Stock' }}
                            </button>
                        @else
                            <button wire:click="addToCart({{ $product->id }}, {{ $selectedVariant[$product->id] ?? 'null' }}, {{ $quantity[$product->id] ?? 1 }})" 
                                    class="flex-1 {{ $this->getDisplayStock($product->id) > 0 ? 'bg-orange-600 hover:bg-orange-700' : 'bg-gray-400 cursor-not-allowed' }} text-white px-4 py-2 rounded-md transition-colors"
                                    {{ $this->getDisplayStock($product->id) <= 0 ? 'disabled' : '' }}>
                                {{ $this->getDisplayStock($product->id) > 0 ? 'Add to Cart' : 'Out of Stock' }}
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
                    <span class="text-lg font-semibold text-green-600">Total: ${{ number_format($this->getCartTotal(), 2) }}</span>
                </div>
                <button wire:click="showOrderForm" class="bg-orange-600 text-white px-6 py-3 rounded-md hover:bg-orange-700 transition-colors">
                    Proceed to Checkout
                </button>
            </div>
        </div>
    @endif

    <!-- Order Form Modal -->
    @if($showOrderForm)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Complete Your Order</h3>
                
                <!-- Cart Summary -->
                <div class="mb-4 p-4 bg-gray-50 rounded-md">
                    <h4 class="font-medium text-gray-900 mb-2">Order Summary:</h4>
                    @foreach($cart as $item)
                        <div class="flex justify-between text-sm mb-1">
                            <span>{{ $item['product_name'] }} ({{ $item['variant_name'] }}) x {{ $item['quantity'] }}</span>
                            <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                        </div>
                    @endforeach
                    <div class="border-t pt-2 mt-2">
                        <div class="flex justify-between font-semibold">
                            <span>Total:</span>
                            <span>${{ number_format($this->getCartTotal(), 2) }}</span>
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
                        <button type="submit" class="flex-1 bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition-colors">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Success Message -->
    <div x-data="{ show: false, message: '' }" 
         x-show="show" 
         x-on:order-placed.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 5000)"
         class="fixed top-4 right-4 bg-orange-500 text-white px-6 py-3 rounded-md shadow-lg z-50">
        <span x-text="message"></span>
    </div>
</div>
