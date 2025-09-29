<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
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
        'usage_limit_per_user',
        'used_count',
        'applicable_products',
        'applicable_categories',
        'single_use',
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
        'single_use' => 'boolean',
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
     * Check if voucher is currently valid
     */
    public function isValid(): bool
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

        if ($this->hasReachedUsageLimit()) {
            return false;
        }

        return true;
    }

    /**
     * Check if voucher has reached global usage limit
     */
    public function hasReachedUsageLimit(): bool
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }

    /**
     * Check if voucher is applicable to the given amount and user
     */
    public function isApplicable(float $amount, ?int $userId = null): bool
    {
        if (!$this->isValid()) {
            return false;
        }

        if ($this->minimum_amount && $amount < $this->minimum_amount) {
            return false;
        }

        // Check per-user usage limit if user ID provided
        if ($userId && $this->usage_limit_per_user) {
            $userUsageCount = $this->orderDiscounts()
                ->whereHas('order', function ($query) use ($userId) {
                    $query->where('created_by', $userId);
                })
                ->count();

            if ($userUsageCount >= $this->usage_limit_per_user) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate discount amount for given total
     */
    public function calculateDiscountAmount(float $total): float
    {
        if (!$this->isValid()) {
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
     * Increment usage count
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');

        // Check if voucher should be marked as expired
        if ($this->usage_limit && $this->fresh()->used_count >= $this->usage_limit) {
            $this->update(['status' => 'expired']);
        }
    }

    /**
     * Generate a unique voucher code
     */
    public static function generateUniqueCode(int $length = 8): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, $length));
        } while (static::where('code', $code)->exists());

        return $code;
    }

    /**
     * Scope for active vouchers
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

    /**
     * Find voucher by code
     */
    public static function findByCode(string $code): ?self
    {
        return static::active()->where('code', $code)->first();
    }
}
