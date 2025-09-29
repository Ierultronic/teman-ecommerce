<?php

namespace App\Livewire;

use App\Models\Promotion;
use Livewire\Component;

class PromotionBanner extends Component
{
    public $style = 'horizontal'; // horizontal, vertical, square
    public $limit = 3;
    public $shuffle = true;

    public function render()
    {
        $promotions = Promotion::forBanners()
            ->when($this->shuffle, function($query) {
                $query->inRandomOrder();
            }, function($query) {
                $query->orderBy('priority', 'desc');
            })
            ->take($this->limit)
            ->get();

        return view('livewire.promotion-banner', compact('promotions'));
    }
}
