<?php

namespace App\Livewire;

use App\Models\Voucher;
use App\Services\DiscountService;
use Livewire\Component;

class CouponCodeClaim extends Component
{
    public $couponCode = '';
    public $appliedVoucher = null;
    public $isValidVoucher = false;
    public $discountAmount = 0.00;
    public $errorMessage = '';
    public $successMessage = '';
    
    public $cartTotal = 0.00;
    public $cartItems = [];

    protected $rules = [
        'couponCode' => 'required|string|max:50',
    ];

    public function mount($cartItems = [], $cartTotal = 0.00)
    {
        // Convert cart items to the format expected by DiscountService
        $this->cartItems = [];
        foreach ($cartItems as $cartKey => $item) {
            $this->cartItems[] = [
                'product_id' => $item['product_id'],
                'variant_id' => $item['variant_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ];
        }
        $this->cartTotal = $cartTotal;
    }

    public function applyCoupon()
    {
        $this->validate();
        
        $this->errorMessage = '';
        $this->successMessage = '';
        
        if (empty($this->couponCode)) {
            $this->errorMessage = 'Please enter a voucher code.';
            return;
        }

        $discountService = new DiscountService();
        $result = $discountService->applyVoucher(
            $this->couponCode, 
            $this->cartItems
        );

        if ($result['success']) {
            $this->appliedVoucher = $result['voucher'];
            $this->discountAmount = $result['discount_amount'];
            $this->isValidVoucher = true;
            $this->successMessage = $result['message'];
            
            // Dispatch event to parent component about successful voucher application
            $this->dispatch('voucher-applied', [
                'voucher' => $this->appliedVoucher,
                'discount_amount' => $this->discountAmount,
                'voucher_code' => $this->appliedVoucher->code
            ]);
        } else {
            $this->isValidVoucher = false;
            $this->errorMessage = $result['message'];
            $this->discountAmount = 0.00;
            
            // Dispatch event to parent component about failed voucher application
            $this->dispatch('voucher-failed', [
                'message' => $result['message']
            ]);
        }
        
        // Clear the coupon input after applying
        $this->couponCode = '';
    }

    public function removeVoucher()
    {
        $this->reset(['appliedVoucher', 'isValidVoucher', 'discountAmount', 'errorMessage', 'successMessage']);
        
        // Dispatch event to parent component about voucher removal
        $this->dispatch('voucher-removed');
    }

    public function updatedCartItems($value)
    {
        // If cart items change, recalculate discount if voucher is applied
        if ($this->appliedVoucher && $this->isValidVoucher) {
            $discountService = new DiscountService();
            $cartTotal = collect($value)->sum(function($item) {
                return $item['price'] * $item['quantity'];
            });
            
            $this->cartTotal = $cartTotal;
            
            // Recalculate discount with new total
            $result = $discountService->applyVoucher(
                $this->appliedVoucher->code, 
                $value
            );
            
            if ($result['success']) {
                $this->discountAmount = $result['discount_amount'];
                
                // Dispatch updated voucher info
                $this->dispatch('voucher-updated', [
                    'discount_amount' => $this->discountAmount
                ]);
            } else {
                $this->isValidVoucher = false;
                $this->errorMessage = $result['message'];
                $this->discountAmount = 0.00;
                
                // Dispatch voucher removal
                $this->dispatch('voucher-removed');
            }
        }
    }

    public function render()
    {
        return view('livewire.coupon-code-claim');
    }
}
