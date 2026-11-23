<?php
include_once('../config/constant.php');

if (!isset($_SESSION['vendor_id'])) {
    header("Location: ../signin.php");
    exit();
}

$vendor_id = intval($_SESSION['vendor_id']);

// Fetch vendor details based on vendor_id
$vendor = db_fetch_one(
    $conn,
    "SELECT 
        sp.full_name AS vendor_name,
        sp.email,
        sp.contact_no AS contact_number,
        sp.experience,
        sp.about_you,
        sp.available_dates_and_times AS available_times,
        sp.vendor_image,
        s.service_type,
        t.town_name,
        s.booking_price,
        sp.registration_code,
        sp.status
    FROM tbl_service_providers sp
    LEFT JOIN tbl_service s ON sp.servicetype_id = s.id
    LEFT JOIN tbl_towns t ON sp.town_id = t.id
    WHERE sp.id = ?",
    [$vendor_id],
    "i"
);

if (!$vendor) {
    header("Location: ../signin.php");
    exit();
}

// Fetch average rating from tbl_feedback
$rank_data = db_fetch_one($conn, "SELECT AVG(rating) AS average_rating, COUNT(*) AS review_count FROM tbl_feedback WHERE vendor_id = ?", [$vendor_id], "i");
$average_rating = ($rank_data && $rank_data['average_rating']) ? round(floatval($rank_data['average_rating']), 1) : 0.0;
$review_count = ($rank_data && $rank_data['review_count']) ? intval($rank_data['review_count']) : 0;

// Fetch count of completed tasks from tbl_booking
$tasks_data = db_fetch_one($conn, "SELECT COUNT(*) AS completed_tasks FROM tbl_booking WHERE service_provider_id = ? AND task_status = 'Completed'", [$vendor_id], "i");
$completed_tasks = $tasks_data ? intval($tasks_data['completed_tasks']) : 0;

// Overall ranking calculation
$overall_ranking = round($average_rating + $completed_tasks, 1);

$current_page = 'profile.php';
include('./partials/header.php');
?>

<!-- Hero Header Section -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 30px 0; border-bottom: 1px solid #e2e8f0;">
    <div style="max-width: 1050px; margin: 0 auto; padding: 0 20px;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 14px; background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-id-badge"></i> Specialist Console
        </span>
        <h1 style="font-size: 32px; font-weight: 900; color: #0f172a; margin: 0 0 8px 0;">
            Partner Account &amp; Profile
        </h1>
        <p style="color: #64748b; font-size: 15.5px; margin: 0; max-width: 650px;">
            Manage your service provider credentials, service area, dispatch availability, and monitor client performance ratings.
        </p>
    </div>
</div>

