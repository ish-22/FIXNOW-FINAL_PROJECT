<?php
include('../config/constant.php');
include('partials/login-check.php');

if (isset($_GET['id'])) {
    $hack_id = intval($_GET['id']);

    $deleted = db_execute($conn, "DELETE FROM tbl_dailyhacks WHERE id = ?", [$hack_id], "i");

    if ($deleted) {
        header("Location: manage-dailyHacks.php?status=success&action=delete-hack");
    } else {
        header("Location: manage-dailyHacks.php?status=error&action=delete-hack");
    }
    exit();
} else {
    header("Location: manage-dailyHacks.php?status=error&action=no-id");
    exit();
}
?>
