<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
            🎫 Available Vouchers
        </h1>
        <p class="text-gray-600 max-w-2xl mx-auto">
            Save money with our exclusive vouchers! Find special discount codes for your shopping.
        </p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Vouchers</label>
                <div class="relative">
                    <input type="text" 
                           id="search"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search by name, code, or description..."
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Discount Type</label>
                <select wire:model.live="filterType" 
                        id="type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <option value="">All Types</option>
                    @foreach($voucherTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Clear Filters -->
            <div class="flex items-end">
                <button wire:click="clearFilters" 
                        class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Results Count -->
    <div class="flex justify-between items-center mb-6">
        <div class="text-sm text-gray-600">
            Showing {{ $vouchers->count() }} of {{ $vouchers->total() }} vouchers
        </div>
        
        <!-- Sorting -->
        <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">Sort by:</span>
            <div class="flex space-x-2">
                <button wire:click="sortBy('created_at')" 
                        class="px-3 py-1 text-sm rounded-lg {{ $sortBy === 'created_at' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                    Latest
                </button>
                <button wire:click="sortBy('value')" 
                        class="px-3 py-1 text-sm rounded-lg {{ $sortBy === 'value' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                    Value
                </button>
                <button wire:click="sortBy('ends_at')" 
                        class="px-3 py-1 text-sm rounded-lg {{ $sortBy === 'ends_at' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-600' }}">
                    Expiry
                </button>
            </div>
        </div>
    </div>

    <!-- Vouchers Grid -->
    @if($vouchers->count() > 0)
        <div class="space-y-4">
            @foreach($vouchers as $voucher)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 group">
                    <!-- Horizontal Layout -->
                    <div class="flex flex-col lg:flex-row">
                        <!-- Left Side - Discount Display -->
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-6 text-white relative overflow-hidden lg:w-80 flex-shrink-0">
                            <!-- Background Pattern -->
                            <div class="absolute top-0 right-0 w-16 h-16 transform translate-x-4 -translate-y-4 opacity-20">
                                <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100">
                                    <circle cx="20" cy="20" r="2"/>
                                    <circle cx="40" cy="20" r="2"/>
                                    <circle cx="60" cy="20" r="2"/>
                                    <circle cx="80" cy="20" r="2"/>
                                    <circle cx="20" cy="40" r="2"/>
                                    <circle cx="40" cy="40" r="2"/>
                                    <circle cx="60" cy="40" r="2"/>
                                    <circle cx="80" cy="40" r="2"/>
                                    <circle cx="20" cy="60" r="2"/>
                                    <circle cx="40" cy="60" r="2"/>
                                    <circle cx="60" cy="60" r="2"/>
                                    <circle cx="80" cy="60" r="2"/>
                                    <circle cx="20" cy="80" r="2"/>
                                    <circle cx="40" cy="80" r="2"/>
                                    <circle cx="60" cy="80} r="2"/>
                                    <circle cx="80" cy="80" r="2"/>
                                </svg>
                            </div>
                            
                            <!-- Expiry Badge -->
                            @if($voucher->ends_at)
                                <div class="absolute top-3 right-3 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow-sm">
                                    {{ $this->getHumanReadableTimeLeft($voucher->ends_at) }}
                                </div>
                            @endif
                            
                            <!-- Discount Amount -->
                            <div class="relative z-10 text-center lg:text-left">
                                <div class="text-4xl font-bold mb-2">
                                    @if($voucher->type === 'percentage')
                                        {{ $voucher->value }}%
                                    @else
                                        RM{{ number_format($voucher->value, 2) }}
                                    @endif
                                </div>
                                <div class="text-sm opacity-90 font-medium">
                                    @if($voucher->type === 'percentage')
                                        Discount
                                    @else
                                        Off Your Order
                                    @endif
                                </div>
                                <!-- Voucher Code -->
                                <div class="mt-4 bg-white/20 px-3 py-2 rounded-lg text-sm font-mono font-bold text-center">
                                    {{ $voucher->code }}
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Details -->
                        <div class="p-6 flex-1">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $voucher->name }}</h3>
                                    @if($voucher->description)
                                        <p class="text-gray-600 text-sm line-clamp-2">{{ $voucher->description }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Terms Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                                @if($voucher->minimum_amount)
                                    <div class="flex items-center space-x-2 text-sm text-gray-700">
                                        <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                        </svg>
                                        <span>Min. spend RM{{ number_format($voucher->minimum_amount, 2) }}</span>
                                    </div>
                                @endif
                                
                                @if($voucher->maximum_discount)
                                    <div class="flex items-center space-x-2 text-sm text-gray-700">
                                        <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Max. discount RM{{ number_format($voucher->maximum_discount, 2) }}</span>
                                    </div>
                                @endif
                                
                                @if($voucher->usage_limit_per_user)
                                    <div class="flex items-center space-x-2 text-sm text-gray-700">
                                        <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor}>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>Limit {{ $voucher->usage_limit_per_user }} per user</span>
                                    </div>
                                @endif
                                
                                @if($voucher->ends_at)
                                    <div class="flex items-center space-x-2 text-sm text-gray-700">
                                        <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span>
                                            Valid until {{ $voucher->ends_at->format('M d, Y') }}
                                            @if($voucher->ends_at->isToday())
                                                (Today!)
                                            @elseif($voucher->ends_at->isTomorrow())
                                                (Tomorrow)
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Copy Code Button -->
                            <button 
                                onclick="copyVoucherCode('{{ $voucher->code }}', this)"
                                class="w-full bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-all duration-200 font-medium flex items-center justify-center space-x-2"
                                data-code="{{ $voucher->code }}">
                                <svg class="w-5 h-5 flex-shrink-0 copy-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span class="copy-text">Copy Code</span>
                                <svg class="w-5 h-5 flex-shrink-0 hidden check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $vouchers->links() }}
        </div>
    @else
        <!-- No Results -->
        <div class="text-center py-12 bg-gray-50 rounded-xl">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 7h.01M7 7l-.71.71a2 2 0 01-2.83-2.83L7 7zm0 0l5.66 5.66a2 2 0 002.83-2.83L7 7z" />
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No vouchers found</h3>
            <p class="text-gray-600 mb-4">Try adjusting your search criteria or check back later for new vouchers.</p>
            <button wire:click="clearFilters" class="text-purple-600 hover:text-purple-700 font-medium underline">
                Clear all filters
            </button>
        </div>
    @endif
