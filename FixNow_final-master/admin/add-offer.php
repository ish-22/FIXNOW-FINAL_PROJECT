<?php
include('../config/constant.php');
include('partials/login-check.php');

$error_msg = '';

// Handle Add Offer Form Submission
if (isset($_POST['submit-offer'])) {
    $title = trim($_POST['title'] ?? '');
    $category_id = intval($_POST['offer_category_id'] ?? 0);
    $service_id = intval($_POST['offer_type_id'] ?? 0);
    $duration = trim($_POST['duration'] ?? '');
    $current_price = floatval($_POST['current_price'] ?? 0.00);
    $discount_rate = floatval($_POST['discount_rate'] ?? 0.00);
    $discounted_price = floatval($_POST['discounted_price'] ?? 0.00);
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['offer_status'] ?? 'active';

    // Handle Image Upload
    $offer_image = '';
    if (isset($_FILES['offer_image']['name']) && $_FILES['offer_image']['name'] != '') {
        $ext = pathinfo($_FILES['offer_image']['name'], PATHINFO_EXTENSION);
        $offer_image = "Offer_" . rand(1000, 9999) . "." . $ext;
        $dest = "../images/offer_images/" . $offer_image;
        move_uploaded_file($_FILES['offer_image']['tmp_name'], $dest);
    }

    if (!empty($title) && $category_id > 0 && $current_price > 0) {
        $inserted = db_execute(
            $conn,
            "INSERT INTO tbl_offer (title, offer_category_id, offer_type_id, duration, current_price, discount_rate, discounted_price, offer_image, description, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$title, $category_id, $service_id, $duration, $current_price, $discount_rate, $discounted_price, $offer_image, $description, $status],
            "siisdddsss"
        );

        if ($inserted) {
            header("Location: manage-offers.php?status=success&action=add-offer");
            exit();
        } else {
            $error_msg = "Database error adding offer.";
        }
    } else {
        $error_msg = "Please fill out all required offer fields.";
    }
}

// Fetch categories for dropdown
$categories = db_fetch_all($conn, "SELECT * FROM tbl_category ORDER BY category_name ASC");

include('partials/main.php'); 
?>

<div class="admin-card" style="max-width: 720px; margin: 0 auto;">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Create Special Promotion</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Publish seasonal discounts, bundle rates, and promotional cards for homepage showcases.</p>
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
            <input type="text" id="title" name="title" placeholder="e.g. 20% Off Monsoon Deep Cleaning Package" required />
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label for="category-select">Trade Category</label>
                <select id="category-select" name="offer_category_id" onchange="fetchServices(this.value)" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo $cat['id']; ?>"><?php echo htmlspecialchars($cat['category_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="service-type">Applicable Service</label>
                <select id="service-type" name="offer_type_id" onchange="fetchServicePrice()" required>
                    <option value="">Select Category First</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="duration">Promotion Validity / Duration</label>
            <input type="text" id="duration" name="duration" placeholder="e.g. Valid till End of Month / Next 14 Days" required />
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label for="current_price">Standard Price (Rs.)</label>
                <input type="number" id="current_price" name="current_price" placeholder="5000.00" step="0.01" min="0" oninput="calculateDiscountedPrice()" required />
            </div>

            <div class="form-group">
                <label for="discount_rate">Discount Rate (%)</label>
                <input type="number" id="discount_rate" name="discount_rate" placeholder="20" step="0.01" min="0" max="100" oninput="calculateDiscountedPrice()" required />
            </div>

            <div class="form-group">
                <label for="discounted_price">Final Promo Price (Rs.)</label>
                <input type="number" id="discounted_price" name="discounted_price" placeholder="4000.00" step="0.01" min="0" required />
            </div>
        </div>

        <div class="form-group">
            <label for="description">Detailed Description</label>
            <textarea id="description" name="description" placeholder="Specify all inclusions and discount terms..." rows="3" required></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label for="offer_image">Promotional Banner Visual</label>
                <input type="file" id="offer_image" name="offer_image" accept="image/*" required />
            </div>

            <div class="form-group">
                <label for="offer_status">Campaign Status</label>
                <select id="offer_status" name="offer_status" required>
                    <option value="active">Active (Visible)</option>
                    <option value="inactive">Inactive (Hidden)</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 24px; display: flex; gap: 12px;">
            <button type="submit" name="submit-offer" class="btn-primary">
                <i class="fa-solid fa-bullhorn"></i> Launch Promotion
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
    formData.append('offer_category_id', categoryId);

    fetch('fetch_services.php', {
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
