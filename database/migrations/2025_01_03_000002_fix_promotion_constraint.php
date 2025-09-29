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
        // Drop the existing constraint for PostgreSQL
        try {
            DB::statement('ALTER TABLE promotions DROP CONSTRAINT IF EXISTS promotions_type_check');
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }
        
        // Change column type to allow any string
        DB::statement('ALTER TABLE promotions ALTER COLUMN type TYPE VARCHAR(100)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Simple rollback - no constraint recreation for safety
        DB::statement('ALTER TABLE promotions ALTER COLUMN type TYPE VARCHAR(100)');
    }
};
