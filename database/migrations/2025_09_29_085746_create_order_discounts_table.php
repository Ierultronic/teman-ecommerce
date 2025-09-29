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
        Schema::create('order_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->nullableMorphs('discountable'); // polymorphic: discount/promotion/voucher
            $table->string('name'); // name of the discount applied
            $table->enum('type', ['percentage', 'fixed']);
            $table->decimal('value', 10, 2); // original discount value
            $table->decimal('calculated_amount', 10, 2); // actual discount amount applied
            $table->string('applied_code')->nullable(); // voucher code if applicable
            $table->json('applied_rules')->nullable(); // snapshot of rules used for calculation
            $table->timestamps();
            
            $table->index(['order_id', 'discountable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_discounts');
    }
};
