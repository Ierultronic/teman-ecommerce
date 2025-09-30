<?php

namespace App\Services;

use App\Models\Discount;
use App\Models\Voucher;
use App\Models\Promotion;
use App\Models\Order;
use App\Models\OrderDiscount;
use Illuminate\Support\Collection;

class DiscountService
{
    /**
     * Apply voucher code to cart/order
     */
    public function applyVoucher(string $code, array $cartItems, ?int $userId = null): array
    {
        $voucher = Voucher::findByCode($code);
        
        if (!$voucher) {
            return [
                'success' => false,
                'message' => 'Invalid voucher code.',
                'discount_amount' => 0.00
            ];
        }

        $cartTotal = $this->calculateCartTotal($cartItems);

        if (!$voucher->isApplicable($cartTotal, $userId)) {
            return [
                'success' => false,
                'message' => 'This voucher is not applicable to your current order.',
                'discount_amount' => 0.00
            ];
        }

        $discountAmount = $voucher->calculateDiscountAmount($cartTotal);

        return [
            'success' => true,
            'voucher' => $voucher,
            'discount_amount' => $discountAmount,
            'message' => "Voucher applied successfully. Discount: RM" . number_format($discountAmount, 2)
        ];
    }

    /**
     * Get applicable promotions for cart
     */
    public function getApplicablePromotions(array $cartItems): Collection
    {
        $cartTotal = $this->calculateCartTotal($cartItems);
        
        return Promotion::active()
            ->where(function ($query) use ($cartTotal) {
                $query->whereNull('minimum_amount')
                      ->orWhere('minimum_amount', '<=', $cartTotal);
            })
            ->orderBy('priority', 'desc')
            ->get()
            ->map(function ($promotion) use ($cartItems) {
                $discountAmount = $this->calculatePromotionDiscount($promotion, $cartItems);
                
                return (object) [
                    'promotion' => $promotion,
                    'discount_amount' => $discountAmount,
                    'is_applicable' => $discountAmount > 0
                ];
            })
            ->filter(fn($item) => $item->is_applicable);
    }

    /**
     * Get applicable discounts for cart
     */
    public function getApplicableDiscounts(array $cartItems, ?int $userId = null): Collection
    {
        $cartTotal = $this->calculateCartTotal($cartItems);
        
        return Discount::active()
            ->where(function ($query) use ($cartTotal) {
                $query->whereNull('minimum_amount')
                      ->orWhere('minimum_amount', '<=', $cartTotal);
            })
            ->get()
            ->map(function ($discount) use ($cartTotal, $userId) {
                // Check first-time customer status based on email (for guest checkouts)
                if ($discount->for_first_time_only) {
                    // For now, skip first-time customer checks in guest checkout
                    // In production, you'd implement proper customer email checking
                    $customerEmail = null;
                    if ($customerEmail) {
                        $hasPreviousOrders = Order::where('customer_email', $customerEmail)->exists();
                        if ($hasPreviousOrders) {
                            return null;
                        }
                    }
                }

                $discountAmount = $discount->calculateDiscountAmount($cartTotal);
                
                return (object) [
                    'discount' => $discount,
                    'discount_amount' => $discountAmount,
                    'is_applicable' => $discountAmount > 0
                ];
            })
            ->filter(fn($item) => $item && $item->is_applicable);
    }

    /**
     * Calculate automatic discounts for cart
     */
    public function calculateAutomaticDiscounts(array $cartItems): Collection
    {
        $allDiscounts = collect();

        // Get applicable discounts
        $applicableDiscounts = $this->getApplicableDiscounts($cartItems);
        foreach ($applicableDiscounts as $discountData) {
            $allDiscounts->push($discountData);
        }

        // Get applicable promotions (non-exclusive only for automatic calculation)
        $applicablePromotions = Promotion::active()
            ->where('exclusive', false)
            ->orderBy('priority', 'desc')
            ->get();
            
        foreach ($applicablePromotions as $promotion) {
            $discountAmount = $this->calculatePromotionDiscount($promotion, $cartItems, $allDiscounts);
            if ($discountAmount > 0) {
                $allDiscounts->push((object) [
                    'promotion' => $promotion,
                    'discount_amount' => $discountAmount,
                    'is_applicable' => true
                ]);
            }
        }

        // Sort by discount amount descending and return top discounts
        return $allDiscounts
            ->sortByDesc('discount_amount')
            ->values();
    }

    /**
     * Apply discounts to order
     */
    public function applyDiscountsToOrder(Order $order, array $discountData): array
    {
        $appliedDiscounts = [];
        $totalDiscount = 0.00;

        foreach ($discountData as $item) {
            $discountable = null;
            $discountType = '';
            $discountValue = 0.00;
            $discountName = '';

            if (isset($item->voucher)) {
                $discountable = $item->voucher;
                $discountType = $item->voucher->type;
                $discountValue = $item->voucher->value;
                $discountName = $item->voucher->name;
                $discountable->incrementUsage();
            } elseif (isset($item->discount)) {
                $discountable = $item->discount;
                $discountType = $item->discount->type;
                $discountValue = $item->discount->value;
                $discountName = $item->discount->name;
                $discountable->incrementUsage();
            } elseif (isset($item->promotion)) {
                $discountable = $item->promotion;
                $discountType = 'fixed'; // Promotions are always handled as fixed amounts
                $discountValue = 0.00; // Will be the calculated amount
                $discountName = $item->promotion->title;
                // Note: Promotions don't have usage tracking, so no incrementUsage() call
            }

            $discountAmount = $item->discount_amount;

            if ($discountAmount > 0) {
                $orderDiscount = OrderDiscount::create([
                    'order_id' => $order->id,
                    'discountable_type' => get_class($discountable),
                    'discountable_id' => $discountable->id,
                    'name' => $discountName,
                    'type' => $discountType,
                    'value' => $discountValue,
                    'calculated_amount' => $discountAmount,
                    'applied_code' => $discountable instanceof Voucher ? $discountable->code : null,
                    'applied_rules' => $this->getAppliedRules($discountable, $discountAmount)
                ]);

                $appliedDiscounts[] = $orderDiscount;
                $totalDiscount += $discountAmount;
            }
        }

        // Update order totals
        $order->calculateTotals();

        return [
            'applied_discounts' => $appliedDiscounts,
            'total_discount' => $totalDiscount
        ];
    }

