<?php
/**
 * Complete Database Setup Script
 * Creates database, tables, and sample data
 */

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'eguidance';

echo "🚀 EGUIDANCE Complete Database Setup\n";
echo "====================================\n\n";

try {
    // Step 1: Connect to MySQL server (without database)
    echo "1. Connecting to MySQL server...\n";
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    echo "✅ Connected to MySQL server\n\n";
    
    // Step 2: Create database if it doesn't exist
    echo "2. Creating database '$database'...\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '$database' created/verified\n\n";
    
    // Step 3: Connect to the specific database
    echo "3. Connecting to '$database' database...\n";
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    echo "✅ Connected to '$database' database\n\n";
    
    // Step 4: Create tables
    echo "4. Creating database tables...\n";
    
    // Users table
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        role ENUM('student', 'counselor') NOT NULL,
        phone VARCHAR(20) NULL,
        student_id VARCHAR(20) NULL,
        department VARCHAR(100) NULL,
        year_level VARCHAR(20) NULL,
        specialization VARCHAR(100) NULL,
        license_number VARCHAR(50) NULL,
        years_experience INT NULL,
        bio TEXT NULL,
        profile_picture VARCHAR(255) NULL,
        is_active BOOLEAN DEFAULT TRUE,
        email_verified_at TIMESTAMP NULL,
        last_login_at TIMESTAMP NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_email (email),
        INDEX idx_role (role),
        INDEX idx_active (is_active)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql);
    echo "✅ Users table created\n";
    
    // OTPs table
    $sql = "
    CREATE TABLE IF NOT EXISTS otps (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        code VARCHAR(6) NOT NULL,
        expires_at TIMESTAMP NOT NULL,
        used_at TIMESTAMP NULL,
        ip_address VARCHAR(45) NULL,
        user_agent TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_user_id (user_id),
        INDEX idx_code (code),
        INDEX idx_expires (expires_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql);
    echo "✅ OTPs table created\n";
    
    // Appointments table
    $sql = "
    CREATE TABLE IF NOT EXISTS appointments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        counselor_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        appointment_date DATE NOT NULL,
        appointment_time TIME NOT NULL,
        duration_minutes INT DEFAULT 60,
        status ENUM('pending', 'approved', 'completed', 'canceled', 'rescheduled') DEFAULT 'pending',
        priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
        notes TEXT,
        cancellation_reason TEXT NULL,
        rescheduled_to DATETIME NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (counselor_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_student (student_id),
        INDEX idx_counselor (counselor_id),
        INDEX idx_date (appointment_date),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql);
    echo "✅ Appointments table created\n";
    
    // Counseling sessions table
    $sql = "
    CREATE TABLE IF NOT EXISTS counseling_sessions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        appointment_id INT NOT NULL,
        session_date DATE NOT NULL,
        session_time TIME NOT NULL,
        duration_minutes INT DEFAULT 60,
        session_type ENUM('individual', 'group', 'crisis', 'follow_up') DEFAULT 'individual',
        notes TEXT,
        goals_achieved TEXT,
        homework_assigned TEXT,
        follow_up_required BOOLEAN DEFAULT FALSE,
        follow_up_date DATE NULL,
        student_feedback TEXT NULL,
        counselor_notes TEXT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
        INDEX idx_appointment (appointment_id),
        INDEX idx_date (session_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql);
    echo "✅ Counseling sessions table created\n";
    
    // Messages table
    $sql = "
    CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        subject VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        read_at TIMESTAMP NULL,
        parent_message_id INT NULL,
        message_type ENUM('text', 'file', 'appointment', 'system') DEFAULT 'text',
        attachment_path VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (parent_message_id) REFERENCES messages(id) ON DELETE SET NULL,
        INDEX idx_sender (sender_id),
        INDEX idx_receiver (receiver_id),
        INDEX idx_read (is_read),
        INDEX idx_created (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql);
    echo "✅ Messages table created\n";
    
    // Wellness assessments table
    $sql = "
    CREATE TABLE IF NOT EXISTS wellness_assessments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT NOT NULL,
        assessment_type ENUM('stress', 'anxiety', 'depression', 'academic', 'social', 'general') NOT NULL,
        score INT NOT NULL,
        max_score INT NOT NULL,
        assessment_data JSON,
        recommendations TEXT,
        completed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
        INDEX idx_student (student_id),
        INDEX idx_type (assessment_type),
        INDEX idx_completed (completed_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $pdo->exec($sql);
    echo "✅ Wellness assessments table created\n";
    
    echo "\n";
    
    // Step 5: Insert sample data
    echo "5. Inserting sample data...\n";
    
    // Sample users
    $users = [
        [
            'name' => 'John Smith',
            'email' => 'john@student.edu',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'role' => 'student',
            'phone' => '+1234567890',
            'student_id' => 'STU001',
            'department' => 'Computer Science',
            'year_level' => '3rd Year'
        ],
        [
            'name' => 'Jane Doe',
            'email' => 'jane@student.edu',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'role' => 'student',
            'phone' => '+1234567891',
            'student_id' => 'STU002',
            'department' => 'Psychology',
            'year_level' => '2nd Year'
        ],
        [
            'name' => 'Sarah Johnson',
            'email' => 'sarah@counselor.edu',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'role' => 'counselor',
            'phone' => '+1234567892',
            'license_number' => 'LIC001',
            'years_experience' => 5,
            'specialization' => 'Academic Counseling',
            'bio' => 'Experienced counselor specializing in academic stress and career guidance.'
        ],
        [
            'name' => 'Michael Brown',
            'email' => 'michael@counselor.edu',
            'password' => password_hash('password123', PASSWORD_BCRYPT),
            'role' => 'counselor',
            'phone' => '+1234567893',
            'license_number' => 'LIC002',
            'years_experience' => 8,
            'specialization' => 'Mental Health Counseling',
            'bio' => 'Licensed mental health counselor with expertise in anxiety and depression management.'
        ]
    ];
    
    // Simple insert for basic users table
    $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, role) 
        VALUES (?, ?, ?, ?)
    ");
    
    foreach ($users as $user) {
        try {
            $stmt->execute([
                $user['name'],
                $user['email'],
                $user['password'],
                $user['role']
            ]);
            echo "✅ User '{$user['name']}' created\n";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry
                echo "ℹ️  User '{$user['name']}' already exists\n";
            } else {
                echo "❌ Failed to create user '{$user['name']}': " . $e->getMessage() . "\n";
            }
        }
    }
    
    // Sample appointments (using correct column names)
    $appointments = [
        [
            'student_id' => 1,
            'counselor_id' => 3,
            'appointment_date' => date('Y-m-d', strtotime('+1 day')),
            'appointment_time' => '10:00:00',
            'duration_minutes' => 60,
            'status' => 'approved',
            'appointment_type' => 'individual',
            'notes' => 'Academic stress counseling session'
        ],
        [
            'student_id' => 2,
            'counselor_id' => 4,
            'appointment_date' => date('Y-m-d', strtotime('+2 days')),
            'appointment_time' => '14:00:00',
            'duration_minutes' => 60,
            'status' => 'pending',
            'appointment_type' => 'individual',
            'notes' => 'Mental health check-in session'
        ]
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO appointments (student_id, counselor_id, appointment_date, appointment_time, duration_minutes, status, appointment_type, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($appointments as $appointment) {
        try {
            $stmt->execute([
                $appointment['student_id'],
                $appointment['counselor_id'],
                $appointment['appointment_date'],
                $appointment['appointment_time'],
                $appointment['duration_minutes'],
                $appointment['status'],
                $appointment['appointment_type'],
                $appointment['notes']
            ]);
            echo "✅ Appointment created for student {$appointment['student_id']}\n";
        } catch (PDOException $e) {
            echo "❌ Failed to create appointment: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n";
    
    // Step 6: Verify setup
    echo "6. Verifying database setup...\n";
    
    // Count users
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users");
    $stmt->execute();
    $userCount = $stmt->fetch()['count'];
    echo "✅ Total users: $userCount\n";
    
    // Count appointments
    $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM appointments");
    $stmt->execute();
    $appointmentCount = $stmt->fetch()['count'];
    echo "✅ Total appointments: $appointmentCount\n";
    
    // Show table structure
    echo "\n7. Database structure:\n";
    $tables = ['users', 'otps', 'appointments', 'counseling_sessions', 'messages', 'wellness_assessments'];
    foreach ($tables as $table) {
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        if ($stmt->fetch()) {
            echo "✅ Table '$table' exists\n";
        } else {
            echo "❌ Table '$table' missing\n";
        }
    }
    
    echo "\n";
    echo "🎉 Database setup completed successfully!\n";
    echo "========================================\n";
    echo "\n";
    echo "📋 Sample Login Credentials:\n";
    echo "Students:\n";
    echo "  - john@student.edu / password123\n";
    echo "  - jane@student.edu / password123\n";
    echo "\n";
    echo "Counselors:\n";
    echo "  - sarah@counselor.edu / password123\n";
    echo "  - michael@counselor.edu / password123\n";
    echo "\n";
    echo "🚀 You can now start the backend server and test the registration!\n";
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    echo "\n";
    echo "🔧 Troubleshooting:\n";
    echo "1. Make sure XAMPP/WAMP is running\n";
    echo "2. Check if MySQL service is started\n";
    echo "3. Verify MySQL credentials\n";
    echo "4. Check if port 3306 is available\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n";
?>
