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
        Schema::table('promotions', function (Blueprint $table) {
            // Drop the check constraint first for PostgreSQL
            $table->dropCheck('promotions_type_check');
        });
        
        Schema::table('promotions', function (Blueprint $table) {
            // Change the type field from enum to string to allow free text
            $table->string('type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Simplified rollback - just change column type without adding constraint
        Schema::table('promotions', function (Blueprint $table) {
            // Change column type back to varchar without constraint
            $table->string('type')->nullable()->change();
        });
    }
};