<!-- Main Profile Section -->
<section style="padding: 40px 20px 80px 20px; max-width: 1050px; margin: 0 auto;">
    
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 32px; align-items: start;">
        
        <!-- Left: Detailed Profile Card -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 36px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            
            <!-- Top Bio Bar -->
            <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap; border-bottom: 1px solid #e2e8f0; padding-bottom: 26px; margin-bottom: 28px;">
                <?php 
                    $v_img = '../images/vendor_images/' . ($vendor['vendor_image'] ?? '');
                    $avatar_src = (!empty($vendor['vendor_image']) && file_exists($v_img)) ? $v_img : '../images/vendor_images/default.jpg';
                ?>
                <img src="<?php echo htmlspecialchars($avatar_src); ?>" alt="<?php echo htmlspecialchars($vendor['vendor_name']); ?>" 
                     style="width: 96px; height: 96px; border-radius: 50%; object-fit: cover; border: 3px solid #0284c7; box-shadow: 0 4px 14px rgba(2,132,199,0.2);" />

                <div>
                    <span style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; font-size: 12px; font-weight: 800; padding: 3px 10px; border-radius: 50px; text-transform: uppercase;">
                        <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($vendor['status']); ?> Partner
                    </span>
                    <h2 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 6px 0 4px 0;">
                        <?php echo htmlspecialchars($vendor['vendor_name']); ?>
                    </h2>
                    <p style="color: #0284c7; font-size: 14.5px; font-weight: 700; margin: 0 0 6px 0;">
                        <?php echo htmlspecialchars($vendor['service_type'] ?? 'General Specialist'); ?>
                    </p>
                    <p style="color: #64748b; font-size: 13px; margin: 0;">
                        Registration ID: <strong><?php echo htmlspecialchars($vendor['registration_code'] ?? ('REG' . (1000 + $vendor_id))); ?></strong>
                    </p>
                </div>
            </div>

            <!-- Profile Info Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 28px;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Primary Email</span>
                    <strong style="color: #0f172a; font-size: 14px;"><?php echo htmlspecialchars($vendor['email']); ?></strong>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Contact Phone</span>
                    <strong style="color: #0f172a; font-size: 14px;"><?php echo htmlspecialchars($vendor['contact_number']); ?></strong>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Primary Town</span>
                    <strong style="color: #0f172a; font-size: 14px;"><?php echo htmlspecialchars($vendor['town_name'] ?? 'Sri Lanka'); ?></strong>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                    <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; display: block; margin-bottom: 4px;">Industry Experience</span>
                    <strong style="color: #0f172a; font-size: 14px;"><?php echo htmlspecialchars($vendor['experience'] ?? 'Experienced'); ?></strong>
                </div>
            </div>

            <!-- Availability & About -->
            <div style="margin-bottom: 24px;">
                <h4 style="font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                    <i class="fa-regular fa-clock" style="color: #0284c7; margin-right: 6px;"></i> Operating &amp; Arrival Hours
                </h4>
                <p style="font-size: 14px; color: #475569; margin: 0 0 16px 0;">
                    <?php echo htmlspecialchars($vendor['available_times'] ?? 'Standard Business Hours'); ?>
                </p>

                <h4 style="font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                    <i class="fa-solid fa-align-left" style="color: #0284c7; margin-right: 6px;"></i> Professional Summary
                </h4>
                <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0;">
                    <?php echo htmlspecialchars($vendor['about_you'] ?? 'Accredited technical service provider on the FixNow platform.'); ?>
                </p>
            </div>

            <!-- Actions Bar -->
            <div style="display: flex; gap: 12px; flex-wrap: wrap; border-top: 1px solid #e2e8f0; padding-top: 22px;">
                <a href="update_profile.php" style="background: #0284c7; color: #ffffff; text-decoration: none; padding: 11px 24px; border-radius: 50px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Profile
                </a>
                <a href="vendors-details.php?vendor_id=<?php echo $vendor_id; ?>" style="background: #ffffff; color: #0284c7; border: 1.5px solid #0284c7; text-decoration: none; padding: 10px 22px; border-radius: 50px; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-eye"></i> View Public Profile
                </a>
            </div>

        </div>

        <!-- Right: Ranking & Metrics Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Overall Ranking Card -->
            <div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border-radius: 20px; padding: 28px; color: #ffffff; text-align: center; box-shadow: 0 8px 24px rgba(2,132,199,0.25);">
                <span style="font-size: 12.5px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9;">
                    Platform Ranking Score
                </span>
                <div style="font-size: 48px; font-weight: 900; margin: 10px 0; line-height: 1;">
                    <?php echo $overall_ranking; ?>
                </div>
                <p style="font-size: 13px; opacity: 0.9; margin: 0 0 16px 0; line-height: 1.5;">
                    Calibrated based on client feedback and confirmed job completions.
                </p>
                <div style="display: flex; justify-content: center; gap: 16px; font-size: 13px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 14px;">
                    <div>
                        <strong style="display: block; font-size: 16px;"><?php echo $average_rating; ?> ★</strong>
                        <span style="opacity: 0.8; font-size: 11.5px;">Avg Rating</span>
                    </div>
                    <div>
                        <strong style="display: block; font-size: 16px;"><?php echo $completed_tasks; ?></strong>
                        <span style="opacity: 0.8; font-size: 11.5px;">Completed</span>
                    </div>
                </div>
            </div>

            <!-- Quick Management Card -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 14px;">Quick Shortcuts</h3>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="customer-bookings.php" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #f8fafc; border-radius: 10px; text-decoration: none; color: #334155; font-size: 13.5px; font-weight: 600;">
                        <span><i class="fa-solid fa-list-check" style="color: #0284c7; margin-right: 8px;"></i> Active Work Orders</span>
                        <i class="fa-solid fa-chevron-right fa-xs" style="color: #94a3b8;"></i>
                    </a>
                    <a href="feedback.php?vendor_id=<?php echo $vendor_id; ?>" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #f8fafc; border-radius: 10px; text-decoration: none; color: #334155; font-size: 13.5px; font-weight: 600;">
                        <span><i class="fa-solid fa-star" style="color: #f59e0b; margin-right: 8px;"></i> Customer Reviews (<?php echo $review_count; ?>)</span>
                        <i class="fa-solid fa-chevron-right fa-xs" style="color: #94a3b8;"></i>
                    </a>
                    <a href="invoice.php" style="display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #f8fafc; border-radius: 10px; text-decoration: none; color: #334155; font-size: 13.5px; font-weight: 600;">
                        <span><i class="fa-solid fa-file-invoice-dollar" style="color: #10b981; margin-right: 8px;"></i> Membership &amp; Invoices</span>
                        <i class="fa-solid fa-chevron-right fa-xs" style="color: #94a3b8;"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

</section>

<?php
include('./partials/footer.php');
?>
