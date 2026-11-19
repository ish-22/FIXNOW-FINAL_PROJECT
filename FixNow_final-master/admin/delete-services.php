<?php
include('../config/constant.php');
include('partials/login-check.php');

if (isset($_GET['id'])) {
    $service_id = intval($_GET['id']);

    // Fetch image path to remove file
    $service = db_fetch_one($conn, "SELECT service_image FROM tbl_service WHERE id = ?", [$service_id], "i");
    if ($service && !empty($service['service_image'])) {
        $img_path = "../images/service_images/" . $service['service_image'];
        if (file_exists($img_path)) {
            @unlink($img_path);
        }
    }

    $deleted = db_execute($conn, "DELETE FROM tbl_service WHERE id = ?", [$service_id], "i");

    if ($deleted) {
        header("Location: manage-services.php?status=success&action=delete-service");
    } else {
        header("Location: manage-services.php?status=error&action=delete-service");
    }
    exit();
} else {
    header("Location: manage-services.php");
    exit();
}
?>
