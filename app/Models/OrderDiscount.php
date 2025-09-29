<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderDiscount extends Model
{
    protected $fillable = [
        'order_id',
        'discountable_type',
        'discountable_id',
        'name',
        'type',
        'value',
        'calculated_amount',
        'applied_code',
        'applied_rules'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'calculated_amount' => 'decimal:2',
        'applied_rules' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function discountable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get discount type name
     */
    public function getDiscountTypeNameAttribute(): string
    {
        return match ($this->type) {
            'percentage' => 'Percentage',
            'fixed' => 'Fixed Amount',
            default => 'Unknown',
        };
    }

    /**
     * Get discountable name
     */
    public function getDiscountableSourceAttribute(): string
    {
        return match ($this->discountable_type) {
            Discount::class => 'System Discount',
            Voucher::class => 'Voucher Code',
            Promotion::class => 'Promotion',
            default => 'Unknown',
        };
    }
}
