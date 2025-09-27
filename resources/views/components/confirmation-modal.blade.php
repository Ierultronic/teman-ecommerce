{{-- Reusable Alpine.js Confirmation Modal Component --}}
<div x-data="confirmationModal()" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
     style="display: none;">
    
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
        
        <!-- Modal Header -->
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0">
                <svg x-show="type === 'danger'" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <svg x-show="type === 'warning'" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 19.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <svg x-show="type === 'info'" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg x-show="type === 'success'" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-gray-900" x-text="title"></h3>
            </div>
        </div>
        
        <!-- Modal Body -->
        <div class="mb-6">
            <p class="text-gray-600" x-text="message"></p>
            <div x-show="details" class="mt-3 p-3 bg-gray-50 rounded-md">
                <p class="text-sm text-gray-700" x-text="details"></p>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex justify-end space-x-3">
            <button @click="cancel()" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                <span x-text="cancelText"></span>
            </button>
            <button @click="confirm()" 
                    :class="confirmButtonClass"
                    class="px-4 py-2 text-sm font-medium text-white rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2">
                <span x-text="confirmText"></span>
            </button>
        </div>
    </div>
</div>

<script>
function confirmationModal() {
    return {
        show: false,
        title: 'Confirm Action',
        message: 'Are you sure?',
        details: '',
        type: 'danger', // danger, warning, info, success
        confirmText: 'Confirm',
        cancelText: 'Cancel',
        confirmAction: null,
        cancelAction: null,
        
        get confirmButtonClass() {
            const classes = {
                'danger': 'bg-red-600 hover:bg-red-700 focus:ring-red-500',
                'warning': 'bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500',
                'info': 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
                'success': 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
            };
            return classes[this.type] || classes['danger'];
        },
        
        // Method to show the confirmation modal
        showConfirmation(config) {
            this.title = config.title || 'Confirm Action';
            this.message = config.message || 'Are you sure?';
            this.details = config.details || '';
            this.type = config.type || 'danger';
            this.confirmText = config.confirmText || 'Confirm';
            this.cancelText = config.cancelText || 'Cancel';
            this.confirmAction = config.confirmAction || null;
            this.cancelAction = config.cancelAction || null;
            this.show = true;
        },
        
        // Method to confirm the action
        confirm() {
            if (this.confirmAction && typeof this.confirmAction === 'function') {
                this.confirmAction();
            }
            this.show = false;
        },
        
        // Method to cancel the action
        cancel() {
            if (this.cancelAction && typeof this.cancelAction === 'function') {
                this.cancelAction();
            }
            this.show = false;
        }
    }
}

// Global helper function to show confirmation modal
window.showConfirmation = function(config) {
    // Find the confirmation modal component
    const modal = document.querySelector('[x-data*="confirmationModal"]');
    if (modal && modal._x_dataStack) {
        const modalData = modal._x_dataStack[0];
        modalData.showConfirmation(config);
    }
};

// Helper functions for common confirmation types
window.confirmDelete = function(message, confirmAction, details = '') {
    window.showConfirmation({
        title: 'Delete Confirmation',
        message: message,
        details: details,
        type: 'danger',
        confirmText: 'Delete',
        cancelText: 'Cancel',
        confirmAction: confirmAction
    });
};

window.confirmOrder = function(message, confirmAction, details = '') {
    window.showConfirmation({
        title: 'Order Confirmation',
        message: message,
        details: details,
        type: 'info',
        confirmText: 'Confirm Order',
        cancelText: 'Cancel',
        confirmAction: confirmAction
    });
};

window.confirmAction = function(title, message, confirmAction, type = 'danger', details = '') {
    window.showConfirmation({
        title: title,
        message: message,
        details: details,
        type: type,
        confirmAction: confirmAction
    });
};
</script>
