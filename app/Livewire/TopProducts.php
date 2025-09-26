<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Product;

class TopProducts extends Component
{
    public $topProducts = [];
    public $limit = 5;

    protected $listeners = ['refreshTopProducts'];

    public function mount()
    {
        $this->loadTopProducts();
    }

    public function refreshTopProducts()
    {
        $this->loadTopProducts();
    }

    public function loadTopProducts()
    {
        $this->topProducts = Product::withCount(['orderItems'])
            ->orderBy('order_items_count', 'desc')
            ->limit($this->limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'order_items_count' => $product->order_items_count,
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.top-products');
    }
}
