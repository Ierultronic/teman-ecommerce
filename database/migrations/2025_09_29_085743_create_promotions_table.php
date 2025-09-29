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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('banner_image')->nullable();
            $table->enum('type', ['buy_x_get_y', 'buy_x_get_percentage', 'bulk_discount', 'category_discount']);
            $table->json('rules'); // Configuration for different promotion types
            $table->decimal('minimum_amount', 10, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->datetime('starts_at')->nullable();
            $table->datetime('ends_at')->nullable();
            $table->json('target_products')->nullable(); // specific products for this promotion
            $table->json('target_categories')->nullable(); // specific categories for this promotion
            $table->integer('priority')->default(0); // higher number = higher priority
            $table->boolean('exclusive')->default(false); // cannot be combined with other promotions
            $table->integer('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['status', 'starts_at', 'ends_at', 'priority']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
