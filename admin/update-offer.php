<?php
include('../config/constant.php');
include('partials/login-check.php');

if (!isset($_GET['id'])) {
    header("Location: manage-offers.php");
    exit();
}

$offer_id = intval($_GET['id']);
$offer = db_fetch_one($conn, "SELECT * FROM tbl_offer WHERE id = ?", [$offer_id], "i");

if (!$offer) {
    header("Location: manage-offers.php");
    exit();
}

$error_msg = '';

// Handle Update POST
if (isset($_POST['submit-update-offer'])) {
    $title = trim($_POST['title'] ?? '');
    $category_id = intval($_POST['offer_category_id'] ?? 0);
    $service_id = intval($_POST['offer_type_id'] ?? 0);
    $duration = trim($_POST['duration'] ?? '');
    $current_price = floatval($_POST['current_price'] ?? 0.00);
    $discount_rate = floatval($_POST['discount_rate'] ?? 0.00);
    $discounted_price = floatval($_POST['discounted_price'] ?? 0.00);
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['offer_status'] ?? 'active';
    $offer_image = $offer['offer_image'];

    // Handle Image Upload if provided
    if (isset($_FILES['offer_image']['name']) && $_FILES['offer_image']['name'] != '') {
        $ext = pathinfo($_FILES['offer_image']['name'], PATHINFO_EXTENSION);
        $offer_image = "Offer_" . rand(1000, 9999) . "." . $ext;
        $dest = "../images/offer_images/" . $offer_image;
        move_uploaded_file($_FILES['offer_image']['tmp_name'], $dest);
    }

    if (!empty($title) && $category_id > 0 && $current_price > 0) {
        $updated = db_execute(
            $conn,
            "UPDATE tbl_offer SET title = ?, offer_category_id = ?, offer_type_id = ?, duration = ?, current_price = ?, discount_rate = ?, discounted_price = ?, offer_image = ?, description = ?, status = ? WHERE id = ?",
            [$title, $category_id, $service_id, $duration, $current_price, $discount_rate, $discounted_price, $offer_image, $description, $status, $offer_id],
            "siisdddsssi"
        );

        if ($updated) {
            header("Location: manage-offers.php?status=success&action=update-offer");
            exit();
        } else {
            $error_msg = "Database error updating offer.";
        }
    } else {
        $error_msg = "Please fill out all required fields.";
    }
}

// Fetch categories
$categories = db_fetch_all($conn, "SELECT * FROM tbl_category ORDER BY category_name ASC");
$services = db_fetch_all($conn, "SELECT id, service_type, booking_price FROM tbl_service WHERE category_id = ? ORDER BY service_type ASC", [intval($offer['offer_category_id'])], "i");

include('partials/main.php'); 
?>

<div class="admin-card" style="max-width: 720px; margin: 0 auto;">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Edit Special Promotion</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Modify promotional pricing, discount rates, duration, and campaign status.</p>
        </div>
        <a href="manage-offers.php" class="btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Back to Offers
        </a>
    </div>

    <?php if (!empty($error_msg)): ?>
        <div class="alert-danger"><i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($error_msg); ?></div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Offer Title</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($offer['title']); ?>" required />
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label for="category-select">Trade Category</label>
                <select id="category-select" name="offer_category_id" onchange="fetchServices(this.value)" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($offer['offer_category_id'] == $cat['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="service-type">Applicable Service</label>
                <select id="service-type" name="offer_type_id" onchange="fetchServicePrice()" required>
                    <?php foreach ($services as $srv): ?>
                        <option value="<?php echo $srv['id']; ?>" data-price="<?php echo $srv['booking_price']; ?>" <?php echo ($offer['offer_type_id'] == $srv['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($srv['service_type']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="duration">Promotion Validity / Duration</label>
            <input type="text" id="duration" name="duration" value="<?php echo htmlspecialchars($offer['duration']); ?>" required />
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label for="current_price">Standard Price (Rs.)</label>
                <input type="number" id="current_price" name="current_price" value="<?php echo htmlspecialchars($offer['current_price']); ?>" step="0.01" min="0" oninput="calculateDiscountedPrice()" required />
            </div>

            <div class="form-group">
                <label for="discount_rate">Discount Rate (%)</label>
                <input type="number" id="discount_rate" name="discount_rate" value="<?php echo htmlspecialchars($offer['discount_rate']); ?>" step="0.01" min="0" max="100" oninput="calculateDiscountedPrice()" required />
            </div>

            <div class="form-group">
                <label for="discounted_price">Final Promo Price (Rs.)</label>
                <input type="number" id="discounted_price" name="discounted_price" value="<?php echo htmlspecialchars($offer['discounted_price']); ?>" step="0.01" min="0" required />
            </div>
        </div>

        <div class="form-group">
            <label for="description">Detailed Description</label>
            <textarea id="description" name="description" rows="3" required><?php echo htmlspecialchars($offer['description']); ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label for="offer_image">Promotional Banner Visual</label>
                <?php if (!empty($offer['offer_image'])): ?>
                    <div style="margin-bottom: 8px;">
                        <img src="../images/offer_images/<?php echo $offer['offer_image']; ?>" alt="" style="width: 80px; height: 50px; object-fit: cover; border-radius: 6px;" onerror="this.style.display='none';">
                    </div>
                <?php endif; ?>
                <input type="file" id="offer_image" name="offer_image" accept="image/*" />
            </div>

            <div class="form-group">
                <label for="offer_status">Campaign Status</label>
                <select id="offer_status" name="offer_status" required>
                    <option value="active" <?php echo ($offer['status'] == 'active') ? 'selected' : ''; ?>>Active (Visible)</option>
                    <option value="inactive" <?php echo ($offer['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive (Hidden)</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <button type="submit" name="submit-update-offer" class="btn-primary">
                <i class="fa-solid fa-check"></i> Save Promotion Changes
            </button>
            <a href="manage-offers.php" class="btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
function fetchServices(categoryId) {
    const serviceSelect = document.getElementById('service-type');
    serviceSelect.innerHTML = '<option value="">Loading services...</option>';

    if (!categoryId) {
        serviceSelect.innerHTML = '<option value="">Select Category First</option>';
        return;
    }

    const formData = new FormData();
    formData.append('category_id', categoryId);

    fetch('fetch_services_update.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(html => {
        serviceSelect.innerHTML = html;
    })
    .catch(err => {
        serviceSelect.innerHTML = '<option value="">Error loading services</option>';
    });
}

function fetchServicePrice() {
    const serviceSelect = document.getElementById('service-type');
    const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
    const price = selectedOption.getAttribute('data-price');
    if (price) {
        document.getElementById('current_price').value = parseFloat(price).toFixed(2);
        calculateDiscountedPrice();
    }
}

function calculateDiscountedPrice() {
    const cur = parseFloat(document.getElementById('current_price').value) || 0;
    const disc = parseFloat(document.getElementById('discount_rate').value) || 0;
    if (cur > 0 && disc >= 0) {
        const discounted = cur * (1 - (disc / 100));
        document.getElementById('discounted_price').value = discounted.toFixed(2);
    }
}
</script>

<?php 
include('partials/footer.php'); 
?>
