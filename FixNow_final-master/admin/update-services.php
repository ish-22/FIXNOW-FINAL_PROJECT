<?php
include('partials/main.php');

// Fetch the service id from the URL
if (isset($_GET['id'])) {
    $service_id = $_GET['id'];

    // Query to get the service details by service_id
    $sql = "SELECT s.id as service_id, s.service_type, s.booking_price, s.service_image, c.category_name, s.category_id 
            FROM tbl_service s 
            INNER JOIN tbl_category c ON s.category_id = c.id
            WHERE s.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $service = $result->fetch_assoc();
        $service_type = $service['service_type'];
        $category_name = $service['category_name'];
        $category_id = $service['category_id'];
        $booking_price = $service['booking_price'];
        $service_image = $service['service_image'];  // Existing image name
    } else {
        echo "<p>Service not found.</p>";
        exit;
    }
}

// Handle the update process when the form is submitted
if (isset($_POST['submit-update'])) {
    $updated_service_type = $_POST['service_type'];
    $updated_category_id = $_POST['category_id'];
    $updated_booking_price = $_POST['booking_price'];
    $updated_image_name = $service_image;  // Retain current image name by default

    // Handle the image upload
    if (isset($_FILES['service_image']['name']) && $_FILES['service_image']['name'] != "") {
        // A new image is uploaded
        $image_name = $_FILES['service_image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $updated_image_name = "Service_" . rand(1000, 9999) . "." . $ext;  // New image name
        $source_path = $_FILES['service_image']['tmp_name'];
        $destination_path = "../images/service_images/" . $updated_image_name;

        // Move uploaded image to the destination folder
        if (!move_uploaded_file($source_path, $destination_path)) {
            $_SESSION['upload_error'] = "Failed to upload image. Please try again.";
        }
    }

    // Update the service in tbl_service
    $sql_update = "UPDATE tbl_service SET service_type = ?, category_id = ?, booking_price = ?, service_image = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sidsi", $updated_service_type, $updated_category_id, $updated_booking_price, $updated_image_name, $service_id);

    if ($stmt_update->execute()) {
        header("Location: " . SITEURL . 'admin/manage-services.php?status=success&action=update-service');
    } else {
        header("Location: " . SITEURL . 'admin/manage-services.php?status=error&action=update-service');
    }
}
?>

<div class="main-content">
    <div class="wrapper">
      
        <br />

        <!-- Update service form -->
        <form action="" method="POST" enctype="multipart/form-data" class="add-service-form">
            <h2 class="text_center">Update Service Category/Type</h2>

            <!-- Dropdown for selecting category -->
            <div class="input-field">
                <label for="category-select">Select Category:</label>
                <select id="category-select" name="category_id" required>
                    <?php
                    // Fetch categories from tbl_category
                    $sql = "SELECT * FROM tbl_category";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $category_result = $stmt->get_result();

                    while ($row = $category_result->fetch_assoc()) {
                        $selected = ($category_id == $row['id']) ? 'selected' : '';
                        echo "<option value='{$row['id']}' $selected>{$row['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Service Type -->
            <div class="input-field">
                <label for="service-type">Service Type:</label>
                <input type="text" id="service-type" name="service_type" value="<?php echo $service_type; ?>" required>
            </div>

            <!-- Booking price -->
            <div class="input-field">
                <label for="booking-price">Booking Price (in rupees):</label>
                <input type="number" id="booking-price" name="booking_price" value="<?php echo $booking_price; ?>" min="0" step="any" required>
            </div>

            <!-- Image upload -->
            <div class="input-field">
                <label for="service-image">Upload Image:</label>
                <input type="file" id="service-image" name="service_image" />
                <?php if ($service_image) { ?>
                    <div class="image-preview">
                        <img src="../images/service_images/<?php echo $service_image; ?>" alt="Service Image" id="preview-image" />
                    </div>
                <?php } ?>
            </div>

            <!-- Submit Button -->
            <div class="input-field">
                <button type="submit" name="submit-update" class="btn-primary">Update Service</button>
            </div>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
