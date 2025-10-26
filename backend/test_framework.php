<?php
/**
 * Simple LavaLust Test
 * Test if the framework is working properly
 */

echo "🧪 LavaLust Framework Test\n";
echo "=========================\n\n";

// Test 1: Check if index.php exists
if (file_exists('index.php')) {
    echo "✅ index.php exists\n";
} else {
    echo "❌ index.php missing\n";
    exit;
}

// Test 2: Check if scheme directory exists
if (is_dir('scheme')) {
    echo "✅ scheme directory exists\n";
} else {
    echo "❌ scheme directory missing\n";
    exit;
}

// Test 3: Check if app directory exists
if (is_dir('app')) {
    echo "✅ app directory exists\n";
} else {
    echo "❌ app directory missing\n";
    exit;
}

// Test 4: Check if LavaLust.php exists
if (file_exists('scheme/kernel/LavaLust.php')) {
    echo "✅ LavaLust.php exists\n";
} else {
    echo "❌ LavaLust.php missing\n";
    exit;
}

echo "\n";

// Test 5: Try to include the framework
try {
    define('PREVENT_DIRECT_ACCESS', TRUE);
    
    // Set up constants
    define('ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR);
    define('SYSTEM_DIR', ROOT_DIR . 'scheme' . DIRECTORY_SEPARATOR);
    define('APP_DIR', ROOT_DIR . 'app' . DIRECTORY_SEPARATOR);
    define('PUBLIC_DIR', 'public');
    
    // Include the framework
    require_once SYSTEM_DIR . 'kernel/LavaLust.php';
    
    echo "✅ LavaLust framework loaded successfully!\n";
    echo "✅ Framework is ready to handle requests\n";
    
} catch (Exception $e) {
    echo "❌ Framework loading failed: " . $e->getMessage() . "\n";
}

echo "\n";
echo "🚀 Ready to start server!\n";
echo "Use: php -S localhost:8000\n";
echo "Then test: http://localhost:8000/\n";
?>
