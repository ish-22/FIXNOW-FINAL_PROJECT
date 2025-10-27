<?php
// Include the database connection file
include('partials/main.php'); 

// Check if offer ID is passed
if (isset($_GET['id'])) {
    $offer_id = $_GET['id'];

    // Check if the offer exists
    $check_query = "SELECT * FROM tbl_offer WHERE id = '$offer_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Offer exists, proceed with deletion
        $delete_query = "DELETE FROM tbl_offer WHERE id = '$offer_id'";
        if (mysqli_query($conn, $delete_query)) {
            // Redirect with success message
            header("Location: " . SITEURL . 'admin/manage-offers.php?status=success&action=delete-offer');
        } else {
            // Redirect with error message
            header("Location: " . SITEURL . 'admin/manage-offers.php?status=error&action=delete-offer');
        }
    } else {
        // Offer not found, redirect with error message
        header("Location: " . SITEURL . 'admin/manage-offers.php?status=error&action=offer-not-found');
    }
} else {
    // No offer ID passed, redirect with error message
    header("Location: " . SITEURL . 'admin/manage-offers.php?status=error&action=invalid-request');
}
?>