</div>

<!-- Copy Code JavaScript -->
<script>
function copyVoucherCode(code, buttonElement) {
    // Modern clipboard API
    if (navigator.clipboard && browserSupportsAPIs) {
        navigator.clipboard.writeText(code).then(function() {
            showCopySuccess(buttonElement);
        }).catch(function(err) {
            console.error('Could not copy voucher code: ', err);
            fallbackCopyTextToClipboard(code, buttonElement);
        });
    } else {
        // Fallback for older browsers
        fallbackCopyTextToClipboard(code, buttonElement);
    }
}

function fallbackCopyTextToClipboard(text, buttonElement) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    
    // Avoid scrolling to bottom
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        const msg = successful ? 'fallback: Copied text was: ' + text : 'fallback: Unable to copy';
        console.log('Fallback: Copying text command was ' + msg);
        
        if (successful) {
            showCopySuccess(buttonElement);
        } else {
            showCopyError(text);
        }
    } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
        showCopyError(text);
    }
    
    document.body.removeChild(textArea);
}

function showCopySuccess(buttonElement) {
    // Get elements
    const copyText = buttonElement.querySelector('.copy-text');
    const copyIcon = buttonElement.querySelector('.copy-icon');
    const checkIcon = buttonElement.querySelector('.check-icon');
    
    // Store original state
    const originalText = copyText.textContent;
    const originalBgColor = buttonElement.className;
    
    // Update button state
    buttonElement.classList.add('bg-green-600');
    buttonElement.classList.remove('bg-orange-600', 'hover:bg-orange-700');
    
    // Update text and icons
    copyText.textContent = 'Copied!';
    copyIcon.classList.add('hidden');
    checkIcon.classList.remove('hidden');
    
    // Reset after 2 seconds
    setTimeout(() => {
        buttonElement.className = originalBgColor;
        copyText.textContent = originalText;
        copyIcon.classList.remove('hidden');
        checkIcon.classList.add('hidden');
    }, 2000);
}

function showCopyError(code) {
    // Create a temporary notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <span>Could not copy code automatically. Please copy manually:</span>
        </div>
        <div class="mt-2 bg-white/20 px-3 py-1 rounded font-mono text-sm">${code}</div>
    `;
    
    document.body.appendChild(notification);
    
    // Remove after 5 seconds
    setTimeout(() => {
        document.body.removeChild(notification);
    }, 5000);
}

// Check if browser supports modern clipboard API
const browserSupportsAPIs = true;
</script>
