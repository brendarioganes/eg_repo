-- =====================================================
-- EGUIDANCE Counseling and Student Wellness System
-- Complete Database Schema (Phase 1)
-- =====================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS eguidance 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE eguidance;

-- =====================================================
-- 1. USERS TABLE
-- Stores user accounts with roles (student/counselor)
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COMMENT 'Full name of the user',
    email VARCHAR(255) UNIQUE NOT NULL COMMENT 'Email address (unique)',
    password VARCHAR(255) NOT NULL COMMENT 'Hashed password',
    role ENUM('student', 'counselor') NOT NULL DEFAULT 'student' COMMENT 'User role',
    phone VARCHAR(20) NULL COMMENT 'Phone number (optional)',
    date_of_birth DATE NULL COMMENT 'Date of birth (optional)',
    address TEXT NULL COMMENT 'Address (optional)',
    profile_picture VARCHAR(255) NULL COMMENT 'Profile picture path',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Account status',
    email_verified_at TIMESTAMP NULL COMMENT 'Email verification timestamp',
    last_login_at TIMESTAMP NULL COMMENT 'Last login timestamp',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Account creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp',
    
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='User accounts table';

-- =====================================================
-- 2. OTPS TABLE
-- Stores one-time passwords for authentication
-- =====================================================
CREATE TABLE IF NOT EXISTS otps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'Reference to users table',
    code VARCHAR(6) NOT NULL COMMENT '6-digit OTP code',
    expires_at TIMESTAMP NOT NULL COMMENT 'OTP expiration timestamp',
    is_used BOOLEAN DEFAULT FALSE COMMENT 'Whether OTP has been used',
    ip_address VARCHAR(45) NULL COMMENT 'IP address of request',
    user_agent TEXT NULL COMMENT 'User agent string',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'OTP creation timestamp',
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_code (code),
    INDEX idx_expires_at (expires_at),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='One-time passwords table';

