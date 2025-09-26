<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if($showSuccess)
            <!-- Success Message -->
            <div class="bg-white rounded-lg shadow-sm p-8 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                    <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Payment Submitted Successfully!</h2>
                <p class="text-gray-600 mb-6">We have received your payment receipt and will verify it shortly.</p>
                
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-gray-600">Order ID: <span class="font-mono font-semibold text-gray-900">#{{ $order->id }}</span></p>
                    <p class="text-sm text-gray-600">Amount: <span class="font-semibold text-gray-900">RM{{ number_format($order->total_price, 2) }}</span></p>
                    <p class="text-sm text-gray-600">Reference: <span class="font-mono font-semibold text-gray-900">{{ $order->payment_reference }}</span></p>
                </div>
                
                <div class="text-sm text-gray-500 space-y-1">
                    <p>• You will receive an email confirmation once payment is verified</p>
                    <p>• Our team will process your order within 24 hours</p>
                    <p>• Use the Order ID to track your order status</p>
                </div>
                
                <div class="mt-8">
                    <a href="{{ route('store.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 transition-colors">
                        Continue Shopping
                    </a>
                </div>
            </div>
        @else
            <!-- Success Messages -->
            @if(session('extraction-success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="text-green-800 text-sm">{{ session('extraction-success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Payment Instructions -->
            <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Complete Your Payment</h1>
                    <p class="text-gray-600">Order ID: <span class="font-mono font-semibold">#{{ $order->id }}</span></p>
                    <p class="text-2xl font-bold text-green-600 mt-2">RM{{ number_format($order->total_price, 2) }}</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- QR Code Section -->
                    <div class="text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Scan QR Code to Pay</h3>
                        
                        <!-- QR Code Placeholder -->
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-8 mb-4 inline-block">
                            <div class="w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="h-16 w-16 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                    </svg>
                                    <p class="text-sm text-gray-500">QR Code</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-600 space-y-2">
                            <p><strong>Bank:</strong> {{ $qrData['bank'] }}</p>
                            <p><strong>Account:</strong> {{ $qrData['account'] }}</p>
                            <p><strong>Name:</strong> {{ $qrData['name'] }}</p>
                            <p><strong>Amount:</strong> RM{{ number_format($qrData['amount'], 2) }}</p>
                            <p><strong>Reference:</strong> {{ $qrData['reference'] }}</p>
                        </div>
                        
                        <div class="mt-4">
                            <button class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Download QR
                            </button>
                        </div>
                    </div>

                    <!-- Receipt Upload Section -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Upload Payment Receipt</h3>
                        
                        <form wire:submit.prevent="uploadReceipt" class="space-y-6">
                            <!-- Payment Reference -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Payment Reference Number *
                                </label>
                                <input type="text" 
                                       wire:model="paymentReference" 
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                                       placeholder="Enter reference number from your bank receipt"
                                       required>
                                @error('paymentReference') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Receipt Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Upload Receipt *
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label for="receipt" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                                <span>Upload a file</span>
                                                <input id="receipt" wire:model="receipt" type="file" class="sr-only" accept="image/*,.pdf">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                                    </div>
                                </div>
                                
                                @if($receipt)
                                    <div class="mt-2 text-sm text-green-600">
                                        ✓ File selected: {{ $receipt->getClientOriginalName() }}
                                    </div>
                                    
                                    <!-- Extract Reference Button -->
                                    <div class="mt-3">
                                        <button type="button" 
                                                wire:click="extractReference"
                                                wire:loading.attr="disabled"
                                                wire:loading.class="opacity-50 cursor-not-allowed"
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <span wire:loading.remove wire:target="extractReference">
                                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                Extract Reference ID
                                            </span>
                                            <span wire:loading wire:target="extractReference" class="flex items-center">
                                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Extracting...
                                            </span>
                                        </button>
                                    </div>
                                    
                                    @if($extractedReference)
                                        <div class="mt-2 text-sm text-green-600">
                                            ✓ Reference ID extracted: <span class="font-mono font-semibold">{{ $extractedReference }}</span>
                                        </div>
                                    @endif
                                    
                                    @error('extraction') 
                                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                                    @enderror
                                @endif
                                
                                @error('receipt') 
                                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" 
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50 cursor-not-allowed"
                                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                                    <span wire:loading.remove>Submit Receipt</span>
                                    <span wire:loading class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Uploading...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="mt-8 bg-blue-50 rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-blue-900 mb-3">Payment Instructions</h4>
                    <div class="text-sm text-blue-800 space-y-2">
                        <p>1. Scan the QR code above with your mobile banking app</p>
                        <p>2. Enter the exact amount: <strong>RM{{ number_format($order->total_price, 2) }}</strong></p>
                        <p>3. Use the reference: <strong>{{ $qrData['reference'] }}</strong></p>
                        <p>4. Complete the payment and save the receipt</p>
                        <p>5. Upload the receipt above with the payment reference number</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
