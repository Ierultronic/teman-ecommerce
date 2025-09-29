<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('variant_name');
            $table->integer('stock')->default(0);
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Add check constraint to prevent negative stock
        DB::statement('ALTER TABLE product_variants ADD CONSTRAINT check_stock_non_negative CHECK (stock >= 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the check constraint first
        DB::statement('ALTER TABLE product_variants DROP CONSTRAINT IF EXISTS check_stock_non_negative');
        Schema::dropIfExists('product_variants');
    }
};
