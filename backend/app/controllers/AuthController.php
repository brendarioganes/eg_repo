<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Authentication Controller
 * Handles login, register, logout
 */
class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input || empty($input['email']) || empty($input['password']) || empty($input['name']) || empty($input['role'])) {
            return $this->json(['error' => 'Invalid input'], 400);
        }

        if ($this->userModel->findByEmail($input['email'])) {
            return $this->json(['error' => 'Email already exists'], 409);
        }

        $this->userModel->create($input);
        return $this->json(['message' => 'Registered successfully']);
    }

    public function login() {
        $input = json_decode(file_get_contents('php://input'), true);
        $user = $this->userModel->findByEmail($input['email'] ?? '');

        if ($user && password_verify($input['password'] ?? '', $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            return $this->json([
                'message' => 'Login successful',
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);
        }
        return $this->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout() {
        session_start();
        session_destroy();
        return $this->json(['message' => 'Logged out']);
    }

    private function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
