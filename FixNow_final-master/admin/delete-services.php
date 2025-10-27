<?php
include('partials/main.php');

// Check if service ID is provided
if (isset($_GET['id'])) {
    $service_id = $_GET['id'];

    // Query to fetch the service details before deletion (optional, if you need to use them)
    $sql = "SELECT service_image FROM tbl_service WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
        $service_image = $service['service_image']; // Store the image name to delete it later

        // Query to delete the service from the database
        $sql_delete = "DELETE FROM tbl_service WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $service_id);

        if ($stmt_delete->execute()) {
            // If service is deleted successfully, delete the associated image from the directory (optional)
            if ($service_image) {
                $image_path = "../images/service_images/" . $service_image;
                if (file_exists($image_path)) {
                    unlink($image_path); // Delete the image file
                }
            }
            // Redirect back to manage services page with success message
            header("Location: " . SITEURL . 'admin/manage-services.php?status=success&action=delete-service');
        } else {
            // If deletion failed, redirect back to manage services page with error message
            header("Location: " . SITEURL . 'admin/manage-services.php?status=error&action=delete-service');
        }
    } else {
        // If the service ID is not found
        echo "<p>Service not found.</p>";
        exit;
    }
} else {
    echo "<p>No service ID provided.</p>";
    exit;
}
?>
