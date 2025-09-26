<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ProductVariant;

class LowStockAlert extends Component
{
    public $lowStockProducts = [];
    public $threshold = 10;

    protected $listeners = ['refreshLowStockAlert'];

    public function mount()
    {
        $this->loadLowStockProducts();
    }

    public function refreshLowStockAlert()
    {
        $this->loadLowStockProducts();
    }

    public function loadLowStockProducts()
    {
        $this->lowStockProducts = ProductVariant::with('product')
            ->where('stock', '<=', $this->threshold)
            ->whereHas('product') // Only include variants that have products
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'variant_name' => $variant->variant_name,
                    'stock' => $variant->stock,
                    'product' => $variant->product ? [
                        'id' => $variant->product->id,
                        'name' => $variant->product->name,
                    ] : null,
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.low-stock-alert');
    }
}
