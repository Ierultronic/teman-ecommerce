<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class ProductStats extends Component
{
    public $stats = [];

    protected $listeners = ['refreshProductStats'];

    public function mount()
    {
        $this->loadStats();
    }

    public function refreshProductStats()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_products' => Product::count(),
            'active_products' => Product::whereNull('deleted_at')->count(),
            'deleted_products' => Product::onlyTrashed()->count(),
            'total_variants' => ProductVariant::count(),
            'low_stock_variants' => ProductVariant::where('stock', '<=', 10)->count(),
            'out_of_stock_variants' => ProductVariant::where('stock', 0)->count(),
            'total_stock_value' => ProductVariant::sum(DB::raw('stock * COALESCE(price, 0)')),
            'average_stock_level' => ProductVariant::avg('stock'),
        ];
    }

    public function render()
    {
        return view('livewire.product-stats');
    }
}
