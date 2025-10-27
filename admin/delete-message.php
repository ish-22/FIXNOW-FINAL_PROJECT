<?php 
// Include the database connection
include('partials/main.php'); 

// Check if the contact_id is set
if (isset($_GET['id'])) {
    // Get the contact_id from the URL
    $contact_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Delete the message and reply from the tbl_contact table
    $delete_query = "UPDATE tbl_contact SET reply = NULL WHERE id = '$contact_id'";

    // Execute the query
    if (mysqli_query($conn, $delete_query)) {
        // Redirect to the manage-question page with success message
        header("Location: manage-question.php?status=success&action=delete_message");
    } else {
        // If there's an error, redirect with an error message
        header("Location: manage-question.php?status=error&action=delete_message");
    }
} else {
    // Redirect to the manage-question page if no contact_id is passed
    header("Location: manage-question.php");
}
?>
