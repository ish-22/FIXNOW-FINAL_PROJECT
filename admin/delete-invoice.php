<?php 
include('../config/constant.php');
include('partials/login-check.php');

if (isset($_GET['id'])) {
    $invoice_id = intval($_GET['id']);

    $deleted = db_execute($conn, "DELETE FROM tbl_invoice WHERE id = ?", [$invoice_id], "i");

    if ($deleted) {
        header("Location: manage-invoice.php?status=success&action=delete_invoice");
    } else {
        header("Location: manage-invoice.php?status=error&action=delete_invoice");
    }
    exit();
} else {
    header("Location: manage-invoice.php?status=error&action=invalid_id");
    exit();
}
?>
