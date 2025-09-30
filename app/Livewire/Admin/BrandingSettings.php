<?php

namespace App\Livewire\Admin;

use App\Models\WebsiteSettings;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class BrandingSettings extends Component
{
    use WithFileUploads;

    public $shop_name;
    public $description;
    public $contact_email;
    public $contact_phone;
    public $address;
    public $logo;
    public $favicon;
    public $current_logo_url;
    public $current_favicon_url;
    public $social_links = [
        'facebook' => '',
        'instagram' => '',
        'twitter' => '',
        'youtube' => '',
    ];

    protected $rules = [
        'shop_name' => 'required|string|max:255',
        'description' => 'nullable|string|max:500',
        'contact_email' => 'nullable|email|max:255',
        'contact_phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'logo' => 'nullable|image|max:2048',
        'favicon' => 'nullable|image|max:1024',
        'social_links.facebook' => 'nullable|url',
        'social_links.instagram' => 'nullable|url',
        'social_links.twitter' => 'nullable|url',
        'social_links.youtube' => 'nullable|url',
    ];

    public function mount()
    {
        $settings = WebsiteSettings::current();
        
        $this->shop_name = $settings->shop_name;
        $this->description = $settings->description;
        $this->contact_email = $settings->contact_email;
        $this->contact_phone = $settings->contact_phone;
        $this->address = $settings->address;
        $this->current_logo_url = $settings->logo_url;
        $this->current_favicon_url = $settings->favicon_url;
        $this->social_links = $settings->social_links ?? [
            'facebook' => '',
            'instagram' => '',
            'twitter' => '',
            'youtube' => '',
        ];
    }

    public function save()
    {
        $this->validate();

        $settings = WebsiteSettings::current();

        $data = [
            'shop_name' => $this->shop_name,
            'description' => $this->description,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'address' => $this->address,
            'social_links' => $this->social_links,
        ];

        // Handle file uploads
        if ($this->logo) {
            $data['logo'] = $this->logo;
        }

        if ($this->favicon) {
            $data['favicon'] = $this->favicon;
        }

        $settings->updateSettings($data);

        // Refresh current URLs
        $settings->refresh();
        $this->current_logo_url = $settings->logo_url;
        $this->current_favicon_url = $settings->favicon_url;

        // Clear file inputs
        $this->logo = null;
        $this->favicon = null;

        session()->flash('success', 'Branding settings updated successfully!');
    }

    public function removeLogo()
    {
        $settings = WebsiteSettings::current();
        
        if ($settings->logo_path && Storage::exists($settings->logo_path)) {
            Storage::delete($settings->logo_path);
        }

        $settings->update(['logo_path' => null]);
        $this->current_logo_url = asset('images/logo.png');
        
        session()->flash('success', 'Logo removed successfully!');
    }

    public function removeFavicon()
    {
        $settings = WebsiteSettings::current();
        
        if ($settings->favicon_path && Storage::exists($settings->favicon_path)) {
            Storage::delete($settings->favicon_path);
        }

        $settings->update(['favicon_path' => null]);
        $this->current_favicon_url = asset('images/logo.png');
        
        session()->flash('success', 'Favicon removed successfully!');
    }

    public function render()
    {
        return view('livewire.admin.branding-settings');
    }
}
