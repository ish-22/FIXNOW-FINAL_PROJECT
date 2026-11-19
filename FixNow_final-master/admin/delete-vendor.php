<?php 
include('../config/constant.php'); 
include('partials/login-check.php');

if (isset($_GET['id'])) {
    $vendor_id = intval($_GET['id']);
    
    // Delete service provider with parameterized statement
    $deleted = db_execute($conn, "DELETE FROM tbl_service_providers WHERE id = ?", [$vendor_id], "i");

    if ($deleted) {
        header("Location: manage-vendor.php?status=success&action=delete_vendor");
    } else {
        header("Location: manage-vendor.php?status=error&action=delete_vendor");
    }
    exit();
} else {
    header("Location: manage-vendor.php");
    exit();
}
?>