-- =====================================================
-- 3. APPOINTMENTS TABLE
-- Stores counseling appointments (Phase 1 - Basic structure)
-- =====================================================
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL COMMENT 'Reference to student user',
    counselor_id INT NOT NULL COMMENT 'Reference to counselor user',
    appointment_date DATE NOT NULL COMMENT 'Appointment date',
    appointment_time TIME NOT NULL COMMENT 'Appointment time',
    duration_minutes INT DEFAULT 60 COMMENT 'Appointment duration in minutes',
    status ENUM('pending', 'approved', 'rejected', 'completed', 'canceled', 'no_show') 
        NOT NULL DEFAULT 'pending' COMMENT 'Appointment status',
    appointment_type ENUM('individual', 'group', 'emergency') 
        DEFAULT 'individual' COMMENT 'Type of appointment',
    notes TEXT NULL COMMENT 'Appointment notes',
    student_notes TEXT NULL COMMENT 'Student notes/concerns',
    counselor_notes TEXT NULL COMMENT 'Counselor notes',
    follow_up_required BOOLEAN DEFAULT FALSE COMMENT 'Whether follow-up is required',
    follow_up_date DATE NULL COMMENT 'Follow-up appointment date',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Appointment creation timestamp',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp',
    
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (counselor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_student_id (student_id),
    INDEX idx_counselor_id (counselor_id),
    INDEX idx_appointment_date (appointment_date),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    UNIQUE KEY unique_appointment (counselor_id, appointment_date, appointment_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='Appointments table';

-- =====================================================
-- 4. SESSIONS TABLE (Optional - for advanced session management)
-- =====================================================
CREATE TABLE IF NOT EXISTS user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL COMMENT 'Reference to users table',
    session_token VARCHAR(255) NOT NULL COMMENT 'Session token',
    ip_address VARCHAR(45) NULL COMMENT 'IP address',
    user_agent TEXT NULL COMMENT 'User agent string',
    expires_at TIMESTAMP NOT NULL COMMENT 'Session expiration',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Session status',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Session creation timestamp',
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last activity timestamp',
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_session_token (session_token),
    INDEX idx_expires_at (expires_at),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='User sessions table';

-- =====================================================
-- 5. SYSTEM LOGS TABLE (Optional - for audit trail)
-- =====================================================
CREATE TABLE IF NOT EXISTS system_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL COMMENT 'Reference to users table (nullable for system events)',
    action VARCHAR(100) NOT NULL COMMENT 'Action performed',
    description TEXT NULL COMMENT 'Action description',
    ip_address VARCHAR(45) NULL COMMENT 'IP address',
    user_agent TEXT NULL COMMENT 'User agent string',
    metadata JSON NULL COMMENT 'Additional metadata',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Log timestamp',
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user_id (user_id),
    INDEX idx_action (action),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci 
COMMENT='System logs table';

-- =====================================================
-- SAMPLE DATA INSERTION
-- =====================================================

-- Insert sample users
INSERT INTO users (name, email, password, role, phone, date_of_birth, address) VALUES
('John Smith', 'john@student.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+1234567890', '2000-05-15', '123 Student St, University City'),
('Jane Doe', 'jane@student.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', '+1234567891', '1999-08-22', '456 College Ave, University City'),
('Dr. Sarah Johnson', 'sarah@counselor.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'counselor', '+1234567892', '1985-03-10', '789 Professional Blvd, University City'),
('Dr. Michael Brown', 'michael@counselor.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'counselor', '+1234567893', '1980-12-05', '321 Counselor Lane, University City');

-- Insert sample appointments
INSERT INTO appointments (student_id, counselor_id, appointment_date, appointment_time, status, notes) VALUES
(1, 3, '2024-01-15', '10:00:00', 'approved', 'First counseling session'),
(2, 4, '2024-01-16', '14:00:00', 'pending', 'Follow-up session'),
(1, 3, '2024-01-20', '11:00:00', 'completed', 'Regular check-in'),
(2, 4, '2024-01-22', '15:30:00', 'approved', 'Academic stress management');

-- =====================================================
-- VIEWS FOR COMMON QUERIES
-- =====================================================

-- View for user statistics
CREATE VIEW user_stats AS
SELECT 
    role,
    COUNT(*) as total_users,
    COUNT(CASE WHEN is_active = TRUE THEN 1 END) as active_users,
    COUNT(CASE WHEN last_login_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as recent_logins
FROM users 
GROUP BY role;

-- View for appointment statistics
CREATE VIEW appointment_stats AS
SELECT 
    status,
    COUNT(*) as count,
    AVG(duration_minutes) as avg_duration
FROM appointments 
GROUP BY status;

-- View for counselor workload
CREATE VIEW counselor_workload AS
SELECT 
    c.id as counselor_id,
    c.name as counselor_name,
    COUNT(a.id) as total_appointments,
    COUNT(CASE WHEN a.status = 'pending' THEN 1 END) as pending_appointments,
    COUNT(CASE WHEN a.appointment_date = CURDATE() THEN 1 END) as today_appointments
FROM users c
LEFT JOIN appointments a ON c.id = a.counselor_id
WHERE c.role = 'counselor'
GROUP BY c.id, c.name;

-- =====================================================
-- STORED PROCEDURES FOR COMMON OPERATIONS
-- =====================================================

DELIMITER //

-- Procedure to clean expired OTPs
CREATE PROCEDURE CleanExpiredOTPs()
BEGIN
    DELETE FROM otps WHERE expires_at < NOW();
    SELECT ROW_COUNT() as deleted_count;
END //

-- Procedure to get user dashboard data
CREATE PROCEDURE GetUserDashboard(IN user_id INT)
BEGIN
    SELECT 
        u.id,
        u.name,
        u.email,
        u.role,
        u.last_login_at,
        COUNT(a.id) as total_appointments,
        COUNT(CASE WHEN a.status = 'pending' THEN 1 END) as pending_appointments,
        COUNT(CASE WHEN a.status = 'completed' THEN 1 END) as completed_appointments
    FROM users u
    LEFT JOIN appointments a ON u.id = a.student_id OR u.id = a.counselor_id
    WHERE u.id = user_id
    GROUP BY u.id;
END //

-- Procedure to get today's appointments for counselor
CREATE PROCEDURE GetTodaysAppointments(IN counselor_id INT)
BEGIN
    SELECT 
        a.id,
        a.appointment_time,
        u.name as student_name,
        u.email as student_email,
        a.status,
        a.notes
    FROM appointments a
    JOIN users u ON a.student_id = u.id
    WHERE a.counselor_id = counselor_id 
    AND a.appointment_date = CURDATE()
    ORDER BY a.appointment_time;
END //

DELIMITER ;

-- =====================================================
-- TRIGGERS FOR AUTOMATIC UPDATES
-- =====================================================

-- Trigger to update last_login_at when user logs in
DELIMITER //
CREATE TRIGGER update_last_login 
AFTER INSERT ON user_sessions
FOR EACH ROW
BEGIN
    UPDATE users 
    SET last_login_at = NOW() 
    WHERE id = NEW.user_id;
END //
DELIMITER ;

-- =====================================================
-- INDEXES FOR PERFORMANCE OPTIMIZATION
-- =====================================================

-- Additional indexes for better performance
CREATE INDEX idx_appointments_date_time ON appointments(appointment_date, appointment_time);
CREATE INDEX idx_appointments_student_status ON appointments(student_id, status);
CREATE INDEX idx_appointments_counselor_status ON appointments(counselor_id, status);
CREATE INDEX idx_users_email_active ON users(email, is_active);
CREATE INDEX idx_otps_user_expires ON otps(user_id, expires_at);

-- =====================================================
-- GRANT PERMISSIONS (Adjust as needed for your setup)
-- =====================================================

-- Grant permissions to application user (replace 'app_user' with your actual username)
-- GRANT SELECT, INSERT, UPDATE, DELETE ON eguidance.* TO 'app_user'@'localhost';
-- FLUSH PRIVILEGES;

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Check table creation
SHOW TABLES;

-- Check table structures
DESCRIBE users;
DESCRIBE otps;
DESCRIBE appointments;
DESCRIBE user_sessions;
DESCRIBE system_logs;

-- Check sample data
SELECT 'Users' as table_name, COUNT(*) as count FROM users
UNION ALL
SELECT 'OTPs', COUNT(*) FROM otps
UNION ALL
SELECT 'Appointments', COUNT(*) FROM appointments;

-- Check views
SELECT * FROM user_stats;
SELECT * FROM appointment_stats;
SELECT * FROM counselor_workload;

-- =====================================================
-- END OF SCHEMA
-- =====================================================
