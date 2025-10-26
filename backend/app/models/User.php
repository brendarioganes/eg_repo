<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * User Model for authentication and user management
 */
class User {
    private $db;

    public function __construct() {
        $this->db = ORM::getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create($data) {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)"
            );
            
            $result = $stmt->execute([
                $data['name'],
                $data['email'],
                password_hash($data['password'], PASSWORD_BCRYPT),
                $data['role']
            ]);
            
            if ($result) {
                error_log("User created successfully: " . $data['email']);
                return true;
            } else {
                error_log("Failed to create user: " . json_encode($stmt->errorInfo()));
                return false;
            }
            
        } catch (PDOException $e) {
            error_log("Database error in User::create: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("General error in User::create: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];
        foreach ($data as $k => $v) {
            $fields[] = "$k = ?";
            $values[] = $v;
        }
        $values[] = $id;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
