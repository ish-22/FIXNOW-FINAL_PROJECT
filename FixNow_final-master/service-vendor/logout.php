<?php
include('../config/constant.php'); 


// Unset only vendor session variables
unset($_SESSION['vendor_id']);
unset($_SESSION['vendor_name']);
unset($_SESSION['vendor_email']);

// Redirect to vendor home page after logout
header("Location: " . SITEURL . "service-vendor/index.php");
exit();
?>
