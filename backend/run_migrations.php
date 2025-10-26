<?php
define('PREVENT_DIRECT_ACCESS', TRUE);

// Include LavaLust bootstrap
require_once 'index.php';

/**
 * Migration Runner Script
 * Run this script to execute database migrations
 */

echo "EGUIDANCE Database Migration Runner\n";
echo "===================================\n\n";

// List of migrations to run
$migrations = [
    'CreateUsersTable',
    'CreateOtpsTable', 
    'CreateAppointmentsTable'
];

$successCount = 0;
$errorCount = 0;

foreach ($migrations as $migrationClass) {
    echo "Running migration: $migrationClass... ";
    
    try {
        // Include the migration file
        $migrationFile = APP_DIR . "Database/Migrations/{$migrationClass}.php";
        
        if (!file_exists($migrationFile)) {
            throw new Exception("Migration file not found: $migrationFile");
        }
        
        require_once $migrationFile;
        
        // Create instance and run migration
        $migration = new $migrationClass();
        $migration->up();
        
        echo "✓ SUCCESS\n";
        $successCount++;
        
    } catch (Exception $e) {
        echo "✗ FAILED: " . $e->getMessage() . "\n";
        $errorCount++;
    }
}

echo "\nMigration Summary:\n";
echo "=================\n";
echo "Successful: $successCount\n";
echo "Failed: $errorCount\n";

if ($errorCount === 0) {
    echo "\n✓ All migrations completed successfully!\n";
    echo "You can now start the application.\n";
} else {
    echo "\n✗ Some migrations failed. Please check the errors above.\n";
}

echo "\nTo start the application, run:\n";
echo "php -S localhost:8000 -t public\n";
echo "\nThen visit: http://localhost:8000/login\n";
?>