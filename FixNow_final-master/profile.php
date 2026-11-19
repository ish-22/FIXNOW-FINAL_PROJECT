<?php 
include './partials/header.php'; 

// Ensure the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: signin.php"); 
    exit();
}

$customer_id = intval($_SESSION['customer_id']); 

// Fetch customer details securely
$query = "SELECT c.*, d.district_name, t.town_name 
          FROM tbl_customer c
          LEFT JOIN tbl_districts d ON c.district_id = d.id
          LEFT JOIN tbl_towns t ON c.town_id = t.id
          WHERE c.id = ?";
$customer = db_fetch_one($conn, $query, [$customer_id], "i");

if (!$customer) {
    header("Location: signin.php");
    exit();
}

// Fetch booking count
$total_bookings = db_fetch_one($conn, "SELECT COUNT(*) AS total FROM tbl_booking WHERE customer_id = ?", [$customer_id], "i")['total'] ?? 0;
?>

<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 30px 0;">
    <div class="fn-container" style="max-width: 800px;">
        <h1 class="fn-section-title">Customer Account Profile</h1>
        <p class="fn-section-subtitle">Manage your account credentials, contact information, and service history.</p>
    </div>
</div>

<section style="padding: 10px 0 80px 0;">
    <div class="fn-container" style="max-width: 800px;">
        <div class="fn-card" style="padding: 35px 30px;">
            <!-- Profile Top Header -->
            <div style="display: flex; align-items: center; gap: 24px; flex-wrap: wrap; border-bottom: 1px solid #e2e8f0; padding-bottom: 25px; margin-bottom: 25px;">
                <img src="./images/cus_images/<?php echo isset($customer['cus_image']) && file_exists('./images/cus_images/' . $customer['cus_image']) ? htmlspecialchars($customer['cus_image']) : 'default.jpg'; ?>" 
                     alt="Profile Picture" 
                     style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #0284c7; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.2);" />

                <div>
                    <h2 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin-bottom: 4px;">
                        <?php echo htmlspecialchars($customer['cus_name']); ?>
                    </h2>
                    <p style="color: #64748b; font-size: 15px; margin-bottom: 8px;">
                        <i class="fa-regular fa-envelope"></i> <?php echo htmlspecialchars($customer['cus_email']); ?>
                    </p>
                    <div style="display: flex; gap: 8px;">
                        <span class="fn-status fn-status-confirmed">
                            <i class="fa-solid fa-circle-check"></i> Active Member
                        </span>
                        <span class="fn-status" style="background: #e0f2fe; color: #0369a1;">
                            <i class="fa-solid fa-calendar-check"></i> <?php echo $total_bookings; ?> Bookings
                        </span>
                    </div>
                </div>
            </div>

            <!-- Profile Details Grid -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 30px;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 16px; border-radius: 10px;">
                    <span style="color: #64748b; font-size: 13px; display: block; margin-bottom: 4px;">Phone Number</span>
                    <strong style="color: #1e293b; font-size: 15px;">
                        <i class="fa-solid fa-phone" style="color: #0284c7; margin-right: 6px;"></i>
                        <?php echo htmlspecialchars($customer['cus_phone']); ?>
                    </strong>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 16px; border-radius: 10px;">
                    <span style="color: #64748b; font-size: 13px; display: block; margin-bottom: 4px;">District & Town</span>
                    <strong style="color: #1e293b; font-size: 15px;">
                        <i class="fa-solid fa-location-dot" style="color: #0284c7; margin-right: 6px;"></i>
                        <?php echo htmlspecialchars($customer['district_name'] ?? 'Western'); ?> &bull; <?php echo htmlspecialchars($customer['town_name'] ?? 'Local'); ?>
                    </strong>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 16px; border-radius: 10px; grid-column: 1 / -1;">
                    <span style="color: #64748b; font-size: 13px; display: block; margin-bottom: 4px;">Primary Service Address</span>
                    <strong style="color: #1e293b; font-size: 15px;">
                        <i class="fa-solid fa-map-pin" style="color: #0284c7; margin-right: 6px;"></i>
                        <?php echo htmlspecialchars($customer['cus_address']); ?>
                    </strong>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 16px; border-radius: 10px; grid-column: 1 / -1;">
                    <span style="color: #64748b; font-size: 13px; display: block; margin-bottom: 4px;">Account Created</span>
                    <strong style="color: #1e293b; font-size: 15px;">
                        <i class="fa-regular fa-clock" style="color: #0284c7; margin-right: 6px;"></i>
                        <?php 
                            $joined_date = !empty($customer['registration_date']) ? $customer['registration_date'] : (!empty($customer['created_at']) ? $customer['created_at'] : 'now');
                            echo date('F j, Y', strtotime($joined_date)); 
                        ?>
                    </strong>
                </div>
            </div>

            <!-- Profile Action Buttons -->
            <div style="display: flex; gap: 14px; flex-wrap: wrap;">
                <a href="profileupdate.php" class="fn-btn fn-btn-primary" style="flex: 1; padding: 12px 20px;">
                    <i class="fa-regular fa-pen-to-square"></i> Edit Profile Info
                </a>
                <a href="bookingHistory.php" class="fn-btn fn-btn-outline" style="flex: 1; padding: 12px 20px;">
                    <i class="fa-regular fa-calendar-check"></i> View Booking History
                </a>
                <a href="logout.php" class="fn-btn" style="background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; padding: 12px 20px;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                </a>
            </div>
        </div>
    </div>
</section>

<?php include './partials/footer.php'; ?>
