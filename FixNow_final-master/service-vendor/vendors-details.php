<?php
include('../config/constant.php');

// Support both vendor_id and service_provider_id
$target_vendor_id = intval($_GET['vendor_id'] ?? $_GET['service_provider_id'] ?? ($_SESSION['vendor_id'] ?? 0));

if ($target_vendor_id <= 0) {
    header("Location: index.php");
    exit();
}

// Fetch vendor details with parameterized query
$vendor = db_fetch_one(
    $conn,
    "SELECT sp.*, s.service_type, s.about_service, s.booking_price, s.category_id, c.category_name, t.town_name, d.district_name 
     FROM tbl_service_providers sp
     JOIN tbl_service s ON s.id = sp.servicetype_id
     LEFT JOIN tbl_category c ON c.id = s.category_id
     LEFT JOIN tbl_towns t ON t.id = sp.town_id
     LEFT JOIN tbl_districts d ON d.id = sp.district_id
     WHERE sp.id = ?",
    [$target_vendor_id],
    "i"
);

if (!$vendor) {
    header("Location: index.php");
    exit();
}

// Fetch active promotional offer if available
$offer = db_fetch_one(
    $conn,
    "SELECT * FROM tbl_offer WHERE offer_type_id = ? AND status = 'active' LIMIT 1",
    [intval($vendor['servicetype_id'])],
    "i"
);

// Fetch ratings & reviews from tbl_feedback
$rating_data = db_fetch_one(
    $conn,
    "SELECT AVG(rating) AS average_rating, COUNT(*) AS review_count FROM tbl_feedback WHERE vendor_id = ?",
    [$target_vendor_id],
    "i"
);
$has_reviews = ($rating_data && intval($rating_data['review_count']) > 0);
$avg_rating = $has_reviews ? round(floatval($rating_data['average_rating']), 1) : 5.0;
$review_count = $has_reviews ? intval($rating_data['review_count']) : 0;

// Fetch verified reviews list
$reviews = db_fetch_all(
    $conn,
    "SELECT * FROM tbl_feedback WHERE vendor_id = ? ORDER BY id DESC LIMIT 10",
    [$target_vendor_id],
    "i"
);

// Fetch completed jobs count
$completed_res = db_fetch_one(
    $conn,
    "SELECT COUNT(*) AS total FROM tbl_booking WHERE service_provider_id = ? AND task_status = 'Completed'",
    [$target_vendor_id],
    "i"
);
$completed_tasks = ($completed_res && $completed_res['total']) ? intval($completed_res['total']) : 0;

// Price calculations
$base_price = floatval($vendor['booking_price']);
$offer_price = null;
if ($offer && isset($offer['discounted_price']) && floatval($offer['discounted_price']) > 0) {
    $offer_price = floatval($offer['discounted_price']);
}
$display_price = $offer_price ? $offer_price : $base_price;
$escrow_fee = round($display_price * 0.10, 2);
$vendor_net_payout = round($display_price - $escrow_fee, 2);

// Image Path with fallback
$avatar_file = $vendor['vendor_image'];
$avatar_path = "../images/vendor_images/" . $avatar_file;
if (empty($avatar_file) || !file_exists($avatar_path)) {
    $avatar_path = "../assets/images/default-avatar.png";
}

include('partials/header.php');
?>

<!-- Breadcrumb Navigation -->
<div style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 14px 0;">
    <div class="fn-container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
        <nav style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: #64748b;">
            <a href="index.php" style="color: #64748b; text-decoration: none;">Specialist Workspace</a>
            <i class="fa-solid fa-chevron-right" style="font-size: 10px; color: #94a3b8;"></i>
            <span style="color: #0f172a; font-weight: 600;">Specialist Public Profile Preview</span>
        </nav>
        <a href="index.php" class="fn-btn fn-btn-outline" style="padding: 7px 16px; font-size: 13px;">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<!-- Specialist Hero Card -->
