<?php

namespace App\Livewire;

use App\Services\DiscountService;
use Livewire\Component;

class CouponCode extends Component
{
    public $couponCode = '';
    public $appliedVoucher = null;
    public $discountAmount = 0.00;
    public $errorMessage = '';
    public $successMessage = '';
    public $cartItems = [];

    protected $rules = [
        'couponCode' => 'required|string|min:3'
    ];

    public function mount($cartItems = [])
    {
        $this->cartItems = $cartItems;
    }

    public function applyCoupon(DiscountService $discountService)
    {
        $this->validate();
        $this->resetMessages();

        $result = $discountService->applyVoucher($this->couponCode, $this->cartItems);

        if ($result['success']) {
            $this->appliedVoucher = $result['voucher'];
            $this->discountAmount = $result['discount_amount'];
            $this->successMessage = $result['message'];
            
            $this->emit('voucherApplied', [
                'voucher' => $result['voucher'],
                'discountAmount' => $result['discount_amount']
            ]);
        } else {
            $this->errorMessage = $result['message'];
            $this->appliedVoucher = null;
            $this->discountAmount = 0.00;
        }

        $this->couponCode = '';
    }

    public function removeCoupon()
    {
        $this->couponCode = '';
        $this->appliedVoucher = null;
        $this->discountAmount = 0.00;
        $this->resetMessages();

        $this->emit('voucherRemoved');
    }

    private function resetMessages()
    {
        $this->errorMessage = '';
        $this->successMessage = '';
    }

    public function render()
    {
        return view('livewire.coupon-code');
    }
}
