<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Sales Overview</h3>
            <p class="text-sm text-gray-500 mt-1">Revenue trends and performance</p>
        </div>
        <select wire:model.live="period" class="rounded-lg border-gray-200 text-sm focus:border-primary-500 focus:ring-primary-500 bg-white px-3 py-2 shadow-sm">
            <option value="7">7 days</option>
            <option value="30">30 days</option>
            <option value="90">90 days</option>
        </select>
    </div>
    
    @if(!empty($chartData))
        <div class="mb-6">
            <div class="h-48 flex items-end justify-between gap-2 px-2">
                @php
                    $maxValue = max(array_column($chartData, 'revenue'));
                    $minValue = min(array_column($chartData, 'revenue'));
                    $range = $maxValue - $minValue;
                @endphp
                @foreach($chartData as $data)
                    @php
                        $height = $range > 0 ? (($data['revenue'] - $minValue) / $range) * 120 + 20 : 20;
                    @endphp
                    <div class="flex flex-col items-center flex-1 group">
                        <div class="w-full bg-gradient-to-t from-primary-500 to-primary-400 rounded-t-lg relative transition-all duration-300 hover:from-primary-600 hover:to-primary-500" 
                             style="height: {{ $height }}px;">
                            <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                ${{ number_format($data['revenue'], 2) }}
                            </div>
                        </div>
                        <span class="text-xs text-gray-500 mt-2 font-medium">{{ $data['date'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-700 font-medium">Total Revenue</p>
                        <p class="text-xl font-bold text-green-900">${{ number_format(array_sum(array_column($chartData, 'revenue')), 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-700 font-medium">Total Orders</p>
                        <p class="text-xl font-bold text-blue-900">{{ array_sum(array_column($chartData, 'orders')) }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="h-48 flex items-center justify-center text-gray-400">
            <div class="text-center">
                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-3">
                    <span class="text-gray-500 font-bold text-lg">CHART</span>
                </div>
                <p class="text-sm">No sales data available</p>
            </div>
        </div>
    @endif
</div>