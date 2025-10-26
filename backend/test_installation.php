<?php
/**
 * EGUIDANCE System Test Script
 * Run this to verify your installation is working correctly
 */

echo "EGUIDANCE System Test\n";
echo "====================\n\n";

// Test 1: Database Connection
echo "Test 1: Database Connection... ";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=eguidance', 'root', '');
    echo "✓ PASS\n";
} catch (PDOException $e) {
    echo "✗ FAIL: " . $e->getMessage() . "\n";
    exit(1);
}

// Test 2: Check Tables Exist
echo "Test 2: Database Tables... ";
$tables = ['users', 'otps', 'appointments'];
$allTablesExist = true;

foreach ($tables as $table) {
    $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
    if ($stmt->rowCount() === 0) {
        $allTablesExist = false;
        break;
    }
}

if ($allTablesExist) {
    echo "✓ PASS\n";
} else {
    echo "✗ FAIL: Some tables are missing. Run migrations first.\n";
    exit(1);
}

// Test 3: Check Sample Users
echo "Test 3: Sample Users... ";
$stmt = $pdo->query("SELECT COUNT(*) FROM users");
$userCount = $stmt->fetchColumn();

if ($userCount >= 4) {
    echo "✓ PASS ($userCount users found)\n";
} else {
    echo "✗ FAIL: Only $userCount users found. Run seed_data.php\n";
}

// Test 4: Check LavaLust Files
echo "Test 4: LavaLust Framework... ";
$requiredFiles = [
    'scheme/kernel/LavaLust.php',
    'app/config/database.php',
    'app/config/routes.php',
    'app/controllers/AuthController.php',
    'app/models/User.php',
    'app/models/Otp.php'
];

$allFilesExist = true;
foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        $allFilesExist = false;
        break;
    }
}

if ($allFilesExist) {
    echo "✓ PASS\n";
} else {
    echo "✗ FAIL: Some required files are missing\n";
    exit(1);
}

// Test 5: Check Views
echo "Test 5: Frontend Views... ";
$viewFiles = [
    'app/views/login.php',
    'app/views/student_dashboard.php',
    'app/views/counselor_dashboard.php'
];

$allViewsExist = true;
foreach ($viewFiles as $file) {
    if (!file_exists($file)) {
        $allViewsExist = false;
        break;
    }
}

if ($allViewsExist) {
    echo "✓ PASS\n";
} else {
    echo "✗ FAIL: Some view files are missing\n";
    exit(1);
}

// Test 6: PHP Extensions
echo "Test 6: PHP Extensions... ";
$requiredExtensions = ['pdo', 'pdo_mysql', 'session', 'json'];
$allExtensionsExist = true;

foreach ($requiredExtensions as $ext) {
    if (!extension_loaded($ext)) {
        $allExtensionsExist = false;
        break;
    }
}

if ($allExtensionsExist) {
    echo "✓ PASS\n";
} else {
    echo "✗ FAIL: Some required PHP extensions are missing\n";
    exit(1);
}

echo "\n🎉 All tests passed! Your EGUIDANCE system is ready to use.\n\n";
echo "Next steps:\n";
echo "===========\n";
echo "1. Start the server: php -S localhost:8000 -t public\n";
echo "2. Visit: http://localhost:8000/login\n";
echo "3. Test with sample accounts:\n";
echo "   - john@student.edu (student)\n";
echo "   - sarah@counselor.edu (counselor)\n";
echo "4. Use OTP authentication flow\n\n";
echo "For email testing, configure SMTP in AuthController.php\n";
echo "Happy coding! 🚀\n";
?>
