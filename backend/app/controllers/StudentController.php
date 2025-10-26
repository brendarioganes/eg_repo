<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Student Controller
 * Handles student-specific operations
 */
class StudentController {
    public function dashboard() {
        session_start();
        return $this->json([
            'message' => 'Welcome to Student Dashboard',
            'user' => [
                'id' => $_SESSION['user_id'],
                'name' => $_SESSION['name'],
                'role' => $_SESSION['role']
            ]
        ]);
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
