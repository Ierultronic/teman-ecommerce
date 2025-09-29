<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Fixing promotion constraint...\n";

try {
    // Drop the check constraint if it exists
    DB::statement('ALTER TABLE promotions DROP CONSTRAINT IF EXISTS promotions_type_check');
    
    // Alter the column type to varchar
    DB::statement('ALTER TABLE promotions ALTER COLUMN type TYPE VARCHAR(100)');
    
    echo "✅ Constraint fixed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error fixing constraint: " . $e->getMessage() . "\n";
    exit(1);
}

exit(0);
