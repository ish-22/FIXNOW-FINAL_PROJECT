<?php 
include('../config/constant.php');
include('partials/login-check.php');

if (isset($_GET['id'])) {
    $contact_id = intval($_GET['id']);
    
    $deleted = db_execute($conn, "DELETE FROM tbl_contact WHERE id = ?", [$contact_id], "i");

    if ($deleted) {
        header("Location: manage-question.php?status=success&action=delete_message");
    } else {
        header("Location: manage-question.php?status=error&action=delete_message");
    }
    exit();
} else {
    header("Location: manage-question.php");
    exit();
}
?>
