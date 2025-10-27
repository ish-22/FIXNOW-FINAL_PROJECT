<?php
include('./config/constant.php');

session_start(); 

// Unset only customer session variables
unset($_SESSION['customer_id']);
unset($_SESSION['cus_name']);
unset($_SESSION['cus_email']);

// Redirect to customer home page after logout
header("Location: " . SITEURL . "index.php");
exit();
?>
