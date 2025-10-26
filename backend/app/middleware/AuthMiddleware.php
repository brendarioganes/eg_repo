<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Authentication Middleware
 * Checks if user is logged in
 */
class AuthMiddleware {
    public function handle($request, $next) {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }
        $request->user = (object) $_SESSION;
        return $next($request);
    }

    protected function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
