<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Discount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'status',
        'starts_at',
        'ends_at',
        'usage_limit',
        'used_count',
        'applicable_products',
        'applicable_categories',
        'for_first_time_only',
        'created_by'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'applicable_products' => 'array',
        'applicable_categories' => 'array',
        'for_first_time_only' => 'boolean',
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
     * Check if discount is currently active and valid
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
     * Check if discount has reached usage limit
     */
    public function hasReachedUsageLimit(): bool
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }

    /**
     * Check if discount is applicable to the given amount
     */
    public function isApplicableToAmount(float $amount): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if ($this->minimum_amount && $amount < $this->minimum_amount) {
            return false;
        }

        return !$this->hasReachedUsageLimit();
    }

    /**
     * Calculate discount amount for given total
     */
    public function calculateDiscountAmount(float $total): float
    {
        if (!$this->isApplicableToAmount($total)) {
            return 0.00;
        }

        $discountAmount = 0.00;

        if ($this->type === 'percentage') {
            $discountAmount = ($total * $this->value) / 100;
        } else {
            $discountAmount = $this->value;
        }

        // Apply maximum discount limit
        if ($this->maximum_discount && $discountAmount > $this->maximum_discount) {
            $discountAmount = $this->maximum_discount;
        }

        // Ensure discount doesn't exceed total
        return min($discountAmount, $total);
    }

    /**
     * Scope for active discounts
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
                    });
    }
}
