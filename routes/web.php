<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

// Store routes (public)
Route::get('/', [StoreController::class, 'index'])->name('store.index');
Route::get('/vouchers', App\Livewire\CustomerVouchers::class)->name('vouchers.index');

// Admin routes (protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Custom product routes (must come before resource route)
    Route::patch('products/{id}/restore', [App\Http\Controllers\Admin\ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [App\Http\Controllers\Admin\ProductController::class, 'forceDelete'])->name('products.force-delete');
    
    // Resource routes
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store']);
    Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/verify-payment', [App\Http\Controllers\Admin\OrderController::class, 'verifyPayment'])->name('orders.verify-payment');
    Route::get('orders/{order}/e-invoice', [App\Http\Controllers\Admin\OrderController::class, 'generateEInvoice'])->name('orders.e-invoice');
    
    // Discount management routes
    Route::get('vouchers', function () {
        return view('admin.vouchers');
    })->name('vouchers.index');
    Route::get('discounts', function () {
        return view('admin.discounts');
    })->name('discounts.index');
    Route::get('promotions', function () {
        return view('admin.promotions');
    })->name('promotions.index');
});

// Order placement (public)
Route::post('orders', [OrderController::class, 'store'])->name('orders.store');

// Payment routes (public)
Route::get('payment/fpx/{orderId}', App\Livewire\FpxPaymentPage::class)->name('payment.fpx');
Route::get('payment/qr/{orderId}', App\Livewire\QrPaymentPage::class)->name('payment.qr');
Route::post('payment/fpx/callback', [App\Http\Controllers\PaymentController::class, 'fpxCallback'])->name('payment.fpx.callback');
Route::get('payment/fpx/success/{order}', [App\Http\Controllers\PaymentController::class, 'fpxSuccess'])->name('payment.fpx.success');

// Authentication routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
