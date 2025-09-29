<?php

namespace App\Livewire\Admin;

use App\Models\Promotion;
use Livewire\Component;
use Livewire\WithPagination;

class PromotionManagement extends Component
{
    use WithPagination;

    // Form properties
    public $title = '';
    public $description = '';
    public $type = 'buy_x_get_y';
    public $minimumAmount = '';
    public $status = 'active';
    public $startsAt = '';
    public $endsAt = '';
    public $priority = 0;
    public $exclusive = false;
    
    // Rules for different promotion types
    public $buyQuantity = '';
    public $getQuantity = '';
    public $itemPrice = '';
    public $discountPercentage = '';
    public $bulkDiscounts = [];
    
    // UI state
    public $showModal = false;
    public $editingModal = false;
    public $editingPromotion = null;
    public $confirmingDeletion = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:buy_x_get_y,buy_x_get_percentage,bulk_discount,category_discount',
        'minimumAmount' => 'nullable|numeric|min:0',
        'status' => 'required|in:active,inactive',
        'startsAt' => 'nullable|date',
        'endsAt' => 'nullable|date|after:startsAt',
        'priority' => 'integer|min:0',
        'exclusive' => 'boolean',
    ];

    public function mount()
    {
        $this->initializeBulkDiscount();
    }

    public function initializeBulkDiscount()
    {
        $this->bulkDiscounts = [
            ['min_quantity' => '', 'type' => 'percentage', 'value' => '']
        ];
    }

    public function createPromotion()
    {
        $this->validate();
        
        $rules = $this->buildRulesForType();
        
        Promotion::create([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'rules' => $rules,
            'minimum_amount' => $this->minimumAmount ?: null,
            'status' => $this->status,
            'starts_at' => $this->startsAt ?: null,
            'ends_at' => $this->endsAt ?: null,
            'priority' => $this->priority,
            'exclusive' => $this->exclusive,
            'created_by' => auth()->id(),
        ]);

        $this->resetForm();
        $this->showModal = false;
        session()->flash('message', 'Promotion created successfully!');
    }

    public function editPromotion(Promotion $promotion)
    {
        $this->editingPromotion = $promotion;
        $this->title = $promotion->title;
        $this->description = $promotion->description;
        $this->type = $promotion->type;
        $this->minimumAmount = $promotion->minimum_amount;
        $this->status = $promotion->status;
        $this->startsAt = $promotion->starts_at?->format('Y-m-d\TH:i');
        $this->endsAt = $promotion->ends_at?->format('Y-m-d\TH:i');
        $this->priority = $promotion->priority;
        $this->exclusive = $promotion->exclusive;
        
        // Load type-specific rules
        $this->loadRulesFromPromotion($promotion);
        
        $this->editingModal = true;
        $this->showModal = true;
    }

    private function loadRulesFromPromotion($promotion)
    {
        $rules = $promotion->rules;
        
        switch($promotion->type) {
            case 'buy_x_get_y':
                $this->buyQuantity = $rules['buy_quantity'] ?? '';
                $this->getQuantity = $rules['get_quantity'] ?? '';
                $this->itemPrice = $rules['item_price'] ?? '';
                break;
            case 'buy_x_get_percentage':
                $this->discountPercentage = $rules['discount_percentage'] ?? '';
                break;
            case 'bulk_discount':
                $this->bulkDiscounts = $rules['bulk_discounts'] ?? [];
                break;
        }
    }

    private function buildRulesForType()
    {
        switch($this->type) {
            case 'buy_x_get_y':
                return [
                    'buy_quantity' => (int)$this->buyQuantity,
                    'get_quantity' => (int)$this->getQuantity,
                    'item_price' => (float)$this->itemPrice,
                ];
            case 'buy_x_get_percentage':
                return [
                    'discount_percentage' => (float)$this->discountPercentage,
                ];
            case 'bulk_discount':
                return [
                    'bulk_discounts' => array_map(function($discount) {
                        return [
                            'min_quantity' => (int)$discount['min_quantity'],
                            'type' => $discount['type'],
                            'value' => (float)$discount['value'],
                        ];
                    }, array_filter($this->bulkDiscounts, function($discount) {
                        return !empty($discount['min_quantity']) && !empty($discount['value']);
                    }))
                ];
            default:
                return [];
        }
    }

    public function addBulkDiscount()
    {
        $this->bulkDiscounts[] = ['min_quantity' => '', 'type' => 'percentage', 'value' => ''];
    }

    public function removeBulkDiscount($index)
    {
        unset($this->bulkDiscounts[$index]);
        $this->bulkDiscounts = array_values($this->bulkDiscounts);
    }

    public function updatePromotion()
    {
        $this->validate();

        $rules = $this->buildRulesForType();
        
        $this->editingPromotion->update([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'rules' => $rules,
            'minimum_amount' => $this->minimumAmount ?: null,
            'status' => $this->status,
            'starts_at' => $this->startsAt ?: null,
            'ends_at' => $this->endsAt ?: null,
            'priority' => $this->priority,
            'exclusive' => $this->exclusive,
        ]);

        $this->resetForm();
        $this->showModal = false;
        $this->editingModal = false;
        $this->editingPromotion = null;
        session()->flash('message', 'Promotion updated successfully!');
    }

    public function deletePromotion(Promotion $promotion)
    {
        $promotion->delete();
        $this->confirmingDeletion = null;
        session()->flash('message', 'Promotion deleted successfully!');
    }

    public function toggleStatus(Promotion $promotion)
    {
        $newStatus = $promotion->status === 'active' ? 'inactive' : 'active';
        $promotion->update(['status' => $newStatus]);
        session()->flash('message', "Promotion status updated to {$newStatus}!");
    }

    public function resetForm()
    {
        $this->reset([
            'title', 'description', 'type', 'minimumAmount', 'status', 
            'startsAt', 'endsAt', 'priority', 'exclusive',
            'buyQuantity', 'getQuantity', 'itemPrice', 'discountPercentage'
        ]);
        $this->initializeBulkDiscount();
        $this->resetErrorBag();
    }

    protected function getListeners()
    {
        return [
            '$refresh',
        ];
    }

    public function render()
    {
        $this->dispatch('refresh-feather-icons');
        return view('livewire.admin.promotion-management', [
            'promotions' => Promotion::withTrashed()
                ->with('creator')
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }
}
