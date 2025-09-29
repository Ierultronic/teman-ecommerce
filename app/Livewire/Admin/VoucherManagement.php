<?php

namespace App\Livewire\Admin;

use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;

class VoucherManagement extends Component
{
    use WithPagination;

    // Form properties
    public $name = '';
    public $code = '';
    public $description = '';
    public $type = 'percentage';
    public $value = '';
    public $minimumAmount = '';
    public $maximumDiscount = '';
    public $status = 'active';
    public $startsAt = '';
    public $endsAt = '';
    public $usageLimit = '';
    public $usageLimitPerUser = '';
    public $singleUse = true;
    public $forFirstTimeOnly = false;
    public $applicableProducts = [];
    
    // UI state
    public $showModal = false;
    public $editingModal = false;
    public $editingVoucher = null;
    public $confirmingDeletion = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:vouchers,code',
        'description' => 'nullable|string',
        'type' => 'required|in:percentage,fixed',
        'value' => 'required|numeric|min:0',
        'minimumAmount' => 'nullable|numeric|min:0',
        'maximumDiscount' => 'nullable|numeric|min:0',
        'status' => 'required|in:active,inactive,expired',
        'startsAt' => 'nullable|date',
        'endsAt' => 'nullable|date|after:startsAt',
        'usageLimit' => 'nullable|integer|min:1',
        'usageLimitPerUser' => 'nullable|integer|min:1',
        'singleUse' => 'boolean',
        'forFirstTimeOnly' => 'boolean',
    ];

    public function mount()
    {
        // Don't auto-generate on mount, only when user clicks button
        $this->resetForm();
    }

    public function generateCode()
    {
        $this->code = Voucher::generateUniqueCode();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->editingModal = false;
        $this->showModal = true;
        $this->dispatch('refresh-feather-icons');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->editingModal = false;
        $this->editingVoucher = null;
        $this->resetForm();
        $this->dispatch('refresh-feather-icons');
    }

    public function createVoucher()
    {
        $this->validate();
        
        Voucher::create([
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'minimum_amount' => $this->minimumAmount ?: null,
            'maximum_discount' => $this->maximumDiscount ?: null,
            'status' => $this->status,
            'starts_at' => $this->startsAt ?: null,
            'ends_at' => $this->endsAt ?: null,
            'usage_limit' => $this->usageLimit ?: null,
            'usage_limit_per_user' => $this->usageLimitPerUser ?: null,
            'single_use' => $this->singleUse,
            'for_first_time_only' => $this->forFirstTimeOnly,
            'applicable_products' => $this->applicableProducts,
            'created_by' => auth()->id(),
        ]);

        $this->resetForm();
        $this->showModal = false;
        session()->flash('message', 'Voucher created successfully!');
        $this->dispatch('refresh-feather-icons');
    }

    public function editVoucher(Voucher $voucher)
    {
        $this->editingVoucher = $voucher;
        $this->name = $voucher->name;
        $this->code = $voucher->code;
        $this->description = $voucher->description;
        $this->type = $voucher->type;
        $this->value = $voucher->value;
        $this->minimumAmount = $voucher->minimum_amount;
        $this->maximumDiscount = $voucher->maximum_discount;
        $this->status = $voucher->status;
        $this->startsAt = $voucher->starts_at?->format('Y-m-d\TH:i');
        $this->endsAt = $voucher->ends_at?->format('Y-m-d\TH:i');
        $this->usageLimit = $voucher->usage_limit;
        $this->usageLimitPerUser = $voucher->usage_limit_per_user;
        $this->singleUse = $voucher->single_use;
        $this->forFirstTimeOnly = $voucher->for_first_time_only;
        $this->applicableProducts = $voucher->applicable_products ?? [];
        
        $this->rules['code'] = 'required|string|max:50|unique:vouchers,code,' . $voucher->id;
        $this->editingModal = true;
        $this->showModal = true;
        $this->dispatch('refresh-feather-icons');
    }

    public function updateVoucher()
    {
        $this->rules['code'] = 'required|string|max:50|unique:vouchers,code,' . $this->editingVoucher->id;
        $this->validate();

        $this->editingVoucher->update([
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'type' => $this->type,
            'value' => $this->value,
            'minimum_amount' => $this->minimumAmount ?: null,
            'maximum_discount' => $this->maximumDiscount ?: null,
            'status' => $this->status,
            'starts_at' => $this->startsAt ?: null,
            'ends_at' => $this->endsAt ?: null,
            'usage_limit' => $this->usageLimit ?: null,
            'usage_limit_per_user' => $this->usageLimitPerUser ?: null,
            'single_use' => $this->singleUse,
            'for_first_time_only' => $this->forFirstTimeOnly,
            'applicable_products' => $this->applicableProducts,
        ]);

        $this->resetForm();
        $this->showModal = false;
        $this->editingModal = false;
        $this->editingVoucher = null;
        session()->flash('message', 'Voucher updated successfully!');
        $this->dispatch('refresh-feather-icons');
    }

    public function deleteVoucher(Voucher $voucher)
    {
        $voucher->delete();
        $this->confirmingDeletion = null;
        session()->flash('message', 'Voucher deleted successfully!');
        $this->dispatch('refresh-feather-icons');
    }

    public function toggleStatus(Voucher $voucher)
    {
        $newStatus = $voucher->status === 'active' ? 'inactive' : 'active';
        $voucher->update(['status' => $newStatus]);
        session()->flash('message', "Voucher status updated to {$newStatus}!");
        $this->dispatch('refresh-feather-icons');
    }

    public function resetForm()
    {
        $this->reset([
            'name', 'code', 'description', 'type', 'value',
            'minimumAmount', 'maximumDiscount', 'status', 'startsAt',
            'endsAt', 'usageLimit', 'usageLimitPerUser', 'singleUse',
            'forFirstTimeOnly', 'applicableProducts'
        ]);
        $this->generateCode();
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
        return view('livewire.admin.voucher-management', [
            'vouchers' => Voucher::withTrashed()
                ->with('creator')
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }
}
