<?php
// Simple database check script
require_once 'app/config/database.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    
    echo "Database connection successful!\n\n";
    
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
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
