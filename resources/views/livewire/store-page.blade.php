<div class="min-h-screen bg-gray-50" 
     x-data="{
         cartCount: 0,
         cartTotal: 0,
         toastMessage: '',
         showToast: false,
         animateCartIcon: false,
         showPromotionModal: true,
         promotionBubbleVisible: false,
         init() {
            // Initialize cart values from reactive properties
            this.cartCount = this.$wire.cartCount || 0;
            this.cartTotal = parseFloat(this.$wire.cartTotal) || 0;
            
            
            // Listen for cart updates
            this.$wire.on('cart-updated', () => {
                this.cartCount = this.$wire.cartCount;
                this.cartTotal = parseFloat(this.$wire.cartTotal) || 0;
            });
            
            // Listen for voucher events - handled by Livewire listeners in component
            this.$wire.on('voucher-applied', (event) => {
                // Persist voucher to localStorage
                localStorage.setItem('applied_voucher', JSON.stringify({
                    voucher: event.voucher,
                    discount_amount: event.discount_amount
                }));
                this.showToastMessage('Voucher applied successfully!');
            });
            
            this.$wire.on('voucher-removed', () => {
                // Clear voucher from localStorage
                localStorage.removeItem('applied_voucher');
                this.showToastMessage('Voucher removed');
            });
            
            this.$wire.on('voucher-updated', (event) => {
                // Update localStorage
                const storedVoucher = JSON.parse(localStorage.getItem('applied_voucher') || '{}');
                if (storedVoucher.voucher) {
                    storedVoucher.discount_amount = event.discount_amount;
                    localStorage.setItem('applied_voucher', JSON.stringify(storedVoucher));
                }
            });
            
            this.$wire.on('voucher-failed', (event) => {
                this.showErrorToast(event.message);
            });
             
             // Listen for item added to cart
             this.$wire.on('item-added-to-cart', (event) => {
                 const productName = event.productName || 'Product';
                 const quantity = event.quantity || 1;
                 this.showToastMessage(`Added ${quantity}x ${productName} to cart!`);
                 this.animateCartIcon = true;
                 setTimeout(() => this.animateCartIcon = false, 1000);
             });
             
             // Listen for item removed from cart
             this.$wire.on('item-removed-from-cart', (event) => {
                 const productName = event.item?.product_name || 'Item';
                 this.showToastMessage(`Removed ${productName} from cart`);
             });
             
             // Listen for cart errors
             this.$wire.on('cart-error', (event) => {
                 this.showErrorToast(event.message);
             });
             
            // Load cart from localStorage
            this.$wire.on('load-cart-from-storage', () => {
                const savedCart = localStorage.getItem('teman_cart');
                if (savedCart) {
                    try {
                        const cartData = JSON.parse(savedCart);
                        this.$wire.loadCartFromStorage(cartData);
                    } catch (e) {
                    }
                }
            });
            
            // Restore voucher after cart is loaded
            this.$wire.on('restore-voucher-after-cart-load', () => {
                const savedVoucher = localStorage.getItem('applied_voucher');
                if (savedVoucher) {
                    try {
                        const voucherData = JSON.parse(savedVoucher);
                        this.$wire.restoreVoucher(voucherData);
                    } catch (e) {
                        localStorage.removeItem('applied_voucher');
                    }
                }
            });
             
             // Persist cart to localStorage
             this.$wire.on('persist-cart', (event) => {
                 localStorage.setItem('teman_cart', JSON.stringify(event.cart));
             });
             
             // Clear cart from localStorage
             this.$wire.on('clear-cart-storage', () => {
                 localStorage.removeItem('teman_cart');
             });
             
             // Initialize cart count from reactive properties
             this.cartCount = this.$wire.cartCount || 0;
             this.cartTotal = parseFloat(this.$wire.cartTotal) || 0;
             
             // Auto-hide promotion modal after 5 seconds and show bubble if not manually closed
             this.hidePromotionTimer = setTimeout(() => {
                 if (this.showPromotionModal) {
                     this.showPromotionModal = false;
                     this.promotionBubbleVisible = true;
                 }
             }, 5000);
         },
         togglePromotionModal() {
             this.showPromotionModal = !this.showPromotionModal;
             if (!this.showPromotionModal) {
                 this.promotionBubbleVisible = true;
                 // Clear the auto-hide timer if manually closed
                 if (this.hidePromotionTimer) {
                     clearTimeout(this.hidePromotionTimer);
                 }
             }
         },
         showToastMessage(message) {
             this.toastMessage = message;
             this.showToast = true;
             setTimeout(() => this.showToast = false, 3000);
         },
         showErrorToast(message) {
             this.toastMessage = message;
             this.showToast = true;
             setTimeout(() => this.showToast = false, 4000);
         }
     }">

    <!-- Floating Cart Button - Bottom Right -->
    <div class="fixed bottom-6 right-6 z-50">
        <button wire:click="toggleCartSidebar" 
                class="relative bg-orange-600 hover:bg-orange-700 text-white px-6 py-4 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 active:scale-95 flex items-center space-x-3"
                :class="{ 'animate-pulse': animateCartIcon }">
            
            <!-- Shopping Cart Icon -->
            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9z"/>
            </svg>
            
            <!-- Cart Info -->
            <div class="flex flex-col items-start">
                <span class="text-sm font-medium">Cart</span>
                <span class="text-xs opacity-90" x-text="(cartCount || 0) + ' item' + ((cartCount || 0) !== 1 ? 's' : '')"></span>
            </div>
            
            <!-- Total Price -->
            <div class="text-right">
                <div class="text-lg font-bold" x-text="'RM' + (parseFloat(cartTotal || 0)).toFixed(2)"></div>
            </div>
            
            <!-- Cart Count Badge -->
            <div x-show="(cartCount || 0) > 0" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-0"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-0"
                 class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center shadow-lg"
                 x-text="cartCount || 0"></div>
        </button>
    </div>

    <!-- Toast Notification -->
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg flex items-center space-x-2"
         :class="toastMessage.includes('error') || toastMessage.includes('Error') || toastMessage.includes('not found') || toastMessage.includes('out of stock') || toastMessage.includes('exceeds') ? 'bg-red-500 text-white' : 'bg-green-500 text-white'">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path x-show="!(toastMessage.includes('error') || toastMessage.includes('Error') || toastMessage.includes('not found') || toastMessage.includes('out of stock') || toastMessage.includes('exceeds'))" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            <path x-show="toastMessage.includes('error') || toastMessage.includes('Error') || toastMessage.includes('not found') || toastMessage.includes('out of stock') || toastMessage.includes('exceeds')" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        <span x-text="toastMessage"></span>
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

    <!-- Promotion Modal -->
    <div x-show="showPromotionModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-[9999]">
        
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto shadow-2xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            
            <!-- Modal Header -->
            <div class="bg-orange-600 p-6 rounded-t-2xl text-white relative">
                <!-- Close Button -->
                <button @click="togglePromotionModal()" 
                        class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <!-- Header Content -->
                <div class="text-center">
                    <div class="flex items-center justify-center mb-3 space-x-2">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2 2z" />
                        </svg>
                        <svg class="w-6 h-6 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-2">🔥 Hot Promotions!</h2>
                    <p class="text-orange-100">Don't miss out on these amazing deals!</p>
                </div>
            </div>
            
            <!-- Modal Content -->
            <div class="p-6">
                
                <!-- Promotions -->
                <livewire:active-promotions />
                
                <!-- Vouchers Link -->
                <div class="mt-6 pt-6 border-t border-gray-200 text-center">
                    <a href="{{ route('vouchers.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-all duration-200 font-medium shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 7l-.71.71a2 2 0 01-2.83-2.83L7 7zm0 0l5.66 5.66a2 2 0 002.83-2.83L7 7z" />
                        </svg>
                        Browse all Vouchers
                    </a>
                    <p class="text-sm text-gray-500 mt-2">Discover exclusive discount codes and deals</p>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-4 rounded-b-2xl flex justify-between items-center">
                 <p class="text-sm text-gray-600">
                     <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                     </svg>
                     Auto-closes in 5 seconds
                 </p>
                <button @click="togglePromotionModal()" 
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
                    Continue Shopping
                </button>
            </div>
        </div>
    </div>

    <!-- Floating Promotion Bubble -->
    <div x-show="promotionBubbleVisible" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-0"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-0"
         class="fixed bottom-20 left-6 z-50 cursor-pointer"
         @click="togglePromotionModal()"
         x-data="{ 
             animate: false,
             toggleAnimation() { 
                 this.animate = true; 
                 setTimeout(() => this.animate = false, 1000); 
             }
         }"
         @click="toggleAnimation()">
        
        <!-- Bubble Container -->
        <div class="relative">
            <!-- Pulse Ring -->
            <div class="absolute inset-0 bg-orange-500/30 rounded-full animate-ping"></div>
            
            <!-- Main Bubble -->
            <div class="relative bg-orange-600 rounded-full p-4 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-110"
                 :class="{ 'animate-bounce': animate }">
                
                <!-- Icon -->
                <div class="flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2 2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 7l-.71.71a2 2 0 01-2.83-2.83L7 7zm0 0l5.66 5.66a2 2 0 002.83-2.83L7 7z" />
                    </svg>
                </div>
                
                <!-- Sparkle Effects -->
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-yellow-400 rounded-full animate-pulse"></div>
                <div class="absolute -bottom-1 -left-1 w-2 h-2 bg-orange-400 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
            </div>
            
            <!-- Tooltip -->
            <div class="absolute right-14 top-1/2 transform -translate-y-1/2 bg-gray-900 text-white px-3 py-1 rounded-lg text-sm whitespace-nowrap opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity">
                Click for Promotions & Vouchers!
                <div class="absolute left-full top-1/2 transform -translate-y-1/2 w-0 h-0 border-l-8 border-l-gray-900 border-t-4 border-t-transparent border-b-4 border-b-transparent"></div>
            </div>
        </div>
    </div>

    <!-- Products Title -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-2">Our Products</h2>
            <p class="text-gray-600">Discover our amazing collection</p>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
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
                            <div class="text-2xl font-bold text-orange-600">RM{{ number_format($product->price, 2) }}</div>
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
                            <button class="w-full mt-4 bg-orange-600 text-white py-3 px-4 rounded-xl hover:bg-orange-700 transition-all duration-200 font-medium transform hover:scale-105 active:scale-95 shadow-lg hover:shadow-xl touch-manipulation"
                                    wire:click.stop="quickAddToCart({{ $product->id }})"
                                    x-data="{ 
                                        isAdding: false,
                                        handleClick() {
                                            this.isAdding = true;
                                            setTimeout(() => this.isAdding = false, 1000);
                                        }
                                    }"
                                    @click="handleClick()">
                                <span x-show="!isAdding" class="flex items-center justify-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 7a2 2 0 01-2 2H8a2 2 0 01-2-2L5 9z" />
                                    </svg>
                                    <span>Quick Add</span>
                                </span>
                                <span x-show="isAdding" class="flex items-center justify-center space-x-2">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Adding...</span>
                                </span>
                            </button>
                        @else
                            <button class="w-full mt-4 bg-gray-100 text-gray-700 py-3 px-4 rounded-xl hover:bg-gray-200 transition-colors font-medium touch-manipulation"
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

    <!-- Cart Sidebar -->
    <x-cart-sidebar 
        :show="$showCartSidebar" 
        :cart="$cart" 
        :cartTotal="$cartTotal"
        :cartCount="$cartCount"
        wire:key="cart-sidebar-{{ $showCartSidebar ? 'open' : 'closed' }}"
    />

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
        :cartTotal="$cartTotal"
        :appliedVoucher="$appliedVoucher"
        :voucherDiscountAmount="$voucherDiscountAmount"
        :automaticDiscounts="$automaticDiscounts"
        :finalTotal="$finalTotal"
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
