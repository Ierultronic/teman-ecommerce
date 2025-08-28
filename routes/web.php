<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;

use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;

// Store routes (public)
Route::get('/', [StoreController::class, 'index'])->name('store.index');

// Admin routes (protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function() {
        return redirect()->route('admin.products.index');
    })->name('dashboard');
    
    // Custom product routes (must come before resource route)
    Route::patch('products/{id}/restore', [App\Http\Controllers\Admin\ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [App\Http\Controllers\Admin\ProductController::class, 'forceDelete'])->name('products.force-delete');
    
    // Resource routes
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->except(['create', 'store']);
    Route::patch('orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
});

// Order placement (public)
Route::post('orders', [OrderController::class, 'store'])->name('orders.store');

// Authentication routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
