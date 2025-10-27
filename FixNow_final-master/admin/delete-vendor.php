<?php 
// Include the database connection
include('partials/main.php'); 

// Check if vendor ID is passed in the URL
if (isset($_GET['id'])) {
    // Get the vendor_id from the URL and sanitize it
    $vendor_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // SQL query to delete the vendor with the specific vendor_id
    $delete_query = "DELETE FROM tbl_service_providers WHERE id = '$vendor_id'";

    if (mysqli_query($conn, $delete_query)) {
        // Redirect to the manage vendors page with a success message
        header("Location: manage-vendors.php?status=success&action=delete_vendor");
    } else {
        // If there's an error, redirect with an error message
        echo "Error deleting record: " . mysqli_error($conn); // Show error message
        header("Location: manage-vendors.php?status=error&action=delete_vendor");
    }
} else {
    // Redirect to the manage vendors page if no id is passed
    header("Location: manage-vendors.php");
}
?>
