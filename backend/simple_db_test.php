<?php
// Simple database test without framework
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'eguidance';

try {
    $dsn = "mysql:host=$host;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "✅ Database connection successful!\n\n";
    
    // Check users table structure
    echo "Users table structure:\n";
    $stmt = $pdo->query("DESCRIBE users");
    while ($row = $stmt->fetch()) {
        echo "  {$row['Field']} - {$row['Type']}\n";
    }
    
    echo "\nAppointments table structure:\n";
    $stmt = $pdo->query("DESCRIBE appointments");
    while ($row = $stmt->fetch()) {
        echo "  {$row['Field']} - {$row['Type']}\n";
    }
    
    echo "\nCurrent users:\n";
    $stmt = $pdo->query("SELECT id, name, email, role FROM users");
    while ($row = $stmt->fetch()) {
        echo "  ID: {$row['id']}, Name: {$row['name']}, Email: {$row['email']}, Role: {$row['role']}\n";
    }
    
    echo "\nTesting registration...\n";
    
    // Test user creation
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([
        'Test User',
        'test@example.com',
        password_hash('password123', PASSWORD_BCRYPT),
        'student'
    ]);
    
    if ($result) {
        echo "✅ Test user created successfully!\n";
    } else {
        echo "❌ Failed to create test user\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
