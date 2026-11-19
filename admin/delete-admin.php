<?php 
include('../config/constant.php');
include('partials/login-check.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prevent deleting primary super admin (ID: 1)
    if ($id == 1) {
        header("Location: manage-user.php?status=error&action=cannot_delete_root");
        exit();
    }

    $deleted = db_execute($conn, "DELETE FROM tbl_admin WHERE id = ?", [$id], "i");

    if ($deleted) {
        header("Location: manage-user.php?status=success&action=delete");
    } else {
        header("Location: manage-user.php?status=error&action=delete");
    }
    exit();
} else {
    header('Location: manage-user.php');
    exit();
}
?>
