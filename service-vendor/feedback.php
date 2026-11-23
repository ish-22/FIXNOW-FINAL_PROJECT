<?php
include_once('../config/constant.php');

$vendor_id = 0;
if (isset($_GET['vendor_id'])) {
    $vendor_id = intval($_GET['vendor_id']);
} elseif (isset($_GET['service_provider_id'])) {
    $vendor_id = intval($_GET['service_provider_id']);
} elseif (isset($_SESSION['vendor_id'])) {
    $vendor_id = intval($_SESSION['vendor_id']);
}

$vendor = null;
if ($vendor_id > 0) {
    $vendor = db_fetch_one(
        $conn, 
        "SELECT sp.*, s.service_type, t.town_name 
         FROM tbl_service_providers sp 
         LEFT JOIN tbl_service s ON sp.servicetype_id = s.id 
         LEFT JOIN tbl_towns t ON sp.town_id = t.id 
         WHERE sp.id = ?", 
        [$vendor_id], 
        "i"
    );
}

$success_msg = false;
$error_msg = '';

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $feedback = trim($_POST['feedback'] ?? '');
    $rating = intval($_POST['rating'] ?? 5);

    if ($rating < 1) $rating = 1;
    if ($rating > 5) $rating = 5;

    if (!empty($customer_name) && !empty($feedback) && $vendor_id > 0) {
        $inserted = db_execute(
            $conn,
            "INSERT INTO tbl_feedback (vendor_id, customer_name, feedback, rating) VALUES (?, ?, ?, ?)",
            [$vendor_id, $customer_name, $feedback, $rating],
            "issi"
        );
        if ($inserted) {
            $success_msg = true;
        } else {
            $error_msg = "Database error saving feedback. Please try again.";
        }
    } else {
        $error_msg = "Please fill in your name and comments.";
    }
}

// Prefill reviewer name if customer logged in
$prefill_name = '';
if (isset($_SESSION['customer_id'])) {
    $cus = db_fetch_one($conn, "SELECT cus_name FROM tbl_customer WHERE id = ?", [$_SESSION['customer_id']], "i");
    if ($cus) $prefill_name = $cus['cus_name'];
} elseif (isset($_SESSION['vendor_id']) && $vendor) {
    $prefill_name = $vendor['full_name'];
}

// Fetch feedback list & stats
$feedback_list = [];
$avg_rating = 0;
$total_reviews = 0;

if ($vendor_id > 0) {
    $feedback_list = db_fetch_all(
        $conn,
        "SELECT customer_name, feedback, rating, created_at FROM tbl_feedback WHERE vendor_id = ? ORDER BY id DESC",
        [$vendor_id],
        "i"
    );
    $total_reviews = count($feedback_list);
    if ($total_reviews > 0) {
        $sum = 0;
        foreach ($feedback_list as $f) {
            $sum += intval($f['rating']);
        }
        $avg_rating = round($sum / $total_reviews, 1);
    }
}

$current_page = 'vendors.php';
include('./partials/header.php');
?>

<!-- Breadcrumb -->
<div style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 14px 0;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <nav style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: #64748b;">
            <a href="index.php" style="color: #64748b; text-decoration: none;">Portal Home</a>
            <i class="fa-solid fa-chevron-right fa-xs" style="color: #cbd5e1;"></i>
            <a href="vendors.php" style="color: #64748b; text-decoration: none;">Specialists</a>
            <i class="fa-solid fa-chevron-right fa-xs" style="color: #cbd5e1;"></i>
            <?php if ($vendor): ?>
                <a href="vendors-details.php?vendor_id=<?php echo $vendor['id']; ?>" style="color: #64748b; text-decoration: none;">
                    <?php echo htmlspecialchars($vendor['full_name']); ?>
                </a>
                <i class="fa-solid fa-chevron-right fa-xs" style="color: #cbd5e1;"></i>
            <?php endif; ?>
            <span style="color: #0f172a; font-weight: 600;">Reviews &amp; Ratings</span>
        </nav>

        <?php if ($vendor): ?>
            <a href="vendors-details.php?vendor_id=<?php echo $vendor['id']; ?>" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #0284c7; text-decoration: none;">
                <i class="fa-solid fa-arrow-left"></i> Specialist Profile
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if (!$vendor): ?>
    <!-- Vendor Not Found State -->
    <div style="max-width: 600px; margin: 0 auto; padding: 80px 20px; text-align: center;">
        <div style="width: 70px; height: 70px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 26px;">
            <i class="fa-solid fa-user-xmark"></i>
        </div>
        <h2 style="font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Specialist Not Found</h2>
        <p style="color: #64748b; font-size: 14.5px; margin-bottom: 24px;">Please select an active specialist from the directory to view reviews and ratings.</p>
        <a href="vendors.php" style="background: #0284c7; color: #ffffff; text-decoration: none; padding: 10px 24px; font-size: 14px; font-weight: 700; border-radius: 50px;">
            Browse Verified Specialists
        </a>
    </div>
