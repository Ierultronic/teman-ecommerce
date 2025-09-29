<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i data-feather="percent" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Discounts</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $discounts->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i data-feather="check-circle" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Discounts</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $discounts->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i data-feather="users" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">First-time Only</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $discounts->where('for_first_time_only', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i data-feather="plus" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $discounts->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <h3 class="text-lg font-medium text-gray-900">Discount Management</h3>
                <p class="text-sm text-gray-500">Create and manage automatic discounts</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <button wire:click="$set('showModal', true)"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                    Create Discount
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <i data-feather="check-circle" class="w-5 h-5 mr-2 text-green-600"></i>
            {{ session('message') }}
        </div>
    @endif

    <!-- Discounts Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Mobile Cards View -->
        <div class="block lg:hidden">
            @forelse($discounts as $discount)
            <div class="p-6 border-b border-gray-200 last:border-b-0">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-feather="percent" class="w-6 h-6 text-purple-600"></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900">{{ $discount->name }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($discount->description, 60) }}</p>
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                    @if ($discount->type === 'percentage')
                                        <span class="font-medium text-gray-900">{{ $discount->value }}% off</span>
                                    @else
                                        <span class="font-medium text-gray-900">RM {{ number_format($discount->value, 2) }} off</span>
                                    @endif
                                    @if($discount->minimum_amount)
                                        <span>•</span>
                                        <span>Min: RM {{ number_format($discount->minimum_amount, 2) }}</span>
                                    @endif
                                    @if($discount->for_first_time_only)
                                        <span>•</span>
                                        <span class="text-blue-600">First-time only</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col items-end space-y-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $discount->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($discount->status) }}
                                </span>

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $discount->used_count }} uses
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i data-feather="calendar" class="w-4 h-4 mr-1"></i>
                                @if ($discount->starts_at && $discount->ends_at)
                                    {{ $discount->starts_at->format('M d') }} - {{ $discount->ends_at->format('M d, Y') }}
                                @elseif ($discount->starts_at)
                                    From {{ $discount->starts_at->format('M d, Y') }}
                                @elseif ($discount->ends_at)
                                    Until {{ $discount->ends_at->format('M d, Y') }}
                                @else
                                    Always valid
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" 
                                        wire:click="editDiscount({{ $discount->id }})">
                                    <i data-feather="edit-3" class="w-3 h-3 mr-1"></i>
                                    Edit
                                </button>
                                <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" 
                                        wire:click="toggleStatus({{ $discount->id }})">
                                    <i data-feather="{{ $discount->status === 'active' ? 'pause' : 'play' }}" class="w-3 h-3 mr-1"></i>
                                    {{ $discount->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                                <button class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" 
                                        wire:click="$set('confirmingDeletion', {{ $discount->id }})">
                                    <i data-feather="trash-2" class="w-3 h-3 mr-1"></i>
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i data-feather="percent" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No discounts found</h3>
                    <p class="text-gray-500 mb-4">Get started by creating your first discount</p>
                    <button wire:click="$set('showModal', true)"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                        Create Discount
                    </button>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($discounts as $discount)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full mr-4 flex items-center justify-center">
                                        <i data-feather="percent" class="w-5 h-5 text-purple-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $discount->name }}</div>
                                        @if($discount->description)
                                            <div class="text-xs text-gray-500">{{ Str::limit($discount->description, 40) }}</div>
                                        @endif
                                        @if($discount->for_first_time_only)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 mt-1">First-time only</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $discount->type === 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($discount->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    @if ($discount->type === 'percentage')
                                        {{ $discount->value }}%
                                    @else
                                        RM {{ number_format($discount->value, 2) }}
                                    @endif
                                </div>
                                @if($discount->minimum_amount)
                                    <div class="text-xs text-gray-500">Min: RM {{ number_format($discount->minimum_amount, 2) }}</div>
                                @endif
                                @if($discount->maximum_discount)
                                    <div class="text-xs text-gray-500">Max: RM {{ number_format($discount->maximum_discount, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $discount->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    <i data-feather="{{ $discount->status === 'active' ? 'check' : 'pause' }}" class="w-3 h-3 mr-1"></i>
                                    {{ ucfirst($discount->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $discount->used_count }}</div>
                                @if ($discount->usage_limit)
                                    <div class="text-xs text-gray-500">of {{ $discount->usage_limit }}</div>
                                @else
                                    <div class="text-xs text-gray-500">unlimited</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if ($discount->starts_at && $discount->ends_at)
                                    <div>{{ $discount->starts_at->format('M d') }} - {{ $discount->ends_at->format('M d, Y') }}</div>
                                @elseif ($discount->starts_at)
                                    <div>From {{ $discount->starts_at->format('M d, Y') }}</div>
                                @elseif ($discount->ends_at)
                                    <div>Until {{ $discount->ends_at->format('M d, Y') }}</div>
                                @else
                                    <div>Always</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $discount->creator->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">{{ $discount->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" 
                                            wire:click="editDiscount({{ $discount->id }})">
                                        <i data-feather="edit-3" class="w-3 h-3 mr-1"></i>
                                        Edit
                                    </button>
                                    <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" 
                                            wire:click="toggleStatus({{ $discount->id }})">
                                        <i data-feather="{{ $discount->status === 'active' ? 'pause' : 'play' }}" class="w-3 h-3 mr-1"></i>
                                        {{ $discount->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i data-feather="percent" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No discounts found</h3>
                                    <p class="text-gray-500 mb-4">Get started by creating your first discount</p>
                                    <button wire:click="$set('showModal', true)"
                                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                        <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                                        Create Discount
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($discounts->hasPages())
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            {{ $discounts->links() }}
        </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showModal', false)"></div>

                <!-- Center the modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $editingModal ? 'Edit Discount' : 'Create Discount' }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Configure automatic discount rules</p>
                        </div>

                        <form wire:submit.prevent="{{ $editingModal ? 'updateDiscount' : 'createDiscount' }}">
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Discount Name *</label>
                                    <input type="text" wire:model="name" id="name" placeholder="e.g., Welcome Discount"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Discount Type *</label>
                                    <select wire:model="type" id="type"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <option value="percentage">Percentage</option>
                                        <option value="fixed">Fixed Amount</option>
                                    </select>
                                    @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="value" class="block text-sm font-medium text-gray-700 mb-2">
                                        Discount Value * ({{ $type === 'percentage' ? '%' : 'RM' }})
                                    </label>
                                    <input type="number" step="0.01" min="0" wire:model="value" id="value"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    @error('value') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="minimumAmount" class="block text-sm font-medium text-gray-700 mb-2">Min Order (RM)</label>
                                        <input type="number" step="0.01" min="0" wire:model="minimumAmount" id="minimumAmount" placeholder="0.00"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('minimumAmount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="usageLimit" class="block text-sm font-medium text-gray-700 mb-2">Usage Limit</label>
                                        <input type="number" min="1" wire:model="usageLimit" id="usageLimit" placeholder="Unlimited"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('usageLimit') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="forFirstTimeOnly" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">First-time Customers Only</span>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end space-x-3">
                                <button type="button" wire:click="$set('showModal', false)" 
                                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                    Cancel
                                </button>
                                <button wire:loading.attr="disabled" type="submit" 
                                        class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    <span wire:loading.remove>{{ $editingModal ? 'Update' : 'Create' }} Discount</span>
                                    <span wire:loading>Creating...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    function initializeFeatherIcons() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    // Initialize Feather icons on page load
    document.addEventListener('DOMContentLoaded', initializeFeatherIcons);
    
    // Re-initialize Feather icons when Livewire updates
    document.addEventListener('livewire:updated', initializeFeatherIcons);
    document.addEventListener('livewire:navigated', initializeFeatherIcons);
    
    // Listen for custom refresh event
    document.addEventListener('livewire:init', () => {
        Livewire.on('refresh-feather-icons', initializeFeatherIcons);
    });
    
    // Backup method - run after a short delay when anything changes
    document.addEventListener('livewire:updated', function() {
        setTimeout(initializeFeatherIcons, 100);
    });
</script>