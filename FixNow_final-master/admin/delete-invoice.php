<?php
// Include the database connection file
include('partials/main.php');

// Check if an invoice ID is provided in the URL
if (isset($_GET['id'])) {
    // Get the invoice ID from the URL
    $invoice_id = mysqli_real_escape_string($conn, $_GET['id']);

    // SQL query to delete the invoice
    $delete_query = "DELETE FROM tbl_invoice WHERE id = '$invoice_id'";

    // Execute the query
    if (mysqli_query($conn, $delete_query)) {
        // Redirect to the Manage Invoices page with success message
        header("Location: manage-invoice.php?status=success&action=delete_invoice");
    } else {
        // If deletion fails, redirect with error message
        header("Location: manage-invoice.php?status=error&action=delete_invoice");
    }
} else {
    // Redirect if no ID is passed in the URL
    header("Location: manage-invoices.php?status=error&action=invalid_id");
}
?>
