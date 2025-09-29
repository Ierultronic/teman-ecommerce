<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
     * Check if promotion is applicable to the given cart items
     */
    public function isApplicableToCart(ShoppingCart $cart): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        $cartTotal = $cart->getTotal();
        
        if ($this->minimum_amount && $cartTotal < $this->minimum_amount) {
            return false;
        }

        return true;
    }

    /**
     * Calculate promotion discount for cart items
     */
    public function calculateDiscountForCart(ShoppingCart $cart): float
    {
        if (!$this->isApplicableToCart($cart)) {
            return 0.00;
        }

        $items = $cart->getItems();
        $discountAmount = 0.00;

        switch ($this->type) {
            case 'buy_x_get_y':
                $discountAmount = $this->calculateBuyXGetYDiscount($items);
                break;
            case 'buy_x_get_percentage':
                $discountAmount = $this->calculateBuyXGetPercentageDiscount($items);
                break;
            case 'bulk_discount':
                $discountAmount = $this->calculateBulkDiscount($items);
                break;
            case 'category_discount':
                $discountAmount = $this->calculateCategoryDiscount($items);
                break;
        }

        return min($discountAmount, $cart->getTotal());
    }

    /**
     * Calculate buy X get Y discount
     */
    private function calculateBuyXGetYDiscount(array $items): float
    {
        $rules = $this->rules;
        $applicableItems = $this->filterApplicableItems($items);
        
        $totalQuantity = array_sum(array_column($applicableItems, 'quantity'));
        $buyQuantity = $rules['buy_quantity'] ?? 0;
        $getQuantity = $rules['get_quantity'] ?? 0;
        $itemPrice = $rules['item_price'] ?? 0;

        if ($buyQuantity > 0 && $getQuantity > 0) {
            $eligibleDiscounts = floor($totalQuantity / $buyQuantity);
            return $eligibleDiscounts * $getQuantity * $itemPrice;
        }

        return 0.00;
    }

    /**
     * Calculate buy X get percentage discount
     */
    private function calculateBuyXGetPercentageDiscount(array $items): float
    {
        $rules = $this->rules;
        $applicableItems = $this->filterApplicableItems($items);
        $totalAmount = array_sum(array_column($applicableItems, 'total'));
        
        $discountPercentage = $rules['discount_percentage'] ?? 0;
        
        return ($totalAmount * $discountPercentage) / 100;
    }

    /**
     * Calculate bulk discount based on quantity
     */
    private function calculateBulkDiscount(array $items): float
    {
        $rules = $this->rules;
        $applicableItems = $this->filterApplicableItems($items);
        $totalQuantity = array_sum(array_column($applicableItems, 'quantity'));
        
        $bulkDiscounts = $rules['bulk_discounts'] ?? [];
        
        foreach ($bulkDiscounts as $discount) {
            if ($totalQuantity >= $discount['min_quantity']) {
                $totalAmount = array_sum(array_column($applicableItems, 'total'));
                if ($discount['type'] === 'percentage') {
                    return ($totalAmount * $discount['value']) / 100;
                } else {
                    return $discount['value'];
                }
            }
        }

        return 0.00;
    }

    /**
     * Calculate category discount
     */
    private function calculateCategoryDiscount(array $items): float
    {
        $rules = $this->rules;
        $discountPercentage = $rules['discount_percentage'] ?? 0;
        $applicableItems = $this->filterApplicableItems($items);
        
        $totalAmount = array_sum(array_column($applicableItems, 'total'));
        
        return ($totalAmount * $discountPercentage) / 100;
    }

    /**
     * Filter items based on promotion targets
     */
    private function filterApplicableItems(array $items): array
    {
        if (empty($this->target_products) && empty($this->target_categories)) {
            return $items;
        }

        return array_filter($items, function ($item) {
            if (!empty($this->target_products) && in_array($item['product_id'], $this->target_products)) {
                return true;
            }
            
            if (!empty($this->target_categories) && in_array($item['category_id'], $this->target_categories)) {
                return true;
            }
            
            return false;
        });
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
}
