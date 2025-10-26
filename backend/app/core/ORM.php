<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * Lightweight ORM for PDO database operations
 */
class ORM {
    private static $pdo = null;

    public static function getConnection() {
        if (!self::$pdo) {
            // Load database configuration
            require_once APP_DIR . 'config/database.php';
            
            $config = $database['main'];
            $dsn = "mysql:host=" . $config['hostname'] . ";dbname=" . $config['database'] . ";charset=" . $config['charset'];
            self::$pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$pdo;
    }
}
