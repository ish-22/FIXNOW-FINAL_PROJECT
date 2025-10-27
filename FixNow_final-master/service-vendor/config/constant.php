<?php

session_start();
// Define constants for reuse
define('SITEURL','http://localhost/fixnow/');
define('LOCALHOST', 'localhost');
define('DB_USERNAME', 'fixnow');
define('DB_PASSWORD', 'mysql');
define('DB_NAME', 'fixnow');

// Establish a connection to the database
$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check if the connection was successful
if (!$conn) {
    // Redirect to an error page if connection fails
    header('Location: '.SITEURL.'error.php?message=database_connection_failed');
    exit(); // Ensure no further code is executed
}



