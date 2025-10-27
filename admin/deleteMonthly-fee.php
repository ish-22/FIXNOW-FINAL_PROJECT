<?php
// Include the database connection
include('partials/main.php');

// Check if the delete request is valid
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Prepare the SQL query to delete the payment fee
    $delete_query = "DELETE FROM tbl_payment WHERE id = '$id'";

    // Execute the query
    if (mysqli_query($conn, $delete_query)) {
        // Redirect back to the manage payment fees page with success message
        echo "<script>
                alert('Payment fee deleted successfully!');
                window.location.href = 'manage-payment-fees.php';
              </script>";
    } else {
        // Handle deletion failure
        echo "<script>alert('Error deleting payment fee: " . mysqli_error($conn) . "');</script>";
        echo "<script>window.location.href = 'manage-payment-fees.php';</script>";
    }
} else {
    // If no ID is provided, redirect back to the manage page
    echo "<script>window.location.href = 'manage-payment-fees.php';</script>";
}
?>
