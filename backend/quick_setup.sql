-- =====================================================
-- EGUIDANCE - Quick Database Setup (Phase 1)
-- =====================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS eguidance 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE eguidance;

-- =====================================================
-- 1. USERS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'counselor') NOT NULL DEFAULT 'student',
    phone VARCHAR(20) NULL,
    date_of_birth DATE NULL,
    address TEXT NULL,
    profile_picture VARCHAR(255) NULL,
    is_active BOOLEAN DEFAULT TRUE,
    email_verified_at TIMESTAMP NULL,
    last_login_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. OTPS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS otps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    code VARCHAR(6) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    is_used BOOLEAN DEFAULT FALSE,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_code (code),
    INDEX idx_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. APPOINTMENTS TABLE
-- =====================================================
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    counselor_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    duration_minutes INT DEFAULT 60,
    status ENUM('pending', 'approved', 'rejected', 'completed', 'canceled', 'no_show') 
        NOT NULL DEFAULT 'pending',
    appointment_type ENUM('individual', 'group', 'emergency') 
        DEFAULT 'individual',
    notes TEXT NULL,
    student_notes TEXT NULL,
    counselor_notes TEXT NULL,
    follow_up_required BOOLEAN DEFAULT FALSE,
    follow_up_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (counselor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_student_id (student_id),
    INDEX idx_counselor_id (counselor_id),
    INDEX idx_appointment_date (appointment_date),
    INDEX idx_status (status),
    UNIQUE KEY unique_appointment (counselor_id, appointment_date, appointment_time)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SAMPLE DATA
-- =====================================================

-- Insert sample users (password: password123)
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
-- VERIFICATION
-- =====================================================
SELECT 'Database Setup Complete!' as status;
SELECT 'Tables Created:' as info;
SHOW TABLES;
SELECT 'Sample Users:' as info;
SELECT id, name, email, role FROM users;
SELECT 'Sample Appointments:' as info;
SELECT id, student_id, counselor_id, appointment_date, appointment_time, status FROM appointments;
