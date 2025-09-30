<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WebsiteSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'logo_path',
        'favicon_path',
        'description',
        'contact_email',
        'contact_phone',
        'address',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
    ];

    /**
     * Get the current website settings (singleton pattern)
     */
    public static function current()
    {
        return static::first() ?? static::create([
            'shop_name' => 'TEMAN',
            'logo_path' => 'images/logo.png',
            'favicon_path' => 'images/logo.png',
            'description' => 'Your trusted e-commerce platform',
        ]);
    }

    /**
     * Get the logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo_path && Storage::exists($this->logo_path)) {
            return Storage::url($this->logo_path);
        }
        
        return asset('images/logo.png');
    }

    /**
     * Get the favicon URL
     */
    public function getFaviconUrlAttribute()
    {
        if ($this->favicon_path && Storage::exists($this->favicon_path)) {
            return Storage::url($this->favicon_path);
        }
        
        return asset('images/logo.png');
    }

    /**
     * Update settings
     */
    public function updateSettings(array $data)
    {
        // Handle file uploads
        if (isset($data['logo'])) {
            $data['logo_path'] = $this->handleFileUpload($data['logo'], 'logos');
            unset($data['logo']);
        }

        if (isset($data['favicon'])) {
            $data['favicon_path'] = $this->handleFileUpload($data['favicon'], 'favicons');
            unset($data['favicon']);
        }

        return $this->update($data);
    }

    /**
     * Handle file upload
     */
    private function handleFileUpload($file, $directory)
    {
        if (!$file) {
            return null;
        }

        // Delete old file if exists
        if ($this->logo_path && Storage::exists($this->logo_path)) {
            Storage::delete($this->logo_path);
        }

        // Store new file
        $path = $file->store($directory, 'public');
        return $path;
    }
}