<section style="padding: 30px 0 20px 0;">
    <div class="fn-container">
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); overflow: hidden;">
            <!-- Header Top Gradient Strip -->
            <div style="height: 120px; background: linear-gradient(135deg, #0284c7 0%, #0369a1 50%, #0f172a 100%); position: relative;">
                <div style="position: absolute; right: 24px; top: 20px; display: flex; gap: 10px;">
                    <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); color: #ffffff; padding: 6px 14px; border-radius: 50px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-shield-check"></i> FixNow Verified Specialist
                    </span>
                </div>
            </div>

            <!-- Profile Info Content -->
            <div style="padding: 0 32px 32px 32px; display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 24px; margin-top: -60px;">
                <div style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 24px;">
                    <!-- Avatar Image Frame -->
                    <div style="position: relative;">
                        <img src="<?php echo $avatar_path; ?>" alt="<?php echo htmlspecialchars($vendor['full_name']); ?>" 
                             style="width: 140px; height: 140px; border-radius: 50%; object-fit: cover; border: 5px solid #ffffff; box-shadow: 0 8px 24px rgba(0,0,0,0.12); background: #ffffff;"
                             onerror="this.src='../assets/images/default-avatar.png';">
                        <span style="position: absolute; bottom: 8px; right: 8px; width: 22px; height: 22px; background: #10b981; border: 3px solid #ffffff; border-radius: 50%;" title="Active Specialist Profile"></span>
                    </div>

                    <!-- Names & Title -->
                    <div style="margin-bottom: 6px;">
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 6px;">
                            <span style="background: #e0f2fe; color: #0284c7; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 50px; text-transform: uppercase;">
                                <?php echo htmlspecialchars($vendor['category_name'] ?? 'Household Services'); ?>
                            </span>
                            <span style="background: #f1f5f9; color: #475569; font-size: 12px; font-weight: 600; padding: 4px 10px; border-radius: 50px;">
                                <i class="fa-solid fa-id-card"></i> Code: <?php echo htmlspecialchars($vendor['registration_code'] ?? 'VND-' . $vendor['id']); ?>
                            </span>
                        </div>

                        <h1 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 6px; letter-spacing: -0.3px;">
                            <?php echo htmlspecialchars($vendor['full_name']); ?>
                        </h1>

                        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 16px; font-size: 14.5px; color: #64748b;">
                            <span>
                                <i class="fa-solid fa-screwdriver-wrench" style="color: #0284c7; margin-right: 6px;"></i>
                                <strong style="color: #334155;"><?php echo htmlspecialchars($vendor['service_type']); ?></strong>
                            </span>
                            <span>•</span>
                            <span>
                                <i class="fa-solid fa-location-dot" style="color: #ef4444; margin-right: 6px;"></i>
                                <?php echo htmlspecialchars($vendor['town_name'] ?? 'Western Province'); ?><?php echo !empty($vendor['district_name']) ? ', ' . htmlspecialchars($vendor['district_name']) : ''; ?>
                            </span>
                            <span>•</span>
                            <span>
                                <i class="fa-solid fa-briefcase" style="color: #f59e0b; margin-right: 6px;"></i>
                                <?php echo htmlspecialchars($vendor['experience'] ?? '5+ Years'); ?>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Top Quick Actions Strip -->
                <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 8px;">
                    <a href="profile.php" class="fn-btn fn-btn-primary" style="padding: 12px 20px; font-size: 14.5px;">
                        <i class="fa-solid fa-pen-to-square"></i> Edit My Profile
                    </a>
                    <a href="customer-bookings.php" class="fn-btn fn-btn-outline" style="padding: 12px 20px; font-size: 14.5px;">
                        <i class="fa-solid fa-list-check"></i> View Orders
                    </a>
                </div>
            </div>

            <!-- Trust Bar / Key Statistics Grid -->
            <div style="background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 18px 32px; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 42px; height: 42px; border-radius: 10px; background: #fef3c7; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div>
                        <div style="font-size: 18px; font-weight: 800; color: #0f172a;">
                            <?php echo $avg_rating; ?> <span style="font-size: 13px; font-weight: 500; color: #94a3b8;">/ 5.0</span>
                        </div>
                        <div style="font-size: 12.5px; color: #64748b;"><?php echo $review_count; ?> Customer Ratings</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 42px; height: 42px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                    <div>
                        <div style="font-size: 18px; font-weight: 800; color: #0f172a;"><?php echo $completed_tasks; ?> Completed</div>
                        <div style="font-size: 12.5px; color: #64748b;">Dispatched Jobs</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 42px; height: 42px; border-radius: 10px; background: #dcfce7; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fa-solid fa-vault"></i>
                    </div>
                    <div>
                        <div style="font-size: 18px; font-weight: 800; color: #10b981;">90% Net Payout</div>
                        <div style="font-size: 12.5px; color: #64748b;">Automated Escrow</div>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 42px; height: 42px; border-radius: 10px; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <div>
                        <div style="font-size: 16px; font-weight: 700; color: #0f172a;">Active Slots</div>
                        <div style="font-size: 12.5px; color: #64748b;">Time-Slot Engine</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Details Layout (2 Columns: Main 68%, Sidebar 32%) -->
