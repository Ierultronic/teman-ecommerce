<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i data-feather="tag" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Vouchers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $vouchers->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i data-feather="check-circle" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Vouchers</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $vouchers->where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i data-feather="users" class="w-6 h-6"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Usage</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $vouchers->sum('used_count') }}</p>
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
                    <p class="text-2xl font-semibold text-gray-900">{{ $vouchers->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <h3 class="text-lg font-medium text-gray-900">Voucher Management</h3>
                <p class="text-sm text-gray-500">Create and manage discount vouchers</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
                <button wire:click="openCreateModal"
                    class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                    Create Voucher
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


    <!-- Vouchers Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <!-- Mobile Cards View -->
        <div class="block lg:hidden">
            @forelse($vouchers as $voucher)
            <div class="p-6 border-b border-gray-200 last:border-b-0">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-feather="tag" class="w-6 h-6 text-green-600"></i>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-lg font-medium text-gray-900">{{ $voucher->name }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ Str::limit($voucher->description, 60) }}</p>
                                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                                    <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ strtoupper($voucher->code) }}</code>
                                    <span>•</span>
                                    @if ($voucher->type === 'percentage')
                                        <span class="font-medium text-gray-900">{{ $voucher->value }}% off</span>
                                    @else
                                        <span class="font-medium text-gray-900">RM {{ number_format($voucher->value, 2) }} off</span>
                                    @endif
                                    @if($voucher->minimum_amount)
                                        <span>•</span>
                                        <span>Min: RM {{ number_format($voucher->minimum_amount, 2) }}</span>
                                    @endif
                                    @if($voucher->maximum_discount)
                                        <span>•</span>
                                        <span>Max: RM {{ number_format($voucher->maximum_discount, 2) }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-col items-end space-y-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $voucher->status === 'active' ? 'bg-green-100 text-green-800' : ($voucher->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    {{ ucfirst($voucher->status) }}
                                </span>

                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $voucher->used_count }} uses
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-4">
                            <div class="flex items-center text-sm text-gray-500">
                                <i data-feather="calendar" class="w-4 h-4 mr-1"></i>
                                @if ($voucher->starts_at && $voucher->ends_at)
                                    {{ $voucher->starts_at->format('M d') }} - {{ $voucher->ends_at->format('M d, Y') }}
                                @elseif ($voucher->starts_at)
                                    From {{ $voucher->starts_at->format('M d, Y') }}
                                @elseif ($voucher->ends_at)
                                    Until {{ $voucher->ends_at->format('M d, Y') }}
                                @else
                                    Always valid
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" 
                                        wire:click="editVoucher({{ $voucher->id }})">
                                    <i data-feather="edit-3" class="w-3 h-3 mr-1"></i>
                                    Edit
                                </button>
                                <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" 
                                        wire:click="toggleStatus({{ $voucher->id }})">
                                    <i data-feather="{{ $voucher->status === 'active' ? 'pause' : 'play' }}" class="w-3 h-3 mr-1"></i>
                                    {{ $voucher->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                                <button class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" 
                                        wire:click="$set('confirmingDeletion', {{ $voucher->id }})">
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
                        <i data-feather="tag" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No vouchers found</h3>
                    <p class="text-gray-500 mb-4">Get started by creating your first voucher</p>
                    <button wire:click="openCreateModal"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                        Create Voucher
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Voucher</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid Period</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($vouchers as $voucher)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full mr-4 flex items-center justify-center">
                                        <i data-feather="tag" class="w-5 h-5 text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $voucher->name }}</div>
                                        <code class="bg-gray-100 px-2 py-1 rounded text-xs">{{ strtoupper($voucher->code) }}</code>
                                        @if($voucher->description)
                                            <div class="text-xs text-gray-500 mt-1">{{ Str::limit($voucher->description, 40) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $voucher->type === 'percentage' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }} mr-2">
                                        {{ ucfirst($voucher->type) }}
                                    </span>
                                    @if ($voucher->type === 'percentage')
                                        {{ $voucher->value }}%
                                    @else
                                        RM {{ number_format($voucher->value, 2) }}
                                    @endif
                                </div>
                                @if($voucher->minimum_amount)
                                    <div class="text-xs text-gray-500 mt-1">Min: RM {{ number_format($voucher->minimum_amount, 2) }}</div>
                                @endif
                                @if($voucher->maximum_discount)
                                    <div class="text-xs text-gray-500 mt-1">Max: RM {{ number_format($voucher->maximum_discount, 2) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $voucher->status === 'active' ? 'bg-green-100 text-green-800' : ($voucher->status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                    <i data-feather="{{ $voucher->status === 'active' ? 'check' : ($voucher->status === 'expired' ? 'x' : 'pause') }}" class="w-3 h-3 mr-1"></i>
                                    {{ ucfirst($voucher->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $voucher->used_count }}</div>
                                @if ($voucher->usage_limit)
                                    <div class="text-xs text-gray-500">of {{ $voucher->usage_limit }}</div>
                                @else
                                    <div class="text-xs text-gray-500">unlimited</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                @if ($voucher->starts_at && $voucher->ends_at)
                                    <div>{{ $voucher->starts_at->format('M d') }} - {{ $voucher->ends_at->format('M d, Y') }}</div>
                                @elseif ($voucher->starts_at)
                                    <div>From {{ $voucher->starts_at->format('M d, Y') }}</div>
                                @elseif ($voucher->ends_at)
                                    <div>Until {{ $voucher->ends_at->format('M d, Y') }}</div>
                                @else
                                    <div>Always</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $voucher->creator->name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-500">{{ $voucher->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" 
                                            wire:click="editVoucher({{ $voucher->id }})">
                                        <i data-feather="edit-3" class="w-3 h-3 mr-1"></i>
                                        Edit
                                    </button>
                                    <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors" 
                                            wire:click="toggleStatus({{ $voucher->id }})">
                                        <i data-feather="{{ $voucher->status === 'active' ? 'pause' : 'play' }}" class="w-3 h-3 mr-1"></i>
                                        {{ $voucher->status === 'active' ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i data-feather="tag" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No vouchers found</h3>
                                    <p class="text-gray-500 mb-4">Get started by creating your first voucher</p>
                                    <button wire:click="openCreateModal"
                                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                        <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                                        Create Voucher
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($vouchers->hasPages())
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            {{ $vouchers->links() }}
        </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <!-- Center the modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $editingModal ? 'Edit Voucher' : 'Create New Voucher' }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Configure voucher settings and validity periods</p>
                        </div>

                        <form wire:submit.prevent="{{ $editingModal ? 'updateVoucher' : 'createVoucher' }}">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Basic Information -->
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Voucher Name *</label>
                                        <input type="text" wire:model="name" id="name" placeholder="e.g., Summer Sale 2024"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label for="code" class="block text-sm font-medium text-gray-700">Voucher Code *</label>
                                            <button type="button" wire:click="generateCode" class="text-sm text-primary-600 hover:text-primary-500 px-2 py-1 border border-primary-300 rounded-md hover:bg-primary-50 cursor-pointer">Generate</button>
                                        </div>
                                        <input type="text" wire:model="code" id="code" style="text-transform: uppercase;" placeholder="SUMMER2024" value="{{ $code }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                        <textarea wire:model="description" id="description" rows="3" placeholder="Optional description"
                                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <!-- Discount Settings -->
                                <div class="space-y-4">
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

                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label for="minimumAmount" class="block text-sm font-medium text-gray-700 mb-2">Min Order (RM)</label>
                                            <input type="number" step="0.01" min="0" wire:model="minimumAmount" id="minimumAmount" placeholder="0.00"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                            @error('minimumAmount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="maximumDiscount" class="block text-sm font-medium text-gray-700 mb-2">Max Discount (RM)</label>
                                            <input type="number" step="0.01" min="0" wire:model="maximumDiscount" id="maximumDiscount" placeholder="Unlimited"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                            @error('maximumDiscount') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label for="usageLimit" class="block text-sm font-medium text-gray-700 mb-2">Usage Limit</label>
                                        <input type="number" min="1" wire:model="usageLimit" id="usageLimit" placeholder="Unlimited"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('usageLimit') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="space-y-3">
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="singleUse" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">Single Use Only</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="forFirstTimeOnly" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">First-time Customers Only</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Date Settings -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-900 mb-4">Validity Period</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="startsAt" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                        <input type="datetime-local" wire:model="startsAt" id="startsAt"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('startsAt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="endsAt" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                        <input type="datetime-local" wire:model="endsAt" id="endsAt"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('endsAt') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end space-x-3">
                                <button type="button" wire:click="closeModal" 
                                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                    Cancel
                                </button>
                                <button wire:loading.attr="disabled" type="submit" 
                                        class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    <span wire:loading.remove>{{ $editingModal ? 'Update' : 'Create' }} Voucher</span>
                                    <span wire:loading>Creating...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Confirmation Modal -->
    @if ($confirmingDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-feather="alert-triangle" class="w-6 h-6 text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Voucher</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Are you sure you want to delete this voucher? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="deleteVoucher({{ $confirmingDeletion }})"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-2 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button type="button" wire:click="$set('confirmingDeletion', null)"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-2 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Register Livewire event listener for icon refresh
    document.addEventListener('livewire:init', () => {
        // Initialize icons when component loads
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        
        // Listen for custom refresh events
        Livewire.on('refresh-feather-icons', () => {
            if (typeof feather !== 'undefined') {
                setTimeout(() => feather.replace(), 50);
            }
        });
        
        // Additional initialization for modal events
        Livewire.hook('morph.updated', ({ component }) => {
            setTimeout(() => {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }, 100);
        });
    });
</script>