<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\ProductVariant;

class OrderStats extends Component
{
    public $stats = [];

    protected $listeners = ['refreshOrderStats'];

    public function mount()
    {
        $this->loadStats();
    }

    public function refreshOrderStats()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::sum('total_price'),
            'average_order_value' => Order::avg('total_price'),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->sum('total_price'),
        ];
    }

    public function render()
    {
        return view('livewire.order-stats');
    }
}
