<?php
/**
 * Database Connection Test Script
 * Tests the database connection and creates tables if needed
 */

// Include the database configuration
require_once 'app/config/database.php';
require_once 'app/core/ORM.php';

echo "🔧 EGUIDANCE Database Connection Test\n";
echo "=====================================\n\n";

try {
    // Test database connection
    echo "1. Testing database connection...\n";
    
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    
    echo "✅ Database connection successful!\n";
    echo "   Host: " . DB_HOST . "\n";
    echo "   Database: " . DB_NAME . "\n";
    echo "   User: " . DB_USER . "\n\n";
    
    // Test if tables exist
    echo "2. Checking database tables...\n";
    
    $tables = ['users', 'otps', 'appointments', 'counseling_sessions'];
    $existingTables = [];
    
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        if ($stmt->fetch()) {
            echo "✅ Table '$table' exists\n";
            $existingTables[] = $table;
        } else {
            echo "❌ Table '$table' missing\n";
        }
    }
    
    echo "\n";
    
    // Create missing tables
    if (count($existingTables) < count($tables)) {
        echo "3. Creating missing tables...\n";
        
        // Users table
        if (!in_array('users', $existingTables)) {
            echo "Creating 'users' table...\n";
            $sql = "
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                role ENUM('student', 'counselor') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
            $pdo->exec($sql);
            echo "✅ Users table created\n";
        }
        
        // OTPs table
        if (!in_array('otps', $existingTables)) {
            echo "Creating 'otps' table...\n";
            $sql = "
            CREATE TABLE otps (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                code VARCHAR(6) NOT NULL,
                expires_at TIMESTAMP NOT NULL,
                used_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )";
            $pdo->exec($sql);
            echo "✅ OTPs table created\n";
        }
        
        // Appointments table
        if (!in_array('appointments', $existingTables)) {
            echo "Creating 'appointments' table...\n";
            $sql = "
            CREATE TABLE appointments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                student_id INT NOT NULL,
                counselor_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT,
                appointment_date DATE NOT NULL,
                appointment_time TIME NOT NULL,
                status ENUM('pending', 'approved', 'completed', 'canceled') DEFAULT 'pending',
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (counselor_id) REFERENCES users(id) ON DELETE CASCADE
            )";
            $pdo->exec($sql);
            echo "✅ Appointments table created\n";
        }
        
        // Counseling sessions table
        if (!in_array('counseling_sessions', $existingTables)) {
            echo "Creating 'counseling_sessions' table...\n";
            $sql = "
            CREATE TABLE counseling_sessions (
                id INT AUTO_INCREMENT PRIMARY KEY,
                appointment_id INT NOT NULL,
                session_date DATE NOT NULL,
                session_time TIME NOT NULL,
                duration_minutes INT DEFAULT 60,
                notes TEXT,
                follow_up_required BOOLEAN DEFAULT FALSE,
                follow_up_date DATE NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
            )";
            $pdo->exec($sql);
            echo "✅ Counseling sessions table created\n";
        }
        
        echo "\n";
    }
    
    // Test ORM connection
    echo "4. Testing ORM connection...\n";
    try {
        $orm = ORM::getConnection();
        echo "✅ ORM connection successful!\n";
    } catch (Exception $e) {
        echo "❌ ORM connection failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // Test user creation
    echo "5. Testing user creation...\n";
    try {
        // Check if test user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute(['test@example.com']);
        $existingUser = $stmt->fetch();
        
        if ($existingUser) {
            echo "✅ Test user already exists\n";
        } else {
            // Create test user
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $result = $stmt->execute([
                'Test User',
                'test@example.com',
                password_hash('password123', PASSWORD_BCRYPT),
                'student'
            ]);
            
            if ($result) {
                echo "✅ Test user created successfully\n";
            } else {
                echo "❌ Failed to create test user\n";
            }
        }
    } catch (Exception $e) {
        echo "❌ User creation test failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n";
    
    // Show current users
    echo "6. Current users in database:\n";
    $stmt = $pdo->prepare("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC");
    $stmt->execute();
    $users = $stmt->fetchAll();
    
    if (empty($users)) {
        echo "   No users found\n";
    } else {
        foreach ($users as $user) {
            echo "   ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}, Role: {$user['role']}\n";
        }
    }
    
    echo "\n";
    
    // Test registration endpoint
    echo "7. Testing registration endpoint...\n";
    echo "   You can test registration by making a POST request to:\n";
    echo "   http://localhost:8000/api/register\n";
    echo "   With JSON data: {\"name\":\"Test User\",\"email\":\"test@test.com\",\"password\":\"password123\",\"role\":\"student\"}\n";
    
    echo "\n";
    echo "🎉 Database setup complete! All systems are ready.\n";
    echo "================================================\n";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "\n";
    echo "🔧 Troubleshooting steps:\n";
    echo "1. Make sure XAMPP/WAMP is running\n";
    echo "2. Check if MySQL service is started\n";
    echo "3. Verify database credentials in app/config/database.php\n";
    echo "4. Create the 'eguidance' database in phpMyAdmin\n";
    echo "5. Check if the database exists: SHOW DATABASES;\n";
    echo "\n";
    echo "To create the database manually:\n";
    echo "CREATE DATABASE eguidance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
