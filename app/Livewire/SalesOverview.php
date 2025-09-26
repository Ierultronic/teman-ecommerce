<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Carbon\Carbon;

class SalesOverview extends Component
{
    public $period = '30';
    public $chartData = [];

    protected $listeners = ['refreshSalesOverview'];

    public function mount()
    {
        $this->loadChartData();
    }

    public function updatedPeriod()
    {
        $this->loadChartData();
    }

    public function refreshSalesOverview()
    {
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $days = (int) $this->period;
        $startDate = Carbon::now()->subDays($days);
        
        $salesData = Order::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->chartData = $salesData->map(function ($item) {
            return [
                'date' => Carbon::parse($item->date)->format('M d'),
                'revenue' => (float) $item->total,
                'orders' => (int) $item->orders,
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.sales-overview');
    }
}
