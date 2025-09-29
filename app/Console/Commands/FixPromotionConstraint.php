<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixPromotionConstraint extends Command
{
    protected $signature = 'promotions:fix-constraint';
    protected $description = 'Fix PostgreSQL constraint for promotions type field';

    public function handle()
    {
        $this->info('Fixing promotion constraint...');
        
        try {
            // Drop the check constraint if it exists
            DB::statement('ALTER TABLE promotions DROP CONSTRAINT IF EXISTS promotions_type_check');
            
            // Alter the column type to varchar
            DB::statement('ALTER TABLE promotions ALTER COLUMN type TYPE VARCHAR(100)');
            
            $this->info('✅ Constraint fixed successfully!');
            
        } catch (\Exception $e) {
            $this->error('❌ Error fixing constraint: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
}
