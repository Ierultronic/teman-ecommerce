<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('total_discount', 10, 2)->default(0.00);
            
            // Status enum with all values
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'pending_verification', 'paid'])->default('pending');
            
            // Payment fields
            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_receipt_path')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
            $table->unsignedBigInteger('payment_verified_by')->nullable();
            
            // Personal Information
            $table->string('customer_address_line_1')->nullable();
            $table->string('customer_address_line_2')->nullable();
            $table->string('customer_city')->nullable();
            $table->string('customer_state')->nullable();
            $table->string('customer_postal_code')->nullable();
            $table->string('customer_country')->nullable();
            
            // Shipping Address (can be different from personal info)
            $table->string('shipping_name')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->string('shipping_address_line_1')->nullable();
            $table->string('shipping_address_line_2')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->nullable();
            
            // Additional fields
            $table->text('order_notes')->nullable();
            $table->boolean('same_as_billing')->default(true);
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('payment_verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
