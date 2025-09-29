<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address_line_1',
        'customer_address_line_2',
        'customer_city',
        'customer_state',
        'customer_postal_code',
        'customer_country',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'order_notes',
        'same_as_billing',
        'subtotal',
        'total_discount',
        'total_price',
        'status',
        'payment_method',
        'payment_reference',
        'payment_receipt_path',
        'payment_verified_at',
        'payment_verified_by'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total_discount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'same_as_billing' => 'boolean',
        'payment_verified_at' => 'datetime',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderDiscounts(): HasMany
    {
        return $this->hasMany(OrderDiscount::class);
    }

    /**
     * Calculate and update order totals
     */
    public function calculateTotals(): void
    {
        $subtotal = $this->orderItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $totalDiscount = $this->orderDiscounts->sum('calculated_amount');
        
        $this->update([
            'subtotal' => $subtotal,
            'total_discount' => $totalDiscount,
            'total_price' => $subtotal - $totalDiscount
        ]);
    }

    /**
     * Get total discount amount
     */
    public function getTotalDiscountAttribute(): float
    {
        return $this->orderDiscounts->sum('calculated_amount');
    }
}
