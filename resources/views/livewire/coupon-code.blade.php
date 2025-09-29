<div class="coupon-code-section">
    <div class="card">
        <div class="card-header">
            <h6 class="card-title mb-0">Have a Coupon Code?</h6>
        </div>
        <div class="card-body">
            @if ($successMessage)
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ $successMessage }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errorMessage)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ $errorMessage }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($appliedVoucher)
                <!-- Applied Voucher Display -->
                <div class="applied-voucher p-3 bg-success bg-opacity-10 border border-success rounded mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-success mb-1">
                                <i class="fas fa-check-circle me-1"></i>
                                {{ $appliedVoucher->name }}
                            </h6>
                            <small class="text-muted">
                                Code: <code>{{ $appliedVoucher->code }}</code>
                                @if ($appliedVoucher->type === 'percentage')
                                    - {{ $appliedVoucher->value }}% off
                                @else
                                    - RM {{ number_format($appliedVoucher->value, 2) }} off
                                @endif
                            </small>
                            <div class="text-success fw-bold mt-1">
                                You save: RM {{ number_format($discountAmount, 2) }}
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeCoupon">
                            <i class="fas fa-times"></i> Remove
                        </button>
                    </div>
                </div>
            @else
                <!-- Coupon Code Input -->
                <form wire:submit.prevent="applyCoupon">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control @error('couponCode') is-invalid @enderror"
                               wire:model="couponCode" 
                               placeholder="Enter coupon code"
                               style="text-transform: uppercase;">
                        <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="fas fa-ticket-alt me-1"></i>Apply
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin me-1"></i>Applying...
                            </span>
                        </button>
                    </div>
                    @error('couponCode') 
                        <div class="invalid-feedback d-block">{{ $message }}</div> 
                    @enderror
                </form>
                
                <!-- Promotional Banner -->
                <div class="mt-3 text-center">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Valid coupon codes are case-insensitive
                    </small>
                </div>
            @endif
        </div>
    </div>
</div>