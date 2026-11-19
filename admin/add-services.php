<?php
include('../config/constant.php');
include('partials/login-check.php');

$cat_msg = '';
$service_msg = '';

// Handle Add Category
if (isset($_POST['submit-category'])) {
    $category_name = trim($_POST['category_name'] ?? '');
    if (!empty($category_name)) {
        $check = db_fetch_one($conn, "SELECT id FROM tbl_category WHERE category_name = ?", [$category_name], "s");
        if ($check) {
            $cat_msg = "Category '{$category_name}' already exists.";
        } else {
            db_execute($conn, "INSERT INTO tbl_category (category_name) VALUES (?)", [$category_name], "s");
            header("Location: add-services.php?status=success&action=category_added");
            exit();
        }
    }
}

// Handle Add Service Type
if (isset($_POST['submit-service'])) {
    $category_id = intval($_POST['category_id'] ?? 0);
    $service_type = trim($_POST['service_type'] ?? '');
    $about_service = trim($_POST['about_service'] ?? '');
    $booking_price = floatval($_POST['booking_price'] ?? 0.00);

    // Handle Image Upload
    $image_name = "";
    if (isset($_FILES['service_image']['name']) && $_FILES['service_image']['name'] != "") {
        $ext = pathinfo($_FILES['service_image']['name'], PATHINFO_EXTENSION);
        $image_name = "Service_" . rand(1000, 9999) . "." . $ext;
        $source = $_FILES['service_image']['tmp_name'];
        $dest = "../images/service_images/" . $image_name;
        move_uploaded_file($source, $dest);
    }

    if (!empty($service_type) && $category_id > 0) {
        $check = db_fetch_one($conn, "SELECT id FROM tbl_service WHERE service_type = ? AND category_id = ?", [$service_type, $category_id], "si");
        if ($check) {
            $service_msg = "This service type already exists under this trade category.";
        } else {
            db_execute($conn, "INSERT INTO tbl_service (category_id, service_type, about_service, booking_price, service_image) VALUES (?, ?, ?, ?, ?)", [$category_id, $service_type, $about_service, $booking_price, $image_name], "issds");
            header("Location: manage-services.php?status=success&action=service_added");
            exit();
        }
    } else {
        $service_msg = "Please select a category and specify service title.";
    }
}

// Fetch categories for dropdown
$categories = db_fetch_all($conn, "SELECT * FROM tbl_category ORDER BY category_name ASC");

include('partials/main.php'); 
?>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 28px; max-width: 1100px; margin: 0 auto;">
    <!-- Form 1: Add New Trade Category -->
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">1. Add Trade Category</h3>
                <p style="color: var(--fn-text-muted); font-size: 13.5px; margin: 0;">Create a broader category (e.g. Electrical, Carpentry).</p>
            </div>
        </div>

        <?php if (!empty($cat_msg)): ?>
            <div class="alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($cat_msg); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['action']) && $_GET['action'] == 'category_added'): ?>
            <div class="alert-success"><i class="fa-solid fa-check"></i> Category registered successfully!</div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="category_name">Category Title</label>
                <input type="text" id="category_name" name="category_name" placeholder="e.g. Solar & Renewable Energy" required />
            </div>

            <button type="submit" name="submit-category" class="btn-primary" style="margin-top: 10px;">
                <i class="fa-solid fa-folder-plus"></i> Save Category
            </button>
        </form>
    </div>

    <!-- Form 2: Add New Service Item -->
    <div class="admin-card">
        <div class="admin-card-header">
            <div>
                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">2. Add Service Item</h3>
                <p style="color: var(--fn-text-muted); font-size: 13.5px; margin: 0;">Specify a bookable service with upfront booking price.</p>
            </div>
        </div>

        <?php if (!empty($service_msg)): ?>
            <div class="alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($service_msg); ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="category_id">Parent Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="service_type">Service Title</label>
                <input type="text" id="service_type" name="service_type" placeholder="e.g. AC Inverter Servicing & Gas Refill" required />
            </div>

            <div class="form-group">
                <label for="booking_price">Base Booking Price (LKR)</label>
                <input type="number" id="booking_price" name="booking_price" placeholder="3500.00" step="0.01" min="0" required />
            </div>

            <div class="form-group">
                <label for="about_service">Service Description</label>
                <textarea id="about_service" name="about_service" placeholder="Detailed description of what is included in this service..." rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="service_image">Service Visual Image</label>
                <input type="file" id="service_image" name="service_image" accept="image/*" />
            </div>

            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" name="submit-service" class="btn-primary">
                    <i class="fa-solid fa-plus"></i> Publish Service
                </button>
                <a href="manage-services.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php 
include('partials/footer.php'); 
?>
