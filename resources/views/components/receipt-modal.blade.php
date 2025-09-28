<div x-data="receiptModal()" 
     x-show="show" 
     class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
     style="display: none;">
    
    <div class="bg-white rounded-lg max-w-4xl w-full mx-4 shadow-xl max-h-[90vh] flex flex-col">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900" x-text="title"></h3>
            <button @click="close()" class="text-gray-400 hover:text-gray-600">
                <i data-feather="x" class="h-6 w-6"></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="flex-1 p-6 overflow-auto">
            <div x-show="receiptType === 'image'" class="text-center">
                <img :src="receiptUrl" :alt="title" class="max-w-full h-auto max-h-[60vh] mx-auto rounded-lg">
            </div>
            
            <div x-show="receiptType === 'pdf'" class="text-center">
                <p class="text-gray-600 mb-4">PDF receipt</p>
                <a :href="receiptUrl" :download="downloadFileName" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i data-feather="download" class="w-4 h-4 mr-2"></i>
                    Download PDF
                </a>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex justify-end p-6 border-t border-gray-200">
            <button @click="close()" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function receiptModal() {
    return {
        show: false,
        title: 'Payment Receipt',
        receiptUrl: '',
        receiptType: 'image',
        downloadFileName: 'receipt',
        
        showReceipt(config) {
            this.title = config.title || 'Payment Receipt';
            this.receiptUrl = config.url || '';
            this.receiptType = config.type || 'image';
            this.downloadFileName = config.downloadFileName || 'receipt';
            this.show = true;
        },
        
        close() {
            this.show = false;
        }
    }
}

window.showReceiptModal = function(url, title = 'Payment Receipt') {
    const extension = url.split('.').pop().toLowerCase();
    const isPdf = extension === 'pdf';
    
    const modal = document.querySelector('[x-data*="receiptModal"]');
    if (modal && modal._x_dataStack) {
        const modalData = modal._x_dataStack[0];
        modalData.showReceipt({
            title: title,
            url: url,
            type: isPdf ? 'pdf' : 'image',
            downloadFileName: `receipt-${Date.now()}`
        });
    }
};
</script>
