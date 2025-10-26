<?php
// Simple test server
echo "EGUIDANCE Backend Server Test\n";
echo "=============================\n\n";

echo "Server is running!\n";
echo "Current directory: " . getcwd() . "\n";
echo "PHP version: " . PHP_VERSION . "\n\n";

// Test database connection
try {
    require_once 'app/config/database.php';
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    echo "✅ Database connection successful!\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
}

echo "\n";
echo "Available endpoints:\n";
echo "- GET  / (this page)\n";
echo "- POST /api/register\n";
echo "- POST /api/login\n";
echo "- POST /api/verify-otp\n";
echo "- GET  /api/check-auth\n";
echo "- POST /api/logout\n";
echo "\n";
echo "Test registration with:\n";
echo "curl -X POST http://localhost:8000/api/register \\\n";
echo "  -H 'Content-Type: application/json' \\\n";
echo "  -d '{\"name\":\"Test User\",\"email\":\"test@test.com\",\"password\":\"password123\",\"role\":\"student\"}'\n";
?>
