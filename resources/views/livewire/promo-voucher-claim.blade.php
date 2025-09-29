<div class="w-full">
    <!-- Quick Voucher Validation -->
    <div class="bg-gray-50 rounded-xl p-4 border-2 border-dashed border-orange-200">
        <div class="text-center mb-4">
            <h4 class="text-lg font-semibold text-gray-900 mb-2">Have a Voucher Code?</h4>
            <p class="text-sm text-gray-600">Enter your code to check validity before checkout</p>
        </div>
        
        <div class="space-y-4">
            <!-- Input Field -->
            <div class="flex space-x-2">
                <input 
                    type="text" 
                    wire:model="couponCode"
                    wire:keydown.enter="applyCoupon"
                    placeholder="Enter voucher code..."
                    class="flex-1 px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-sm {{ $errors->has('couponCode') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}"
                    @disabled($isValidVoucher)
                >
                <button 
                    type="button"
                    wire:click="applyCoupon"
                    wire:loading.attr="disabled"
                    @disabled($isValidVoucher)
                    class="px-4 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-all duration-200 font-medium text-sm min-w-[80px]">
                    <span wire:loading.remove wire:target="applyCoupon">
                        Check
                    </span>
                    <span wire:loading wire:target="applyCoupon" class="flex items-center space-x-1">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Checking...</span>
                    </span>
                </button>
            </div>
            
            @error('couponCode') 
                <p class="text-red-600 text-xs">
                    <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $message }}
                </p> 
            @enderror

            <!-- Success Message -->
            @if($successMessage)
                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-green-800">{{ $successMessage }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if($errorMessage)
                <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                    <div class="flex items-start space-x-3">
                        <svg class="h-5 w-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-red-800">{{ $errorMessage }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Validated Voucher Display -->
            @if($isValidVoucher && $voucherInfo)
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h5 class="text-sm font-semibold text-green-900">{{ $voucherInfo['name'] }}</h5>
                                <p class="text-xs text-green-700">Code: <span class="font-mono font-medium">{{ $voucherInfo['code'] }}</span></p>
                                @if($voucherInfo['description'])
                                    <p class="text-xs text-green-600 mt-1">{{ $voucherInfo['description'] }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-900">
                                @if($voucherInfo['type'] === 'percentage')
                                    {{ $voucherInfo['value'] }}%
                                @else
                                    RM{{ number_format($voucherInfo['value'], 2) }}
                                @endif
                            </div>
                            <button 
                                wire:click="clearVoucher"
                                class="text-xs text-green-600 hover:text-green-800 transition-colors underline">
                                Clear
                            </button>
                        </div>
                    </div>
                    
                    <!-- Quick Terms -->
                    <div class="grid grid-cols-2 gap-2 text-xs text-green-700">
                        @if($voucherInfo['minimum_amount'])
                            <div>
                                <span class="font-medium">Min. Amount:</span> RM{{ number_format($voucherInfo['minimum_amount'], 2) }}
                            </div>
                        @endif
                        @if($voucherInfo['maximum_discount'])
                            <div>
                                <span class="font-medium">Max. Discount:</span> RM{{ number_format($voucherInfo['maximum_discount'], 2) }}
                            </div>
                        @endif
                        @if($voucherInfo['ends_at'])
                            <div class="col-span-2">
                                <span class="font-medium">Valid until:</span> {{ \Carbon\Carbon::parse($voucherInfo['ends_at'])->format('M d, Y') }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-3 pt-3 border-t border-green-200 text-center">
                        <p class="text-xs text-green-600">
                            ✓ This voucher will be applied during checkout
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
