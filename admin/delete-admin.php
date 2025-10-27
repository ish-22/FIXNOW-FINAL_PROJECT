<?php
// Include the main configuration file for database connection
include('partials/main.php');

// Check if the 'id' parameter is passed via URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Get the admin ID from the URL

    // SQL query to delete the admin with the specific ID
    $sql = "DELETE FROM tbl_admin WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id); // Bind the ID parameter to the query

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the manage user page with a success message
        header("Location: manage-user.php?status=success&action=delete");
    } else {
        // Redirect to the manage user page with an error message
        header("Location: manage-user.php?status=error&action=delete");
    }
} else {
    // If no ID is passed, redirect back to the manage user page
    header('Location: manage-user.php');
    exit();
}
?>