<?php else: 
    $vendor_img = '../images/vendor_images/' . ($vendor['vendor_image'] ?? '');
    $img_src = (!empty($vendor['vendor_image']) && file_exists($vendor_img)) ? $vendor_img : '../images/vendor_images/default.jpg';
?>

<!-- Specialist Header Card -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 40px 20px 25px 20px; border-bottom: 1px solid #e2e8f0;">
    <div style="max-width: 1100px; margin: 0 auto;">
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 28px; box-shadow: 0 4px 18px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 24px;">
            <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                <img src="<?php echo htmlspecialchars($img_src); ?>" alt="<?php echo htmlspecialchars($vendor['full_name']); ?>" 
                     style="width: 84px; height: 84px; border-radius: 50%; object-fit: cover; border: 3px solid #0284c7; box-shadow: 0 4px 12px rgba(2,132,199,0.15);" />
                <div>
                    <span style="background: #e0f2fe; color: #0284c7; font-size: 12px; font-weight: 800; padding: 3px 10px; border-radius: 50px; text-transform: uppercase;">
                        <?php echo htmlspecialchars($vendor['service_type'] ?? 'Technician'); ?>
                    </span>
                    <h1 style="font-size: 26px; font-weight: 900; color: #0f172a; margin: 6px 0 4px 0;">
                        <?php echo htmlspecialchars($vendor['full_name']); ?>
                    </h1>
                    <p style="color: #64748b; font-size: 13.5px; margin: 0; display: flex; align-items: center; gap: 12px;">
                        <span><i class="fa-solid fa-location-dot" style="color: #0284c7;"></i> <?php echo htmlspecialchars($vendor['town_name'] ?? 'Sri Lanka'); ?></span>
                        <span><i class="fa-solid fa-briefcase" style="color: #0284c7;"></i> <?php echo htmlspecialchars($vendor['experience'] ?? 'Experienced'); ?></span>
                    </p>
                </div>
            </div>

            <!-- Stats -->
            <div style="text-align: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 22px;">
                <div style="font-size: 28px; font-weight: 900; color: #0f172a; line-height: 1;">
                    <?php echo $avg_rating > 0 ? $avg_rating : 'New'; ?>
                </div>
                <div style="color: #f59e0b; font-size: 12px; margin: 4px 0;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="fa-solid fa-star<?php echo ($i <= round($avg_rating)) ? '' : '-half-stroke'; ?>" style="opacity: <?php echo ($i <= round($avg_rating)) ? '1' : '0.3'; ?>;"></i>
                    <?php endfor; ?>
                </div>
                <span style="font-size: 12px; color: #64748b; font-weight: 600;"><?php echo $total_reviews; ?> Reviews</span>
            </div>
        </div>
    </div>
</div>

