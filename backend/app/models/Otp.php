<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * OTP Model for handling one-time passwords
 */
class Otp {
    private $db;

    public function __construct() {
        $this->db = ORM::getConnection();
    }

    /**
     * Generate a 6-digit OTP code
     */
    public function generateCode() {
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new OTP record
     */
    public function create($userId, $code, $expiresAt) {
        // Clean up any existing OTPs for this user
        $this->cleanup($userId);
        
        $stmt = $this->db->prepare(
            "INSERT INTO otps (user_id, code, expires_at) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$userId, $code, $expiresAt]);
    }

    /**
     * Verify OTP code
     */
    public function verify($userId, $code) {
        $stmt = $this->db->prepare(
            "SELECT * FROM otps WHERE user_id = ? AND code = ? AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1"
        );
        $stmt->execute([$userId, $code]);
        return $stmt->fetch();
    }

    /**
     * Clean up expired OTPs for a user
     */
    public function cleanup($userId) {
        $stmt = $this->db->prepare("DELETE FROM otps WHERE user_id = ?");
        return $stmt->execute([$userId]);
    }

    /**
     * Clean up all expired OTPs
     */
    public function cleanupExpired() {
        $stmt = $this->db->prepare("DELETE FROM otps WHERE expires_at < NOW()");
        return $stmt->execute();
    }
}
