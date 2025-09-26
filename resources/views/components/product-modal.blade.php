@props(['show' => false, 'product' => null, 'selectedVariant' => null, 'quantity' => 1, 'currentVariantId' => null, 'currentVariant' => null, 'currentStock' => 0])

@if($show && $product)
<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-[9999] overflow-y-auto" 
     x-data="{ show: @entangle('showProductModal') }"
     @click.self="show = false; $wire.closeProductModal()">
    <div class="bg-white rounded-2xl max-w-4xl w-full shadow-2xl my-4 max-h-[90vh] overflow-y-auto" x-show="show" x-transition>
        <div class="relative">
            <!-- Close Button -->
            <button @click="show = false" wire:click="closeProductModal" class="absolute top-4 right-4 z-10 bg-white/80 hover:bg-white rounded-full p-2 transition-colors">
                <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                <!-- Product Image Section -->
                <div class="relative bg-gray-50 rounded-t-2xl lg:rounded-l-2xl lg:rounded-tr-none min-h-[400px] lg:min-h-[500px]">
                    @if($product->image)
                        <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" 
                             class="w-full h-full object-contain rounded-t-2xl lg:rounded-l-2xl lg:rounded-tr-none p-4">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center rounded-t-2xl lg:rounded-l-2xl lg:rounded-tr-none">
                            <div class="text-center">
                                <svg class="h-16 w-16 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-500 text-sm">No Image Available</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Product Details Section -->
                <div class="p-8">
                    <div class="space-y-6">
                        <!-- Product Title & Description -->
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-3">{{ $product->name }}</h2>
                            <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                        </div>

                        <!-- Price Display -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            @if($currentVariant && $currentVariant->price)
                                <div class="text-3xl font-bold text-green-600">
                                    RM{{ number_format($currentVariant->price, 2) }}
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Variant Price</p>
                            @else
                                <div class="text-3xl font-bold text-green-600">
                                    RM{{ number_format($product->price, 2) }}
                                </div>
                                <p class="text-sm text-gray-500 mt-1">Base Price</p>
                            @endif
                        </div>

                        <!-- Variant Selection -->
                        @if($product->variants->count() > 0)
                            <div>
                                <label class="block text-lg font-semibold text-gray-900 mb-3">Select Variant</label>
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach($product->variants as $variant)
                                        <label class="relative {{ $variant->stock <= 0 ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                                            <input type="radio" 
                                                   wire:model="selectedVariant.{{ $product->id }}" 
                                                   wire:click="selectVariant({{ $product->id }}, {{ $variant->id }})"
                                                   value="{{ $variant->id }}"
                                                   class="sr-only peer"
                                                   {{ $variant->stock <= 0 ? 'disabled' : '' }}>
                                            <div class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-gray-300 transition-colors {{ $variant->stock <= 0 ? 'opacity-50 bg-gray-50' : '' }}">
                                                <div class="flex-1">
                                                    <div class="font-medium {{ $variant->stock <= 0 ? 'text-gray-500' : 'text-gray-900' }}">{{ $variant->variant_name }}</div>
                                                    <div class="text-sm {{ $variant->stock <= 0 ? 'text-gray-400' : 'text-gray-600' }}">
                                                        @if($variant->price)
                                                            RM{{ number_format($variant->price, 2) }}
                                                        @else
                                                            RM{{ number_format($product->price, 2) }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-sm font-medium {{ $variant->stock <= 0 ? 'text-gray-400' : 'text-gray-600' }}">
                                                        {{ $variant->stock > 0 ? $variant->stock . ' available' : 'Unavailable' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('variant') <span class="text-red-500 text-sm mt-2">{{ $message }}</span> @enderror
                            </div>
                        @endif

                        <!-- Quantity Selection -->
                        <div>
                            <label class="block text-lg font-semibold text-gray-900 mb-3">Quantity</label>
                            <div class="flex items-center space-x-4">
                                <button wire:click="decrementQuantity({{ $product->id }})" 
                                        class="w-12 h-12 {{ (!$currentVariantId || ($quantity[$product->id] ?? 1) <= 1) ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }} rounded-full transition-colors flex items-center justify-center text-xl font-bold"
                                        {{ (!$currentVariantId || ($quantity[$product->id] ?? 1) <= 1) ? 'disabled' : '' }}>
                                    -
                                </button>
                                <input type="number" 
                                       wire:model="quantity.{{ $product->id }}" 
                                       class="w-20 text-center border-2 border-gray-300 rounded-xl px-3 py-3 text-lg font-semibold {{ !$currentVariantId ? 'bg-gray-100 cursor-not-allowed' : '' }}" 
                                       min="1" 
                                       max="{{ $currentStock }}" 
                                       value="{{ $quantity[$product->id] ?? 1 }}"
                                       {{ !$currentVariantId ? 'disabled' : '' }}
                                       wire:change="updateQuantity({{ $product->id }}, $event.target.value)">
                                <button wire:click="incrementQuantity({{ $product->id }})" 
                                        class="w-12 h-12 {{ (!$currentVariantId || ($quantity[$product->id] ?? 1) >= $currentStock) ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }} rounded-full transition-colors flex items-center justify-center text-xl font-bold"
                                        {{ (!$currentVariantId || ($quantity[$product->id] ?? 1) >= $currentStock) ? 'disabled' : '' }}>
                                    +
                                </button>
                            </div>
                            <div class="text-sm text-gray-500 mt-2">
                                @if($currentVariantId)
                                    @if(($quantity[$product->id] ?? 1) >= $currentStock)
                                        <span class="text-orange-600 font-medium">Maximum quantity reached</span>
                                    @else
                                        Max: {{ $currentStock }} available
                                    @endif
                                @else
                                    Please select a variant first
                                @endif
                            </div>
                        </div>

                        <!-- Cart Status -->
                        @if($currentVariantId && $this->getCartQuantity($product->id, $currentVariantId) > 0)
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-blue-700 font-medium">
                                        In cart: {{ $this->getCartQuantity($product->id, $currentVariantId) }} 
                                        ({{ $currentVariant->variant_name ?? 'Unknown' }})
                                    </span>
                                    <span class="text-blue-700 font-bold">
                                        RM{{ number_format(($currentVariant?->price ?? $product->price) * $this->getCartQuantity($product->id, $currentVariantId), 2) }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex space-x-3 pt-4">
                            @if($currentVariantId && $this->getCartQuantity($product->id, $currentVariantId) > 0)
                                <button wire:click="removeFromCart('{{ $this->getCartKey($product->id, $currentVariantId) }}')" 
                                        class="flex items-center justify-center space-x-2 bg-red-50 text-red-600 px-4 py-3 rounded-lg hover:bg-red-100 transition-colors border border-red-200 hover:border-red-300 group">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    <span class="font-medium text-sm">Remove</span>
                                </button>
                                <button wire:click="updateCart({{ $product->id }}, {{ $currentVariantId }}, {{ $quantity[$product->id] ?? 1 }})" 
                                        class="flex-1 flex items-center justify-center space-x-2 {{ $currentStock > 0 ? 'bg-orange-600 hover:bg-orange-700 text-white' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }} px-4 py-3 rounded-lg transition-colors group"
                                        {{ $currentStock <= 0 ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    <span class="font-medium text-sm">{{ $currentStock > 0 ? 'Update' : 'Out of Stock' }}</span>
                                </button>
                            @else
                                <button wire:click="addToCart({{ $product->id }}, {{ $currentVariantId ?? 'null' }}, {{ $quantity[$product->id] ?? 1 }})" 
                                        class="flex-1 flex items-center justify-center space-x-2 {{ ($currentVariantId && $currentStock > 0) ? 'bg-orange-600 hover:bg-orange-700 text-white' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }} px-4 py-3 rounded-lg transition-colors group"
                                        {{ (!$currentVariantId || $currentStock <= 0) ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9z" />
                                    </svg>
                                    <span class="font-medium text-sm">
                                        @if(!$currentVariantId)
                                            Select Variant
                                        @elseif($currentStock > 0)
                                            Add to Cart
                                        @else
                                            Out of Stock
                                        @endif
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
