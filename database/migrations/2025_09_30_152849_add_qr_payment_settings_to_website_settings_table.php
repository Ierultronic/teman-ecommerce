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
        Schema::table('website_settings', function (Blueprint $table) {
            $table->string('qr_image_path')->nullable()->after('favicon_path');
            $table->string('qr_bank_name')->nullable()->after('qr_image_path');
            $table->string('qr_account_number')->nullable()->after('qr_bank_name');
            $table->string('qr_account_holder_name')->nullable()->after('qr_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'qr_image_path',
                'qr_bank_name',
                'qr_account_number',
                'qr_account_holder_name'
            ]);
        });
    }
};
