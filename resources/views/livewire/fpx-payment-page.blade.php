<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if($showSuccess)
            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Payment Successful!</h2>
                <p class="text-gray-600 mb-6">Your payment has been processed successfully.</p>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600">Order ID: <span class="font-mono font-semibold text-gray-900">#{{ $order->id }}</span></p>
                    <p class="text-sm text-gray-600">Amount: <span class="font-semibold text-gray-900">RM{{ number_format($order->total_price, 2) }}</span></p>
                    <p class="text-sm text-gray-600">Reference: <span class="font-mono font-semibold text-gray-900">{{ $order->payment_reference }}</span></p>
                </div>
                
                <div class="text-sm text-gray-500 space-y-1">
                    <p>• You will receive an email confirmation shortly</p>
                    <p>• Our team will process your order within 24 hours</p>
                    <p>• Use the Order ID to track your order status</p>
                </div>
                
                <div class="mt-8">
                    <a href="{{ route('store.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 transition-colors">
                        Continue Shopping
                    </a>
                </div>
            </div>
        @elseif($showError)
            <!-- Error Message -->
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-6">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Payment Failed</h2>
                <p class="text-gray-600 mb-6">{{ $errorMessage }}</p>
                
                <div class="mt-8 space-x-4">
                    <button wire:click="initiatePayment" 
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 transition-colors">
                        Try Again
                    </button>
                    <a href="{{ route('store.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Back to Store
                    </a>
                </div>
            </div>
        @else
            <!-- Payment Page -->
            <div class="bg-white rounded-lg shadow-sm p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Complete Your Payment</h1>
                    <p class="text-gray-600">Order ID: <span class="font-mono font-semibold">#{{ $order->id }}</span></p>
                    <p class="text-2xl font-bold text-green-600 mt-2">RM{{ number_format($order->total_price, 2) }}</p>
                </div>

                <!-- Payment Method Info -->
                <div class="bg-green-50 rounded-lg p-6 mb-8">
                    <div class="flex items-center mb-4">
                        <svg class="h-8 w-8 text-green-600 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <h3 class="text-lg font-semibold text-green-900">FPX Online Banking</h3>
                    </div>
                    <p class="text-green-800 text-sm">You will be redirected to your bank's secure payment page to complete the transaction.</p>
                </div>

                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h4>
                    
                    <div class="space-y-3">
                        @foreach($order->orderItems as $item)
                            <div class="flex justify-between items-start text-sm">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900">{{ $item->product->name }}</div>
                                    <div class="text-gray-600">{{ $item->productVariant->variant_name ?? 'Standard' }} x {{ $item->quantity }}</div>
                                </div>
                                <div class="text-right font-medium text-gray-900">
                                    RM{{ number_format($item->price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="border-t pt-3 mt-4">
                        <div class="flex justify-between items-center text-lg font-semibold text-gray-900">
                            <span>Total:</span>
                            <span>RM{{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Button -->
                <div class="text-center">
                    <button wire:click="initiatePayment" 
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="w-full flex justify-center py-4 px-6 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                        <span wire:loading.remove>Pay with FPX</span>
                        <span wire:loading class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>

                <!-- Security Notice -->
                <div class="mt-6 text-center">
                    <div class="flex items-center justify-center text-sm text-gray-500">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Your payment is secured with bank-level encryption
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
