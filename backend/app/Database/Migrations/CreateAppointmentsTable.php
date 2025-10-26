<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Migration for creating appointments table
 */
class CreateAppointmentsTable {
    
    public function up() {
        $db = ORM::getConnection();
        
        $sql = "CREATE TABLE IF NOT EXISTS appointments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL,
            counselor_id INT NOT NULL,
            date DATE NOT NULL,
            time TIME NOT NULL,
            status ENUM('pending', 'approved', 'completed', 'canceled') NOT NULL DEFAULT 'pending',
            notes TEXT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (counselor_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $db->exec($sql);
    }
    
    public function down() {
        $db = ORM::getConnection();
        $db->exec("DROP TABLE IF EXISTS appointments");
    }
}
