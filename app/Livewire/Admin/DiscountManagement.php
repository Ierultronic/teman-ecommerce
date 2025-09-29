<?php

namespace App\Livewire\Admin;

use App\Models\Discount;
use Livewire\Component;
use Livewire\WithPagination;

class DiscountManagement extends Component
{
    use WithPagination;

    // Form properties
    public $name = '';
    public $description = '';
    public $type = 'percentage';
    public $value = '';
    public $minimumAmount = '';
    public $maximumDiscount = '';
    public $status = 'active';
    public $startsAt = '';
    public $endsAt = '';
    public $usageLimit = '';
    public $forFirstTimeOnly = false;
    
    // UI state
    public $showModal = false;
    public $editingModal = false;
    public $editingDiscount = null;
    public $confirmingDeletion = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:percentage,fixed',
        'value' => 'required|numeric|min:0',
        'minimumAmount' => 'nullable|numeric|min:0',
        'maximumDiscount' => 'nullable|numeric|min:0',
        'status' => 'required|in:active,inactive',
        'startsAt' => 'nullable|date',
        'endsAt' => 'nullable|date|after:startsAt',
        'usageLimit' => 'nullable|integer|min:1',
        'forFirstTimeOnly' => 'boolean',
    ];

    public function createDiscount()
    {
        $this->validate();
        
        Discount::create([
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'minimum_amount' => $this->minimumAmount ?: null,
            'maximum_discount' => $this->maximumDiscount ?: null,
            'status' => $this->status,
            'starts_at' => $this->startsAt ?: null,
            'ends_at' => $this->endsAt ?: null,
            'usage_limit' => $this->usageLimit ?: null,
            'for_first_time_only' => $this->forFirstTimeOnly,
            'created_by' => auth()->id(),
        ]);

        $this->resetForm();
        $this->showModal = false;
        session()->flash('message', 'Discount created successfully!');
    }

    public function editDiscount(Discount $discount)
    {
        $this->editingDiscount = $discount;
        $this->name = $discount->name;
        $this->description = $discount->description;
        $this->type = $discount->type;
        $this->value = $discount->value;
        $this->minimumAmount = $discount->minimum_amount;
        $this->maximumDiscount = $discount->maximum_discount;
        $this->status = $discount->status;
        $this->startsAt = $discount->starts_at?->format('Y-m-d\TH:i');
        $this->endsAt = $discount->ends_at?->format('Y-m-d\TH:i');
        $this->usageLimit = $discount->usage_limit;
        $this->forFirstTimeOnly = $discount->for_first_time_only;
        
        $this->editingModal = true;
        $this->showModal = true;
    }

    public function updateDiscount()
    {
        $this->validate();

        $this->editingDiscount->update([
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'minimum_amount' => $this->minimumAmount ?: null,
            'maximum_discount' => $this->maximumDiscount ?: null,
            'status' => $this->status,
            'starts_at' => $this->startsAt ?: null,
            'ends_at' => $this->endsAt ?: null,
            'usage_limit' => $this->usageLimit ?: null,
            'for_first_time_only' => $this->forFirstTimeOnly,
        ]);

        $this->resetForm();
        $this->showModal = false;
        $this->editingModal = false;
        $this->editingDiscount = null;
        session()->flash('message', 'Discount updated successfully!');
    }

    public function deleteDiscount(Discount $discount)
    {
        $discount->delete();
        $this->confirmingDeletion = null;
        session()->flash('message', 'Discount deleted successfully!');
    }

    public function toggleStatus(Discount $discount)
    {
        $newStatus = $discount->status === 'active' ? 'inactive' : 'active';
        $discount->update(['status' => $newStatus]);
        session()->flash('message', "Discount status updated to {$newStatus}!");
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'description', 'type', 'value',
            'minimumAmount', 'maximumDiscount', 'status', 'startsAt',
            'endsAt', 'usageLimit', 'forFirstTimeOnly'
        ]);
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
        return view('livewire.admin.discount-management', [
            'discounts' => Discount::withTrashed()
                ->with('creator')
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }
}
