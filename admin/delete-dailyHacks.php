<?php
// Include the database connection
include('partials/main.php');

// Check if 'id' is passed in the URL to delete a specific hack
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // SQL query to delete the hack from the database
    $sql = "DELETE FROM tbl_dailyhacks WHERE id = ?";

    // Prepare and execute the query
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Redirect to the manage page with success message
            header("Location: manage-dailyHacks.php?status=success&action=delete-hack");
        } else {
            // Redirect to the manage page with error message
            header("Location: manage-dailyHacks.php?status=error&action=delete-hack");
        }

        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // If 'id' is not provided in the URL, redirect with an error message
    header("Location: manage-dailyHacks.php?status=error&action=no-id");
    exit();
}
?>