<section style="padding: 20px 0 80px 0;">
    <div class="fn-container" style="display: grid; grid-template-columns: 1fr 360px; gap: 32px; align-items: start;">
        
        <!-- Left Content Column -->
        <div>
            <!-- Card 1: About Specialist Profile -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px; box-shadow: 0 4px 14px rgba(0,0,0,0.02); margin-bottom: 24px;">
                <h3 style="font-size: 19px; font-weight: 700; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-user-tie" style="color: #0284c7;"></i> Public Bio &amp; Professional Statement
                </h3>
                <div style="font-size: 15px; color: #475569; line-height: 1.75;">
                    <?php 
                    $clean_about = trim($vendor['about_you']);
                    if (empty($clean_about) || strlen($clean_about) < 10) {
                        echo "Certified master specialist with extensive field experience delivering high-quality residential and commercial maintenance across " . htmlspecialchars($vendor['town_name'] ?? 'Sri Lanka') . ". Equipped with professional-grade trade equipment and trained to follow all national safety and electrical/plumbing guidelines.";
                    } else {
                        echo nl2br(htmlspecialchars($clean_about));
                    }
                    ?>
                </div>

                <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid #f1f5f9; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                    <div>
                        <span style="font-size: 12.5px; color: #94a3b8; font-weight: 600; text-transform: uppercase;">Trade Category</span>
                        <div style="font-size: 14.5px; font-weight: 700; color: #0f172a; margin-top: 2px;">
                            <?php echo htmlspecialchars($vendor['category_name'] ?? 'General Maintenance'); ?>
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 12.5px; color: #94a3b8; font-weight: 600; text-transform: uppercase;">Field Experience</span>
                        <div style="font-size: 14.5px; font-weight: 700; color: #0f172a; margin-top: 2px;">
                            <?php echo htmlspecialchars($vendor['experience'] ?? '5+ Years'); ?>
                        </div>
                    </div>
                    <div>
                        <span style="font-size: 12.5px; color: #94a3b8; font-weight: 600; text-transform: uppercase;">Service Territory</span>
                        <div style="font-size: 14.5px; font-weight: 700; color: #0f172a; margin-top: 2px;">
                            <?php echo htmlspecialchars($vendor['town_name'] ?? 'Sri Lanka'); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Service Scope & Work Inclusions -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px; box-shadow: 0 4px 14px rgba(0,0,0,0.02); margin-bottom: 24px;">
                <h3 style="font-size: 19px; font-weight: 700; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-list-check" style="color: #0284c7;"></i> Service Scope &amp; Customer Inclusions
                </h3>
                <p style="font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 16px;">
                    <?php echo htmlspecialchars($vendor['about_service'] ?? 'Comprehensive diagnostic inspection, repair, routine maintenance, and functional verification.'); ?>
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-top: 16px;">
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #334155;">
                        <i class="fa-solid fa-circle-check" style="color: #10b981;"></i> On-site diagnostic evaluation
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #334155;">
                        <i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Professional-grade equipment
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #334155;">
                        <i class="fa-solid fa-circle-check" style="color: #10b981;"></i> Post-service workplace cleanup
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 14px; color: #334155;">
                        <i class="fa-solid fa-circle-check" style="color: #10b981;"></i> 100% Escrow backed workmanship
                    </div>
                </div>
            </div>

            <!-- Card 3: Operating Availability & Time Slots -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px; box-shadow: 0 4px 14px rgba(0,0,0,0.02); margin-bottom: 24px;">
                <h3 style="font-size: 19px; font-weight: 700; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-regular fa-calendar-days" style="color: #0284c7;"></i> Current Dispatch Availability
                </h3>
                <p style="font-size: 14.5px; color: #475569; margin-bottom: 16px;">
                    <strong>Schedule:</strong> 
                    <span style="color: #0f172a; font-weight: 600;"><?php echo htmlspecialchars($vendor['available_dates_and_times'] ?? 'Monday to Saturday, 8:00 AM - 6:00 PM'); ?></span>
                </p>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                    <span style="font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Standard Customer Arrival Slots</span>
                    <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 10px;">
                        <span style="background: #ffffff; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 8px; font-size: 13.5px; font-weight: 600; color: #0f172a;">
                            <i class="fa-regular fa-sun" style="color: #f59e0b; margin-right: 6px;"></i> Morning (09:00 - 12:00)
                        </span>
                        <span style="background: #ffffff; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 8px; font-size: 13.5px; font-weight: 600; color: #0f172a;">
                            <i class="fa-solid fa-cloud-sun" style="color: #0284c7; margin-right: 6px;"></i> Afternoon (13:00 - 16:00)
                        </span>
                        <span style="background: #ffffff; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 8px; font-size: 13.5px; font-weight: 600; color: #0f172a;">
                            <i class="fa-solid fa-moon" style="color: #6366f1; margin-right: 6px;"></i> Evening (16:00 - 19:00)
                        </span>
                    </div>
                </div>
            </div>

            <!-- Card 4: Verified Customer Reviews -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px; box-shadow: 0 4px 14px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                    <h3 style="font-size: 19px; font-weight: 700; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-comments" style="color: #0284c7;"></i> Client Feedback History
                    </h3>
                    <span style="font-size: 13.5px; font-weight: 600; color: #64748b;">
                        <?php echo $review_count; ?> Total Rating<?php echo $review_count === 1 ? '' : 's'; ?>
                    </span>
                </div>

                <?php if (!empty($reviews)): ?>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <?php foreach ($reviews as $rev): 
                            $stars = intval($rev['rating'] ?? 5);
                        ?>
                            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                                    <div style="font-weight: 700; color: #0f172a; font-size: 14.5px;">
                                        <?php echo htmlspecialchars($rev['customer_name'] ?? 'FixNow Customer'); ?>
                                        <span style="color: #10b981; font-size: 12px; margin-left: 6px; font-weight: 600;">
                                            <i class="fa-solid fa-circle-check"></i> Verified Job
                                        </span>
                                    </div>
                                    <div style="color: #f59e0b; font-size: 13px;">
                                        <?php 
                                        for ($s = 1; $s <= 5; $s++) {
                                            if ($s <= $stars) {
                                                echo '<i class="fa-solid fa-star"></i>';
                                            } else {
                                                echo '<i class="fa-regular fa-star" style="color: #cbd5e1;"></i>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <p style="font-size: 14px; color: #475569; line-height: 1.6; margin: 0;">
                                    "<?php echo htmlspecialchars($rev['feedback'] ?? 'Great service and quality workmanship.'); ?>"
                                </p>
                                <div style="font-size: 12px; color: #94a3b8; margin-top: 8px;">
                                    <?php echo !empty($rev['created_at']) ? date('M d, Y', strtotime($rev['created_at'])) : 'Recent Service'; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 36px 20px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px;">
                        <i class="fa-solid fa-award" style="font-size: 32px; color: #0284c7; margin-bottom: 12px;"></i>
                        <h4 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">New Profile on FIXNOW</h4>
                        <p style="color: #64748b; font-size: 14px; max-width: 440px; margin: 0 auto;">
                            Customer reviews will automatically appear here once incoming appointments are completed.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Sticky Action Card (32%) -->
        <aside style="position: sticky; top: 90px;">
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 28px; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
                
                <!-- Pricing Header -->
                <div style="margin-bottom: 20px; padding-bottom: 18px; border-bottom: 1px solid #f1f5f9;">
                    <span style="font-size: 12.5px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">Customer Rate Card</span>
                    <div style="display: flex; align-items: baseline; gap: 8px; margin-top: 6px;">
                        <span style="font-size: 32px; font-weight: 800; color: #0f172a;">Rs. <?php echo number_format($display_price, 2); ?></span>
                    </div>
                    <p style="color: #64748b; font-size: 12.5px; margin: 4px 0 0 0;">
                        Standard job booking fee charged to customers.
                    </p>
                </div>

                <!-- Escrow Fee Breakdown -->
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 16px; margin-bottom: 22px; font-size: 13.5px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <span style="color: #166534;"><i class="fa-solid fa-vault"></i> Net Specialist Payout (90%):</span>
                        <strong style="color: #166534; font-size: 15px;">Rs. <?php echo number_format($vendor_net_payout, 2); ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 12.5px; color: #15803d;">
                        <span>Platform Protection Fee (10%):</span>
                        <span>Rs. <?php echo number_format($escrow_fee, 2); ?></span>
                    </div>
                    <div style="border-top: 1px dashed #86efac; margin-top: 8px; padding-top: 8px; font-size: 11.5px; color: #166534; line-height: 1.4;">
                        <i class="fa-solid fa-shield-halved"></i> Automated PayHere Escrow. Net payout automatically released to your registered account upon customer completion approval.
                    </div>
                </div>

                <a href="profile.php" class="fn-btn fn-btn-primary" style="width: 100%; padding: 14px; font-size: 15px; font-weight: 700; text-align: center; border-radius: 12px; margin-bottom: 12px; display: block;">
                    <i class="fa-solid fa-user-pen" style="margin-right: 8px;"></i> Update Profile &amp; Pricing
                </a>

                <a href="customer-bookings.php" class="fn-btn fn-btn-outline" style="width: 100%; padding: 12px; font-size: 14px; text-align: center; display: block;">
                    <i class="fa-solid fa-calendar-check" style="margin-right: 8px;"></i> View Assigned Bookings
                </a>

                <!-- Trust Guarantees Checklist -->
                <div style="border-top: 1px solid #f1f5f9; padding-top: 20px; margin-top: 20px;">
                    <span style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 12px;">
                        Specialist Membership Benefits
                    </span>
                    <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 10px; font-size: 13.5px; color: #475569;">
                        <li style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-circle-check" style="color: #0284c7; font-size: 14px;"></i>
                            <span>90% Direct Job Payout Guarantee</span>
                        </li>
                        <li style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-circle-check" style="color: #0284c7; font-size: 14px;"></i>
                            <span>Scheduled 2-Hour Arrival Slots</span>
                        </li>
                        <li style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-circle-check" style="color: #0284c7; font-size: 14px;"></i>
                            <span>Direct Customer In-App Messaging</span>
                        </li>
                        <li style="display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-circle-check" style="color: #0284c7; font-size: 14px;"></i>
                            <span>Central Bank / PayHere Security</span>
                        </li>
                    </ul>
                </div>

            </div>
        </aside>

    </div>
</section>

<?php 
include('partials/footer.php'); 
?>
