<?php
/**
 * LavaLust Router for PHP Built-in Server
 * This file handles routing for the PHP development server
 */

// Check if the request is for a file that exists
$requestUri = $_SERVER['REQUEST_URI'];
$path = parse_url($requestUri, PHP_URL_PATH);

// Remove query string from path
$path = strtok($path, '?');

// If it's a file that exists, serve it directly
if ($path !== '/' && file_exists(__DIR__ . $path)) {
    return false; // Let PHP serve the file
}

// For all other requests, route through index.php
require_once __DIR__ . '/index.php';