    /**
     * Calculate promotion discount
     */
    private function calculatePromotionDiscount(Promotion $promotion, array $cartItems, Collection $existingDiscounts = null): float
    {
        $cartTotal = $this->calculateCartTotal($cartItems);
        
        if (!$promotion->isApplicableToCart($cartTotal)) {
            return 0.00;
        }

        // For exclusive promotions, check if there are other applied discounts
        if ($promotion->exclusive && $existingDiscounts && $existingDiscounts->isNotEmpty()) {
            return 0.00;
        }

        $discountAmount = 0.00;

        switch ($promotion->type) {
            case 'buy_x_get_y':
                $discountAmount = $this->calculateBuyXGetYDiscount($promotion, $cartItems);
                break;
            case 'buy_x_get_percentage':
                $discountAmount = $this->calculateBuyXGetPercentageDiscount($promotion, $cartItems);
                break;
            case 'bulk_discount':
                $discountAmount = $this->calculateBulkDiscount($promotion, $cartItems);
                break;
            case 'category_discount':
                $discountAmount = $this->calculateCategoryDiscount($promotion, $cartItems);
                break;
        }

        return min($discountAmount, $cartTotal);
    }

    /**
     * Calculate buy X get Y discount
     */
    private function calculateBuyXGetYDiscount(Promotion $promotion, array $cartItems): float
    {
        $rules = $promotion->rules;
        $applicableItems = $this->filterApplicableItems($cartItems, $promotion);
        
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
    private function calculateBuyXGetPercentageDiscount(Promotion $promotion, array $cartItems): float
    {
        $rules = $promotion->rules;
        $applicableItems = $this->filterApplicableItems($cartItems, $promotion);
        $totalAmount = array_sum(array_column($applicableItems, 'total'));
        
        $discountPercentage = $rules['discount_percentage'] ?? 0;
        
        return ($totalAmount * $discountPercentage) / 100;
    }

    /**
     * Calculate bulk discount based on quantity
     */
    private function calculateBulkDiscount(Promotion $promotion, array $cartItems): float
    {
        $rules = $promotion->rules;
        $applicableItems = $this->filterApplicableItems($cartItems, $promotion);
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
    private function calculateCategoryDiscount(Promotion $promotion, array $cartItems): float
    {
        $rules = $promotion->rules;
        $discountPercentage = $rules['discount_percentage'] ?? 0;
        $applicableItems = $this->filterApplicableItems($cartItems, $promotion);
        
        $totalAmount = array_sum(array_column($applicableItems, 'total'));
        
        return ($totalAmount * $discountPercentage) / 100;
    }

    /**
     * Filter items based on promotion targets
     */
    private function filterApplicableItems(array $items, $promotion): array
    {
        if (empty($promotion->target_products) && empty($promotion->target_categories)) {
            return $items;
        }

        return array_filter($items, function ($item) use ($promotion) {
            if (!empty($promotion->target_products) && in_array($item['product_id'], $promotion->target_products)) {
                return true;
            }
            
            if (!empty($promotion->target_categories) && in_array($item['category_id'], $promotion->target_categories)) {
                return true;
            }
            
            return false;
        });
    }

    /**
     * Calculate cart total
     */
    private function calculateCartTotal(array $cartItems): float
    {
        return array_sum(array_column($cartItems, 'total'));
    }

    /**
     * Get applied rules for tracking
     */
    private function getAppliedRules($discountable, float $amount): array
    {
        $rules = [];
        
        if ($discountable instanceof Voucher || $discountable instanceof Discount) {
            $rules = [
                'type' => $discountable->type,
                'value' => $discountable->value,
                'minimum_amount' => $discountable->minimum_amount,
                'maximum_discount' => $discountable->maximum_discount,
            ];
        } elseif ($discountable instanceof Promotion) {
            $rules = array_merge([
                'promotion_type' => $discountable->type,
                'calculated_amount' => $amount,
            ], $discountable->rules);
        }

        return $rules;
    }

    /**
     * Get user email for checking first-time customer status
     */
    private function getUserEmail(int $userId): string 
    {
        // For guest checkouts, we'll check based on email instead
        // This method should ideally fetch from User model, but since we're doing guest checkouts
        // we'll return empty string to skip first-time customer checks for now
        return '';
    }

    /**
     * Get customer email from user ID or session
     */
    private function getCustomerEmailFromUserId(?int $userId): ?string
    {
        // For guest checkouts (userId is null or 0), we'll get email from session/form data
        // For now, we'll skip this check since it's complex to access session data here
        // In a full implementation, you'd pass the email as a parameter
        return null;
    }
}
