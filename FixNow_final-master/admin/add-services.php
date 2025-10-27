<?php
include('partials/main.php'); 

// Handle Add Service Category
if (isset($_POST['submit'])) {
    $category_name = trim($_POST['category_name']);

    // Check if category already exists
    $check_sql = "SELECT * FROM tbl_category WHERE category_name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $category_name);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Category already exists
        header("Location: " . SITEURL . 'admin/add-services.php?status=exists&action=add-category');
        exit();
    } else {
        // Insert new category
        $sql = "INSERT INTO tbl_category (category_name) VALUES (?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $category_name);

        if ($stmt->execute()) {
            header("Location: " . SITEURL . 'admin/add-services.php?status=success&action=add-category');
        } else {
            header("Location: " . SITEURL . 'admin/add-services.php?status=error&action=add-category');
        }
    }
}

// Handle Add Service Type
if (isset($_POST['submit-service'])) {
    $category_id = $_POST['category-select'];
    $service_type = trim($_POST['service_type']);
    $about_service = $_POST['about_service'];
    $booking_price = $_POST['booking_price'];

    // Handle image upload
    if (isset($_FILES['service_image']['name']) && $_FILES['service_image']['name'] != "") {
        $image_name = $_FILES['service_image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);
        $image_name = "Service_" . rand(1000, 9999) . "." . $ext;
        $source_path = $_FILES['service_image']['tmp_name'];
        $destination_path = "../images/service_images/" . $image_name;

        if (!move_uploaded_file($source_path, $destination_path)) {
            $_SESSION['upload_error'] = "Failed to upload image.";
            $image_name = "";
        }
    } else {
        $image_name = "";
    }

    // Check if service type already exists under same category
    $check_sql = "SELECT * FROM tbl_service WHERE service_type = ? AND category_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("si", $service_type, $category_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Service type already exists
        header("Location: " . SITEURL . 'admin/manage-services.php?status=exists&action=add-service');
        exit();
    } else {
        // Insert service
        $sql = "INSERT INTO tbl_service (service_type, about_service, booking_price, category_id, service_image) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdis", $service_type, $about_service, $booking_price, $category_id, $image_name);

        if ($stmt->execute()) {
            header("Location: " . SITEURL . 'admin/manage-services.php?status=success&action=add-service');
        } else {
            header("Location: " . SITEURL . 'admin/manage-services.php?status=error&action=add-service');
        }
    }
}
?>

<div class="main-content">
    <div class="wrapper">

        <!-- Add Category Form -->
        <form class="add-service-form" action="" method="POST">
            <h2 class="main-welcome">Add Service Category</h2>
            <div class="form-group">
                <label for="category-name">Type Category Name:</label>
                <input type="text" id="category-name" name="category_name" placeholder="Enter Category Name" required>
            </div>
            <div class="form-group">
                <input type="submit" name="submit" value="Add Category" class="btn-primary">
            </div>
        </form>

        <br />

        <!-- Add Service Type Form -->
        <form class="add-service-type-form" action="" method="POST" enctype="multipart/form-data">
            <h2>Add Service Type</h2>
            <div class="form-group">
                <label for="category-select">Select Category:</label>
                <select id="category-select" name="category-select" required>
                    <?php
                    $sql = "SELECT * FROM tbl_category";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['category_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="service-type">Service Type:</label>
                <input type="text" id="service-type" name="service_type" placeholder="Enter Service Type" required>
            </div>

            <div class="form-group">
                <label for="about-service">About Service:</label>
                <textarea id="about-service" name="about_service" placeholder="Describe the service" rows="3" cols="80" required></textarea>
            </div>

            <div class="form-group">
                <label for="booking-price">Booking Price (in rupees):</label>
                <input type="number" id="booking-price" name="booking_price" placeholder="Enter Booking Price" min="0" step="any" required>
            </div>

            <div class="form-group">
                <label for="service-image">Add Image:</label>
                <input type="file" id="service-image" name="service_image" accept="image/*" required>
                <div class="image-preview">
                    <img src="../images/man1.jpg" alt="Service Image" id="preview-image">
                </div>
            </div>

            <div class="form-group">
                <input type="submit" name="submit-service" value="Add Service" class="btn-primary">
            </div>
        </form>

    </div>
</div>

<?php include('partials/footer.php'); ?>
