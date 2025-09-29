<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Promotion Management</h1>
            <p class="text-gray-600 mt-1">Create dynamic promotions and campaigns</p>
        </div>
        <button class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center" 
                wire:click="$set('showModal', true)">
            <i data-feather="plus" class="w-4 h-4 mr-2"></i>
            Create Promotion
        </button>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <i data-feather="check-circle" class="w-5 h-5 mr-2 text-green-600"></i>
            {{ session('message') }}
        </div>
    @endif

    <!-- Promotions Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valid Period</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($promotions as $promotion)
                                <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($promotion->banner_image)
                                        <img src="{{ $promotion->banner_image_url }}" alt="{{ $promotion->title }}" class="w-12 h-8 object-cover rounded">
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $promotion->title }}</div>
                                        @if($promotion->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($promotion->description, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                    {{ $promotion->type }}
                                </span>
                                @if($promotion->exclusive)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 ml-1">
                                        Exclusive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $promotion->priority }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($promotion->deleted_at)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        Deleted
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $promotion->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($promotion->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if ($promotion->starts_at && $promotion->ends_at)
                                    {{ $promotion->starts_at->format('M d') }} - {{ $promotion->ends_at->format('M d, Y') }}
                                @elseif ($promotion->starts_at)
                                    From {{ $promotion->starts_at->format('M d, Y') }}
                                @elseif ($promotion->ends_at)
                                    Until {{ $promotion->ends_at->format('M d, Y') }}
                                @else
                                    Always
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <button class="text-indigo-600 hover:text-indigo-900 p-1" 
                                            wire:click="editPromotion({{ $promotion->id }})">
                                        <i data-feather="edit-2" class="w-4 h-4"></i>
                                    </button>
                                    @if(!$promotion->deleted_at)
                                        @php
                                            $buttonClass = $promotion->status === 'active' ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900';
                                            $iconClass = $promotion->status === 'active' ? 'pause' : 'play';
                                        @endphp
                                        <button class="{{ $buttonClass }} p-1" 
                                                wire:click="toggleStatus({{ $promotion->id }})"
                                                title="Toggle status">
                                            <i data-feather="{{ $iconClass }}" class="w-4 h-4"></i>
                                        </button>
                                    @endif
                                    @if($promotion->deleted_at)
                                        <button class="text-green-600 hover:text-green-900 p-1" 
                                                wire:click="restorePromotion({{ $promotion->id }})"
                                                title="Restore promotion">
                                            <i data-feather="refresh-cw" class="w-4 h-4"></i>
                                        </button>
                                    @else
                                        <button class="text-red-600 hover:text-red-900 p-1" 
                                                wire:click="confirmDelete({{ $promotion->id }})"
                                                title="Delete promotion">
                                            <i data-feather="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-center">
                                    <i data-feather="gift" class="mx-auto h-12 w-12 text-gray-400"></i>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No promotions</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new promotion.</p>
                                    <div class="mt-6">
                                        <button type="button" 
                                                wire:click="$set('showModal', true)"
                                                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                                            <i data-feather="plus" class="w-4 h-4 mr-2"></i>
                                            Create Promotion
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($promotions->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-sm sm:px-6">
                {{ $promotions->links() }}
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $editingModal ? 'Edit Promotion' : 'Create Promotion' }}</h3>
                            <p class="text-sm text-gray-500 mt-1">Configure dynamic promotional campaigns</p>
                        </div>

                        <form wire:submit.prevent="{{ $editingModal ? 'updatePromotion' : 'createPromotion' }}">
                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Promotion Title *</label>
                                    <input type="text" wire:model="title" id="title" placeholder="e.g., Summer Sale"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Promotion Type *</label>
                                    <input type="text" wire:model="type" id="type" placeholder="e.g., Summer Sale, Black Friday Deal, New Product Launch"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Promotion Description</label>
                                    <textarea wire:model="description" id="description" rows="3" placeholder="Describe the promotion details..."
                                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="bannerImage" class="block text-sm font-medium text-gray-700 mb-2">Banner Image</label>
                                    <input type="file" wire:model="bannerImage" id="bannerImage" accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    @if ($bannerImage)
                                        <div class="mt-2">
                                            <img src="{{ $bannerImage->temporaryUrl() }}" alt="Preview" class="w-24 h-16 object-cover rounded">
                                        </div>
                                    @elseif ($editingPromotion && $editingPromotion->banner_image)
                                        <div class="mt-2">
                                            <img src="{{ $editingPromotion->banner_image_url }}" alt="Current Banner" class="w-24 h-16 object-cover rounded">
                                            <p class="text-xs text-gray-500 mt-1">Current banner image</p>
                                        </div>
                                    @endif
                                    @error('bannerImage') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                </div>


                                <div class="grid grid-cols-2 gap-4">
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

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                                        <input type="number" min="0" wire:model="priority" id="priority" placeholder="0"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        @error('priority') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="flex items-end">
                                        <label class="flex items-center">
                                            <input type="checkbox" wire:model="exclusive" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">Exclusive Promotion</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end space-x-3">
                                <button type="button" wire:click="$set('showModal', false)" 
                                        class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                    Cancel
                                </button>
                                <button wire:loading.attr="disabled" type="submit" 
                                        class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                                    <span wire:loading.remove>{{ $editingModal ? 'Update' : 'Create' }} Promotion</span>
                                    <span wire:loading>Creating...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeletion)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('confirmingDeletion', null)"></div>

                <!-- Center the modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i data-feather="alert-triangle" class="h-6 w-6 text-red-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Promotion</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Are you sure you want to delete this promotion? This action cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deletePromotion({{ $confirmingDeletion }})" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button wire:click="$set('confirmingDeletion', null)" 
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
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