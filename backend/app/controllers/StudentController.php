<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Student Controller
 * Handles student-specific operations
 */
class StudentController {
    public function dashboard() {
        session_start();
        
        // Check if user is authenticated and is a student
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: /login');
            exit;
        }
        
        // Load student dashboard view
        include APP_DIR . 'views/student_dashboard.php';
    }

    public function profile() {
        session_start();
        $userModel = new User();
        $profile = $userModel->find($_SESSION['user_id']);
        return $this->json($profile);
    }

    private function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
