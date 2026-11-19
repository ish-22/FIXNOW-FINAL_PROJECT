<?php

// Start session safely if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define constants safely to prevent multiple definition warnings
if (!defined('SITEURL')) {
    define('SITEURL', 'http://localhost/fixnow/');
    define('LOCALHOST', 'localhost');
    define('DB_USERNAME', 'fixnow');
    define('DB_PASSWORD', 'mysql');
    define('DB_NAME', 'fixnow');
}

// Establish database connection if not already connected
if (!isset($conn) || !($conn instanceof mysqli)) {
    $conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if (!$conn) {
        header('Location: ' . SITEURL . 'error.php?message=database_connection_failed');
        exit(); 
    }
}

// Include parameterized DB helper functions
require_once __DIR__ . '/db_helper.php';