<!-- Main Reviews Content (2 Columns) -->
<section style="padding: 35px 20px 80px 20px; max-width: 1100px; margin: 0 auto;">
    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 36px; align-items: start;">
        
        <!-- Left: Customer Reviews Feed -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;">
                    Client Feedback &amp; Ratings (<?php echo $total_reviews; ?>)
                </h2>
            </div>

            <?php if (empty($feedback_list)): ?>
                <div style="background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 18px; padding: 50px 20px; text-align: center;">
                    <div style="width: 54px; height: 54px; border-radius: 50%; background: #fef3c7; color: #f59e0b; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px auto; font-size: 22px;">
                        <i class="fa-regular fa-star"></i>
                    </div>
                    <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">No Reviews Yet</h3>
                    <p style="color: #64748b; font-size: 14px; margin: 0;">
                        No client reviews have been recorded for this specialist profile yet.
                    </p>
                </div>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <?php foreach ($feedback_list as $fb): ?>
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 22px 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.02);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: 800;">
                                        <?php echo strtoupper(substr($fb['customer_name'] ?? 'C', 0, 1)); ?>
                                    </div>
                                    <div>
                                        <h4 style="font-size: 15px; font-weight: 800; color: #0f172a; margin: 0;">
                                            <?php echo htmlspecialchars($fb['customer_name']); ?>
                                        </h4>
                                        <span style="font-size: 12px; color: #10b981; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                            <i class="fa-solid fa-circle-check fa-xs"></i> Verified Customer
                                        </span>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="color: #f59e0b; font-size: 13px;">
                                        <?php 
                                            $stars = intval($fb['rating'] ?? 5);
                                            for ($s = 1; $s <= 5; $s++) {
                                                echo ($s <= $stars) ? '<i class="fa-solid fa-star"></i>' : '<i class="fa-regular fa-star" style="color:#cbd5e1;"></i>';
                                            }
                                        ?>
                                    </div>
                                    <span style="font-size: 11.5px; color: #94a3b8;">
                                        <?php echo !empty($fb['created_at']) ? date('M d, Y', strtotime($fb['created_at'])) : 'Recent'; ?>
                                    </span>
                                </div>
                            </div>
                            <p style="color: #475569; font-size: 14.5px; line-height: 1.6; margin: 0;">
                                "<?php echo htmlspecialchars($fb['feedback']); ?>"
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right: Submit Review Form Card -->
        <div>
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 28px; box-shadow: 0 4px 18px rgba(0,0,0,0.03); margin-bottom: 24px;">
                <h3 style="font-size: 18px; font-weight: 800; color: #0f172a; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-pen-to-square" style="color: #0284c7;"></i> Leave a Review
                </h3>
                <p style="color: #64748b; font-size: 13.5px; margin-bottom: 20px;">
                    Record direct feedback for this service provider.
                </p>

                <?php if ($success_msg): ?>
                    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 14px 16px; color: #065f46; font-size: 13.5px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 18px;"></i>
                        <span>Thank you! Review has been saved.</span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_msg)): ?>
                    <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 14px 16px; color: #991b1b; font-size: 13.5px; margin-bottom: 20px;">
                        <?php echo htmlspecialchars($error_msg); ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                            Your Full Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="customer_name" value="<?php echo htmlspecialchars($prefill_name); ?>" placeholder="e.g. Ruwan Wickramasinghe" required
                               style="width: 100%; box-sizing: border-box; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; font-family: inherit;">
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                            Star Rating <span style="color: #ef4444;">*</span>
                        </label>
                        <select name="rating" style="width: 100%; box-sizing: border-box; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; font-family: inherit; background: #ffffff;">
                            <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733; (5/5 Excellent)</option>
                            <option value="4">&#9733;&#9733;&#9733;&#9733;&#9734; (4/5 Very Good)</option>
                            <option value="3">&#9733;&#9733;&#9733;&#9734;&#9734; (3/5 Average)</option>
                            <option value="2">&#9733;&#9733;&#9734;&#9734;&#9734; (2/5 Below Average)</option>
                            <option value="1">&#9733;&#9734;&#9734;&#9734;&#9734; (1/5 Unsatisfactory)</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #334155; margin-bottom: 6px;">
                            Your Comments <span style="color: #ef4444;">*</span>
                        </label>
                        <textarea name="feedback" placeholder="Describe punctuality, quality of repair, tool readiness, and overall satisfaction..." required rows="4"
                                  style="width: 100%; box-sizing: border-box; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; font-family: inherit; line-height: 1.5; resize: vertical;"></textarea>
                    </div>

                    <button type="submit" name="submit_review" style="background: #0284c7; color: #ffffff; border: none; width: 100%; padding: 12px; font-size: 14.5px; font-weight: 700; border-radius: 50px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="fa-solid fa-paper-plane"></i> Submit Review
                    </button>
                </form>
            </div>
        </div>

    </div>
</section>

<?php endif; ?>

<?php
include('./partials/footer.php');
?>
