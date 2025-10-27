<?php 
// Include the database connection
include('partials/main.php'); 

// Check if the fee ID is set
if (isset($_GET['id'])) {
    // Sanitize the fee ID
    $fee_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Debug: Check if the fee ID is valid
    // echo "Fee ID: " . $fee_id; // Remove after testing

    // Check if the fee exists before deleting
    $check_query = "SELECT id FROM tbl_registration_fees WHERE id = '$fee_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Delete the fee with the specific fee_id
        $delete_query = "DELETE FROM tbl_registration_fees WHERE id = '$fee_id'";

        if (mysqli_query($conn, $delete_query)) {
            header("Location: manage-registrationFee.php?status=success&action=delete_fee");
        } else {
            echo "Error deleting record: " . mysqli_error($conn); // Show error message
        }
    } else {
        // Redirect if the fee is not found
        echo "Fee not found.";
        header("Location: manage-registrationFee.php?status=error&action=delete_fee");
    }
} else {
    // Redirect to the manage-registrationFee page if no id is passed
    header("Location: manage-registrationFee.php");
}
?>
