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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
            $table->string('payment_reference')->nullable()->after('payment_method');
            $table->string('payment_receipt_path')->nullable()->after('payment_reference');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_receipt_path');
            $table->unsignedBigInteger('payment_verified_by')->nullable()->after('payment_verified_at');
            
            $table->foreign('payment_verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['payment_verified_by']);
            $table->dropColumn([
                'payment_method',
                'payment_reference', 
                'payment_receipt_path',
                'payment_verified_at',
                'payment_verified_by'
            ]);
        });
    }
};
