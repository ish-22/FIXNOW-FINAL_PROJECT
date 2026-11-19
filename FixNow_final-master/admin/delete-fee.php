<?php
include('../config/constant.php');
include('partials/login-check.php');

if (isset($_GET['id'])) {
    $fee_id = intval($_GET['id']);
    $deleted = db_execute($conn, "DELETE FROM tbl_registration_fees WHERE id = ?", [$fee_id], "i");

    if ($deleted) {
        header("Location: manage-invoice.php?status=success&action=delete_fee");
    } else {
        header("Location: manage-invoice.php?status=error&action=delete_fee");
    }
    exit();
} else {
    header("Location: manage-invoice.php");
    exit();
}
?>
