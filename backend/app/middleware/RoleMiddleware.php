<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Role-based Authorization Middleware
 * Checks if user has required role
 */
class RoleMiddleware {
    private $requiredRole;

    public function __construct($requiredRole) {
        $this->requiredRole = $requiredRole;
    }

    public function handle($request, $next) {
        if ($request->user->role !== $this->requiredRole) {
            return $this->json(['error' => 'Forbidden'], 403);
        }
        return $next($request);
    }

    protected function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
