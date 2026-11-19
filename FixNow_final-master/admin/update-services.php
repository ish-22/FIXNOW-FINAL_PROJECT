<?php
include('../config/constant.php');
include('partials/login-check.php');

if (!isset($_GET['id'])) {
    header("Location: manage-services.php");
    exit();
}

$service_id = intval($_GET['id']);
$service = db_fetch_one($conn, "SELECT * FROM tbl_service WHERE id = ?", [$service_id], "i");

if (!$service) {
    header("Location: manage-services.php");
    exit();
}

$error_msg = '';

// Handle update POST
if (isset($_POST['submit-update'])) {
    $category_id = intval($_POST['category_id'] ?? 0);
    $service_type = trim($_POST['service_type'] ?? '');
    $about_service = trim($_POST['about_service'] ?? '');
    $booking_price = floatval($_POST['booking_price'] ?? 0.00);
    $image_name = $service['service_image'];

    // Handle Image Upload if new image selected
    if (isset($_FILES['service_image']['name']) && $_FILES['service_image']['name'] != "") {
        $ext = pathinfo($_FILES['service_image']['name'], PATHINFO_EXTENSION);
        $image_name = "Service_" . rand(1000, 9999) . "." . $ext;
        $source = $_FILES['service_image']['tmp_name'];
        $dest = "../images/service_images/" . $image_name;
        move_uploaded_file($source, $dest);
    }

    if (!empty($service_type) && $category_id > 0) {
        $updated = db_execute($conn, "UPDATE tbl_service SET category_id = ?, service_type = ?, about_service = ?, booking_price = ?, service_image = ? WHERE id = ?", [$category_id, $service_type, $about_service, $booking_price, $image_name, $service_id], "issdsi");

        if ($updated) {
            header("Location: manage-services.php?status=success&action=update-service");
            exit();
        } else {
            $error_msg = "Failed to update service details.";
        }
    } else {
        $error_msg = "Please fill in all required fields.";
    }
}

// Fetch categories
$categories = db_fetch_all($conn, "SELECT * FROM tbl_category ORDER BY category_name ASC");

include('partials/main.php');
?>

<div class="admin-card" style="max-width: 680px; margin: 0 auto;">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Edit Service Item</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Update service specifications, category, and pricing.</p>
        </div>
        <a href="manage-services.php" class="btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Services
        </a>
    </div>

    <?php if (!empty($error_msg)): ?>
        <div class="alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="category_id">Parent Category</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>" <?php echo ($service['category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="service_type">Service Title</label>
            <input type="text" id="service_type" name="service_type" value="<?php echo htmlspecialchars($service['service_type']); ?>" required />
        </div>

        <div class="form-group">
            <label for="booking_price">Base Booking Price (LKR)</label>
            <input type="number" id="booking_price" name="booking_price" value="<?php echo htmlspecialchars($service['booking_price']); ?>" step="0.01" min="0" required />
        </div>

        <div class="form-group">
            <label for="about_service">Service Description</label>
            <textarea id="about_service" name="about_service" rows="3"><?php echo htmlspecialchars($service['about_service']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Current Visual Image</label>
            <?php if (!empty($service['service_image'])): ?>
                <div style="margin-bottom: 10px;">
                    <img src="../images/service_images/<?php echo $service['service_image']; ?>" alt="" style="width: 100px; height: 75px; object-fit: cover; border-radius: 8px; border: 1px solid var(--fn-border);" onerror="this.style.display='none';">
                </div>
            <?php endif; ?>
            <label for="service_image" style="font-weight: 500; font-size: 13px;">Replace Image (Optional):</label>
            <input type="file" id="service_image" name="service_image" accept="image/*" />
        </div>

        <div style="margin-top: 28px; display: flex; gap: 12px;">
            <button type="submit" name="submit-update" class="btn-primary">
                <i class="fa-solid fa-check"></i> Save Service Changes
            </button>
            <a href="manage-services.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php 
include('partials/footer.php'); 
?>
