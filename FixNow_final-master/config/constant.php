<?php

session_start();
// Define constants for reuse
define('SITEURL','http://localhost/fixnow/');
define('LOCALHOST', 'localhost');
define('DB_USERNAME', 'fixnow');
define('DB_PASSWORD', 'mysql');
define('DB_NAME', 'fixnow');


$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (!$conn) {
 
    header('Location: '.SITEURL.'error.php?message=database_connection_failed');
    exit(); 
}



