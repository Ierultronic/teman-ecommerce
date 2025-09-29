<?php

namespace App\Livewire;

use App\Models\Voucher;
use App\Services\DiscountService;
use Livewire\Component;

class PromoVoucherClaim extends Component
{
    public $couponCode = '';
    public $isValidVoucher = false;
    public $errorMessage = '';
    public $successMessage = '';
    
    public $voucherInfo = null;

    protected $rules = [
        'couponCode' => 'nullable|string|max:50',
    ];

    public function applyCoupon()
    {
        $this->validate();
        
        $this->errorMessage = '';
        $this->successMessage = '';
        
        if (empty($this->couponCode)) {
            $this->errorMessage = 'Please enter a voucher code.';
            return;
        }

        $voucher = Voucher::findByCode($this->couponCode);
        
        if (!$voucher) {
            $this->isValidVoucher = false;
            $this->errorMessage = 'Invalid voucher code.';
            $this->successMessage = '';
            return;
        }

        if (!$voucher->isValid()) {
            $this->isValidVoucher = false;
            $this->errorMessage = 'This voucher is not currently valid.';
            $this->successMessage = '';
            return;
        }

        // Show voucher info for preview (without applying yet)
        $this->voucherInfo = [
            'code' => $voucher->code,
            'name' => $voucher->name,
            'description' => $voucher->description,
            'type' => $voucher->type,
            'value' => $voucher->value,
            'minimum_amount' => $voucher->minimum_amount,
            'maximum_discount' => $voucher->maximum_discount,
            'ends_at' => $voucher->ends_at,
        ];
        
        $this->isValidVoucher = true;
        $this->successMessage = 'Voucher found! It will be applied when you checkout.';
        $this->errorMessage = '';
        
        // Clear the input after successful validation
        $this->couponCode = '';
        
        // Dispatch event to parent to store this for later use
        $this->dispatch('voucher-validated', [
            'voucher' => $voucher,
            'code' => $voucher->code
        ]);
    }

    public function clearVoucher()
    {
        $this->reset(['couponCode', 'isValidVoucher', 'errorMessage', 'successMessage', 'voucherInfo']);
    }

    public function render()
    {
        return view('livewire.promo-voucher-claim');
    }
}
