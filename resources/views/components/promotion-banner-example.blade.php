{{-- 
    Example Usage of Promotion Banner Component
    
    Usage Examples:
    1. Horizontal Banner (default):
        <livewire:promotion-banner />
    
    2. Vertical Banner:
        <livewire:promotion-banner style="vertical" limit="4" />
    
    3. Square Banner (2x2 grid):
        <livewire:promotion-banner style="square" limit="4" />
    
    4. Non-shuffled (ordered by priority):
        <livewire:promotion-banner shuffle="false" />
    
    Parameters:
    - style: 'horizontal', 'vertical', 'square'
    - limit: number of promotions to show (default: 3)
    - shuffle: true/false for random order (default: true)
--}}

<div class="bg-gray-50 p-6 rounded-lg border-2 border-dashed border-gray-300">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Promotion Banner Examples</h3>
    
    <div class="space-y-6">
        <div>
            <h4 class="text-md font-medium text-gray-600 mb-2 align-left">Horizontal Banner:</h4>
            <livewire:promotion-banner />
        </div>
        
        <div>
            <h4 class="text-md font-medium text-gray-600 mb-2 align-left">Square Banner (2x2 Grid):</h4>
            <livewire:promotion-banner style="square" limit="4" />
        </div>
        
        <div>
            <h4 class="text-md font-medium text-gray-600 mb-2 align-left">Vertical Banner:</h4>
            <livewire:promotion-banner style="vertical" limit="3" />
        </div>
        
        <div>
            <h4 class="text-md font-medium text-gray-600 mb-2 align-left">Ordered by Priority (No Shuffle):</h4>
            <livewire:promotion-banner shuffle="false" />
        </div>
    </div>
</div>
