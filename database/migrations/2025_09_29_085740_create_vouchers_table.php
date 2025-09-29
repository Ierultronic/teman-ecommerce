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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed']); // percentage or fixed amount
            $table->decimal('value', 10, 2); // voucher value (percentage or fixed amount)
            $table->decimal('minimum_amount', 10, 2)->nullable(); // minimum order amount to qualify
            $table->decimal('maximum_discount', 10, 2)->nullable(); // maximum discount amount
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->datetime('starts_at')->nullable();
            $table->datetime('ends_at')->nullable();
            $table->integer('usage_limit')->nullable(); // null = unlimited
            $table->integer('usage_limit_per_user')->nullable(); // null = unlimited per user
            $table->integer('used_count')->default(0);
            $table->json('applicable_products')->nullable(); // specific product IDs
            $table->json('applicable_categories')->nullable(); // specific category IDs
            $table->boolean('single_use')->default(true); // true = coupon, false = multiple use
            $table->boolean('for_first_time_only')->default(false);
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'starts_at', 'ends_at']);
            $table->index(['code', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
