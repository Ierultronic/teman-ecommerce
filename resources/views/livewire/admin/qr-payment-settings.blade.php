<div class="space-y-6" x-data="{ showPreviewModal: @entangle('showPreviewModal'), showFeedbackModal: @entangle('showFeedbackModal') }">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">QR Payment Settings</h2>
                <p class="text-gray-600 mt-1">Configure QR code image and bank account details for payments</p>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <form wire:submit.prevent="save" class="space-y-6">
            <!-- QR Image Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    QR Code Image
                </label>
                
                <!-- Current QR Image Preview -->
                @if($current_qr_image_url)
                    <div class="mb-4">
                        <div class="inline-block border-2 border-gray-200 rounded-lg p-4">
                            <img src="{{ $current_qr_image_url }}" 
                                 alt="Current QR Code" 
                                 class="w-32 h-32 object-contain rounded-lg">
                        </div>
                        <div class="mt-2">
                            <button type="button" 
                                    wire:click="removeQrImage"
                                    class="text-sm text-red-600 hover:text-red-800 transition-colors">
                                Remove QR Image
                            </button>
                        </div>
                    </div>
                @endif

                <!-- File Upload -->
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="qr_image" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                <span>Upload QR image</span>
                                <input id="qr_image" wire:model="qr_image" type="file" class="sr-only" accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                    </div>
                </div>
                
                @if($qr_image)
                    <div class="mt-2 text-sm text-green-600">
                        ✓ File selected: {{ $qr_image->getClientOriginalName() }}
                    </div>
                @endif
                
                @error('qr_image') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Bank Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bank Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bank Name
                    </label>
                    <input type="text" 
                           wire:model="qr_bank_name" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="e.g., Maybank, CIMB Bank">
                    @error('qr_bank_name') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <!-- Account Number -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Account Number
                    </label>
                    <input type="text" 
                           wire:model="qr_account_number" 
                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                           placeholder="e.g., 1234567890">
                    @error('qr_account_number') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>
            </div>

            <!-- Account Holder Name -->
<div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Account Holder Name
                </label>
                <input type="text" 
                       wire:model="qr_account_holder_name" 
                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" 
                       placeholder="e.g., Teman Ecommerce Sdn Bhd">
                @error('qr_account_holder_name') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="button" 
                        wire:click="showPreview"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                    <span wire:loading.remove>Preview & Save</span>
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Validating...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- Preview Confirmation Modal -->
    @if($showPreviewModal)
    <div x-show="showPreviewModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-screen w-screen z-[9999]" style="top: 0; left: 0; right: 0; bottom: 0; position: fixed; margin: 0; padding: 0;" wire:click="cancelSave">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Preview QR Payment Settings</h3>
                    <button wire:click="cancelSave" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Preview of QR Payment Display</h4>
                    
                    <div class="text-center">
                        <!-- QR Code Preview -->
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-8 mb-4 inline-block">
                            <div class="w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                @if($qr_image)
                                    <img src="{{ $qr_image->temporaryUrl() }}" 
                                         alt="QR Code Preview" 
                                         class="w-full h-full object-contain rounded-lg">
                                @elseif($current_qr_image_url)
                                    <img src="{{ $current_qr_image_url }}" 
                                         alt="QR Code Preview" 
                                         class="w-full h-full object-contain rounded-lg">
                                @else
                                    <div class="text-center">
                                        <svg class="h-12 w-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                        </svg>
                                        <p class="text-xs text-gray-500">No QR Image</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Bank Details Preview -->
                        <div class="text-sm text-gray-600 space-y-1">
                            @if($qr_bank_name)
                                <p><strong>Bank:</strong> {{ $qr_bank_name }}</p>
                            @endif
                            @if($qr_account_number)
                                <p><strong>Account:</strong> {{ $qr_account_number }}</p>
                            @endif
                            @if($qr_account_holder_name)
                                <p><strong>Name:</strong> {{ $qr_account_holder_name }}</p>
                            @endif
                            @if(!$qr_bank_name && !$qr_account_number && !$qr_account_holder_name)
                                <p class="text-gray-500 italic">No bank details configured</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Confirm Changes</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Please review the QR payment settings above. Once confirmed, these settings will be applied to all future QR payment pages.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button wire:click="cancelSave" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        Cancel
                    </button>
                    <button wire:click="confirmSave" 
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="px-4 py-2 bg-orange-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                        <span wire:loading.remove>Confirm & Save</span>
                        <span wire:loading class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Feedback Modal -->
    @if($showFeedbackModal)
    <div x-show="showFeedbackModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-screen w-screen z-[9999]" style="top: 0; left: 0; right: 0; bottom: 0; position: fixed; margin: 0; padding: 0;" wire:click="closeFeedbackModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full {{ $feedbackType === 'success' ? 'bg-green-100' : 'bg-red-100' }}">
                    @if($feedbackType === 'success')
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    @endif
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-2">Notification</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">{{ $feedbackMessage }}</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button wire:click="closeFeedbackModal" 
                            class="px-4 py-2 bg-orange-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('debug', (event) => {
            console.log('Debug:', event.message);
        });
        
        // Debug Alpine.js state
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized');
        });
    });
    
    // Additional debug for modal state
    document.addEventListener('DOMContentLoaded', () => {
        console.log('DOM loaded, checking for Alpine.js');
        if (window.Alpine) {
            console.log('Alpine.js is available');
        } else {
            console.log('Alpine.js not found');
        }
        
        // Portal modals to body for full viewport coverage
        function portalModals() {
            const modals = document.querySelectorAll('.fixed.inset-0.bg-gray-600');
            modals.forEach(modal => {
                if (modal.parentNode !== document.body) {
                    document.body.appendChild(modal);
                }
            });
        }
        
        // Run portal function after Livewire updates
        document.addEventListener('livewire:navigated', portalModals);
        document.addEventListener('livewire:updated', portalModals);
        
        // Initial portal
        setTimeout(portalModals, 100);
    });
</script>