<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Promotion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'banner_image',
        'type',
        'rules',
        'minimum_amount',
        'status',
        'starts_at',
        'ends_at',
        'target_products',
        'target_categories',
        'priority',
        'exclusive',
        'created_by'
    ];

    protected $casts = [
        'rules' => 'array',
        'minimum_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'target_products' => 'array',
        'target_categories' => 'array',
        'exclusive' => 'boolean',
        'priority' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orderDiscounts(): MorphMany
    {
        return $this->morphMany(OrderDiscount::class, 'discountable');
    }

    /**
     * Check if promotion is currently active
     */
    public function isActive(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        $now = now();
        
        if ($this->starts_at && $now->lt($this->starts_at)) {
            return false;
        }

        if ($this->ends_at && $now->gt($this->ends_at)) {
            return false;
        }

        return true;
    }




    /**
     * Scope for active promotions ordered by priority
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('ends_at')
                          ->orWhere('ends_at', '>=', now());
                    })
                    ->orderBy('priority', 'desc');
    }

    /**
     * Get the banner image URL
     */
    public function bannerImageUrl(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                if (!$attributes['banner_image']) {
                    return null;
                }
                
                // Check if it's already a full URL
                if (filter_var($attributes['banner_image'], FILTER_VALIDATE_URL)) {
                    return $attributes['banner_image'];
                }
                
                return asset('storage/' . $attributes['banner_image']);
            }
        );
    }

    /**
     * Scope for banner-enabled promotions (for displaying as ads)
     */
    public function scopeForBanners($query)
    {
        return $query->whereNotNull('banner_image')
                    ->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', now());
                    })
                    ->where(function ($q) {
                        $q->whereNull('ends_at')
                          ->orWhere('ends_at', '>=', now());
                    });
    }
}
