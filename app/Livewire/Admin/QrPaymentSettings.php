<?php

namespace App\Livewire\Admin;

use App\Models\WebsiteSettings;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class QrPaymentSettings extends Component
{
    use WithFileUploads;

    public $qr_image;
    public $qr_bank_name;
    public $qr_account_number;
    public $qr_account_holder_name;
    public $current_qr_image_url;
    public $showPreviewModal = false;
    public $showFeedbackModal = false;
    public $feedbackMessage = '';
    public $feedbackType = 'success';

    protected $rules = [
        'qr_image' => 'nullable|image|max:2048',
        'qr_bank_name' => 'nullable|string|max:255',
        'qr_account_number' => 'nullable|string|max:255',
        'qr_account_holder_name' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        $settings = WebsiteSettings::current();
        
        $this->qr_bank_name = $settings->qr_bank_name;
        $this->qr_account_number = $settings->qr_account_number;
        $this->qr_account_holder_name = $settings->qr_account_holder_name;
        $this->current_qr_image_url = $settings->qr_image_url;
    }

    public function showPreview()
    {
        $this->validate();
        $this->showPreviewModal = true;
        
        // Debug: Log to console
        $this->dispatch('debug', message: 'Preview modal should be showing. State: ' . ($this->showPreviewModal ? 'true' : 'false'));
    }

    public function save()
    {
        $settings = WebsiteSettings::current();

        $data = [
            'qr_bank_name' => $this->qr_bank_name,
            'qr_account_number' => $this->qr_account_number,
            'qr_account_holder_name' => $this->qr_account_holder_name,
        ];

        // Handle QR image upload
        if ($this->qr_image) {
            $data['qr_image'] = $this->qr_image;
        }

        $settings->updateSettings($data);

        // Refresh current URL
        $settings->refresh();
        $this->current_qr_image_url = $settings->qr_image_url;

        // Clear file input
        $this->qr_image = null;

        // Close preview modal
        $this->showPreviewModal = false;

        // Show feedback modal
        $this->feedbackMessage = 'QR Payment settings updated successfully! You will be redirected to the dashboard.';
        $this->feedbackType = 'success';
        $this->showFeedbackModal = true;
    }

    public function confirmSave()
    {
        $this->save();
    }

    public function cancelSave()
    {
        $this->showPreviewModal = false;
    }

    public function removeQrImage()
    {
        $settings = WebsiteSettings::current();
        
        if ($settings->qr_image_path && Storage::exists($settings->qr_image_path)) {
            Storage::delete($settings->qr_image_path);
        }

        $settings->update(['qr_image_path' => null]);
        $this->current_qr_image_url = asset('images/qr.jpeg');
        
        // Show feedback modal
        $this->feedbackMessage = 'QR image removed successfully!';
        $this->feedbackType = 'success';
        $this->showFeedbackModal = true;
    }

    public function closeFeedbackModal()
    {
        $this->showFeedbackModal = false;
        $this->feedbackMessage = '';
        $this->feedbackType = 'success';
        
        // Redirect to dashboard
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.admin.qr-payment-settings');
    }
}
