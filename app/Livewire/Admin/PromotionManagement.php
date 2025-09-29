<?php

namespace App\Livewire\Admin;

use App\Models\Promotion;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class PromotionManagement extends Component
{
    use WithPagination, WithFileUploads;

    // Form properties
    public $title = '';
    public $description = '';
    public $type = '';
    public $bannerImage;
    public $status = 'active';
    public $startsAt = '';
    public $endsAt = '';
    public $priority = 0;
    public $exclusive = false;
    
    // UI state
    public $showModal = false;
    public $editingModal = false;
    public $editingPromotion = null;
    public $confirmingDeletion = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|string|max:100',
        'bannerImage' => 'nullable|image|max:2048',
        'status' => 'required|in:active,inactive',
        'startsAt' => 'nullable|date',
        'endsAt' => 'nullable|date|after:startsAt',
        'priority' => 'integer|min:0',
        'exclusive' => 'boolean',
    ];

    public function mount()
    {
        // Initialize any default values if needed
    }

    public function createPromotion()
    {
        $this->validate();
        
        $bannerImagePath = null;
        if ($this->bannerImage) {
            $bannerImagePath = $this->bannerImage->store('promotions', 'public');
        }
        
        Promotion::create([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'banner_image' => $bannerImagePath,
            'rules' => [], // Empty rules for informational banners
            'minimum_amount' => null, // Remove price-related fields
            'status' => $this->status,
            'starts_at' => $this->startsAt ?: null,
            'ends_at' => $this->endsAt ?: null,
            'priority' => $this->priority,
            'exclusive' => $this->exclusive,
            'created_by' => Auth::id(),
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
        $this->bannerImage = null; // Reset file upload
        $this->status = $promotion->status;
        $this->startsAt = $promotion->starts_at?->format('Y-m-d\TH:i');
        $this->endsAt = $promotion->ends_at?->format('Y-m-d\TH:i');
        $this->priority = $promotion->priority;
        $this->exclusive = $promotion->exclusive;
        
        $this->editingModal = true;
        $this->showModal = true;
    }


    public function updatePromotion()
    {
        $this->validate();

        $updateData = [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'rules' => [], // Empty rules for informational banners
            'minimum_amount' => null, // Remove price-related fields
            'status' => $this->status,
            'starts_at' => $this->startsAt ?: null,
            'ends_at' => $this->endsAt ?: null,
            'priority' => $this->priority,
            'exclusive' => $this->exclusive,
        ];
        
        // Handle banner image upload
        if ($this->bannerImage) {
            $updateData['banner_image'] = $this->bannerImage->store('promotions', 'public');
        }
        
        $this->editingPromotion->update($updateData);

        $this->resetForm();
        $this->showModal = false;
        $this->editingModal = false;
        $this->editingPromotion = null;
        session()->flash('message', 'Promotion updated successfully!');
    }

    public function confirmDelete($promotionId)
    {
        $this->confirmingDeletion = $promotionId;
    }

    public function deletePromotion($promotionId)
    {
        $promotion = Promotion::find($promotionId);
        if ($promotion) {
            $promotion->delete();
            session()->flash('message', 'Promotion deleted successfully!');
        }
        $this->confirmingDeletion = null;
    }

    public function restorePromotion($promotionId)
    {
        $promotion = Promotion::withTrashed()->find($promotionId);
        if ($promotion && $promotion->trashed()) {
            $promotion->restore();
            session()->flash('message', 'Promotion restored successfully!');
        }
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
            'title', 'description', 'type', 'bannerImage', 'status', 
            'startsAt', 'endsAt', 'priority', 'exclusive'
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
        return view('livewire.admin.promotion-management', [
            'promotions' => Promotion::withTrashed()
                ->with('creator')
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }
}
