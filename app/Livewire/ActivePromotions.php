<?php

namespace App\Livewire;

use App\Models\Promotion;
use Livewire\Component;

class ActivePromotions extends Component
{
    public $promotions = [];
    public $loading = true;

    public function mount()
    {
        $this->loadPromotions();
    }

    public function loadPromotions()
    {
        $this->loading = true;
        
        $this->promotions = Promotion::active()
            ->where('status', 'active')
            ->orderBy('priority', 'desc')
            ->limit(3) // Show only top 3 promotions
            ->get()
            ->map(function ($promotion) {
                return [
                    'id' => $promotion->id,
                    'title' => $promotion->title,
                    'description' => $promotion->description,
                    'banner_image' => $promotion->banner_image,
                    'type' => $promotion->type,
                    'rules' => $promotion->rules,
                    'minimum_amount' => $promotion->minimum_amount,
                    'ends_at' => $promotion->ends_at,
                    'days_remaining' => $promotion->ends_at ? now()->diffInDays($promotion->ends_at, false) : null,
                    'expires_soon' => $promotion->ends_at && now()->diffInDays($promotion->ends_at, false) <= 7,
                ];
            })
            ->toArray();

        $this->loading = false;
    }

    public function refreshPromotions()
    {
        $this->loadPromotions();
    }

    public function render()
    {
        return view('livewire.active-promotions');
    }
}
