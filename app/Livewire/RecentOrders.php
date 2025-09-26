<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class RecentOrders extends Component
{
    public $orders = [];
    public $limit = 5;

    protected $listeners = ['refreshRecentOrders'];

    public function mount()
    {
        $this->loadOrders();
    }

    public function refreshRecentOrders()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = Order::with(['orderItems.product'])
            ->latest()
            ->limit($this->limit)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'created_at' => $order->created_at,
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.recent-orders');
    }
}
