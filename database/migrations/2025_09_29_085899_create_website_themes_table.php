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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('shop_name')->default('TEMAN');
            $table->string('logo_path')->nullable();
            $table->string('favicon_path')->nullable();
            $table->text('description')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->json('social_links')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('website_settings')->insert([
            'shop_name' => 'TEMAN',
            'logo_path' => 'images/logo.png',
            'favicon_path' => 'images/logo.png',
            'description' => 'Your trusted e-commerce platform',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
