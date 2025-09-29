<div class="w-full">
    <!-- Voucher Code Input Section -->
    <div class="space-y-4">
        <!-- Input Field -->
        <div class="flex flex-col space-y-2">
            <label for="couponCode" class="text-sm font-medium text-gray-700">
                Voucher Code
            </label>
            <div class="flex space-x-2">
                <input 
                    type="text" 
                    id="couponCode"
                    wire:model="couponCode"
                    wire:keydown.enter="applyCoupon"
                    placeholder="Enter your voucher code"
                    class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200 text-sm {{ $errors->has('couponCode') ? 'border-red-300 focus:ring-red-500 focus:border-red-500' : '' }}"
                    @disabled($isValidVoucher)
                >
                <button 
                    type="button"
                    wire:click="applyCoupon"
                    @disabled($isValidVoucher)
                    class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-all duration-200 font-medium text-sm"
                >
                    <span wire:loading.remove wire:target="applyCoupon">
                        Apply
                    </span>
                    <span wire:loading wire:target="applyCoupon" class="flex items-center space-x-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Applying...</span>
                    </span>
                </button>
            </div>
            @error('couponCode') 
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p> 
            @enderror
        </div>

        <!-- Success Message -->
        @if($successMessage)
            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                <div class="flex items-start space-x-3">
                    <svg class="h-5 w-5 text-green-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    <svg class="h-5 w-5 text-red-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-red-800">{{ $errorMessage }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Applied Voucher Display -->
        @if($isValidVoucher && $appliedVoucher)
            <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-orange-900">{{ $appliedVoucher->name }}</h4>
                            <p class="text-xs text-orange-700">Code: <span class="font-mono font-medium">{{ $appliedVoucher->code }}</span></p>
                            @if($appliedVoucher->description)
                                <p class="text-xs text-orange-600 mt-1">{{ $appliedVoucher->description }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-lg font-bold text-orange-900">
                            -RM{{ number_format($discountAmount, 2) }}
                        </div>
                        <button 
                            wire:click="removeVoucher"
                            class="text-xs text-orange-600 hover:text-orange-800 transition-colors underline"
                        >
                            Remove
                        </button>
                    </div>
                </div>
                
                <!-- Voucher Details -->
                <div class="mt-3 pt-3 border-t border-orange-200">
                    <div class="grid grid-cols-2 gap-2 text-xs text-orange-700">
                        @if(@$appliedVoucher->minimum_amount)
                            <div>
                                <span class="font-medium">Min. Amount:</span> RM{{ number_format($appliedVoucher->minimum_amount, 2) }}
                            </div>
                        @endif
                        @if(@$appliedVoucher->maximum_discount)
                            <div>
                                <span class="font-medium">Max. Discount:</span> RM{{ number_format($appliedVoucher->maximum_discount, 2) }}
                            </span>
                            </div>
                        @endif
                        @if(@$appliedVoucher->ends_at)
                            <div class="col-span-2">
                                <span class="font-medium">Valid until:</span> {{ $appliedVoucher->ends_at->format('M d, Y') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
