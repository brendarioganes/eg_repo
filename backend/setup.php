<?php
/**
 * EGUIDANCE Setup Script
 * Automated setup for Phase 1 of the EGUIDANCE system
 */

echo "EGUIDANCE System Setup\n";
echo "=====================\n\n";

// Check PHP version
echo "Checking PHP version... ";
if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
    echo "✓ PHP " . PHP_VERSION . " (OK)\n";
} else {
    echo "✗ PHP " . PHP_VERSION . " (Requires PHP 8.0+)\n";
    exit(1);
}

// Check if we're in the right directory
echo "Checking directory structure... ";
if (file_exists('app') && file_exists('scheme') && file_exists('index.php')) {
    echo "✓ LavaLust structure found\n";
} else {
    echo "✗ Please run this script from the backend directory\n";
    exit(1);
}

// Check database connection
echo "Testing database connection... ";
try {
    $pdo = new PDO('mysql:host=localhost', 'root', '');
    echo "✓ MySQL connection successful\n";
} catch (PDOException $e) {
    echo "✗ MySQL connection failed: " . $e->getMessage() . "\n";
    echo "Please ensure XAMPP is running and MySQL is started\n";
    exit(1);
}

// Create database if it doesn't exist
echo "Creating database 'eguidance'... ";
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS eguidance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✓ Database created/verified\n";
} catch (PDOException $e) {
    echo "✗ Database creation failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\nRunning migrations...\n";
echo "===================\n";

// Include and run migrations
require_once 'run_migrations.php';

echo "\nSeeding sample data...\n";
echo "=====================\n";

// Include and run seeder
require_once 'seed_data.php';

echo "\n✓ Setup completed successfully!\n";
echo "\nNext steps:\n";
echo "===========\n";
echo "1. Start the development server:\n";
echo "   php -S localhost:8000 -t public\n\n";
echo "2. Open your browser and visit:\n";
echo "   http://localhost:8000/login\n\n";
echo "3. Test with sample accounts:\n";
echo "   - Students: john@student.edu, jane@student.edu\n";
echo "   - Counselors: sarah@counselor.edu, michael@counselor.edu\n";
echo "   - Password: password123 (for registration)\n\n";
echo "4. Use OTP authentication:\n";
echo "   - Enter email → Check email for OTP → Enter OTP → Login\n\n";
echo "Note: For email functionality, configure SMTP settings in AuthController.php\n";
echo "For local testing, you may need to configure your local mail server.\n\n";
echo "Happy coding! 🚀\n";
?>
