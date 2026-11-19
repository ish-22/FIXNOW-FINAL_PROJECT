<?php 
include 'partials/header.php'; 

$vendor_id = isset($_SESSION['vendor_id']) ? intval($_SESSION['vendor_id']) : 0;

if ($vendor_id <= 0) {
    header("Location: ../signin.php");
    exit();
}

// Fetch specialist details
$vendor = db_fetch_one($conn, "SELECT sp.*, s.service_type, t.town_name FROM tbl_service_providers sp LEFT JOIN tbl_service s ON sp.servicetype_id = s.id LEFT JOIN tbl_towns t ON sp.town_id = t.id WHERE sp.id = ?", [$vendor_id], "i");

// Fetch stats
$total_bookings = db_fetch_one($conn, "SELECT COUNT(*) AS total FROM tbl_booking WHERE service_provider_id = ?", [$vendor_id], "i")['total'] ?? 0;
$pending_bookings = db_fetch_one($conn, "SELECT COUNT(*) AS total FROM tbl_booking WHERE service_provider_id = ? AND (booking_status = 'Pending' OR task_status = 'Pending')", [$vendor_id], "i")['total'] ?? 0;
$completed_tasks = db_fetch_one($conn, "SELECT COUNT(*) AS total FROM tbl_booking WHERE service_provider_id = ? AND task_status = 'Completed'", [$vendor_id], "i")['total'] ?? 0;
$total_earnings = db_fetch_one($conn, "SELECT SUM(vendor_payout) AS total FROM tbl_booking WHERE service_provider_id = ? AND (payment_status = 'Paid' OR task_status = 'Completed')", [$vendor_id], "i")['total'] ?? 0.00;

// Fetch recent appointments
$recent_bookings = db_fetch_all($conn, "SELECT b.*, s.service_type FROM tbl_booking b LEFT JOIN tbl_service s ON b.service_id = s.id WHERE b.service_provider_id = ? ORDER BY b.id DESC LIMIT 5", [$vendor_id], "i");

// Fetch unread notifications
$notification = db_fetch_one($conn, "SELECT * FROM tbl_notification WHERE serviceProvider_id = ? AND status_provider = 'unread' ORDER BY id DESC LIMIT 1", [$vendor_id], "i");
if ($notification) {
    db_execute($conn, "UPDATE tbl_notification SET status_provider = 'read' WHERE id = ?", [$notification['id']], "i");
}
?>

<style>
.fn-dash-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #ffffff;
    padding: 50px 0 60px 0;
    position: relative;
}

.fn-stat-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    padding: 24px 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    display: flex;
    align-items: center;
    gap: 18px;
    transition: all 0.2s;
}

.fn-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.fn-stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}
</style>

<!-- Dashboard Top Banner -->
<div class="fn-dash-header">
    <div class="fn-container">
        <?php if (!empty($notification)): ?>
            <div style="background: rgba(2, 132, 199, 0.25); border: 1px solid #0284c7; padding: 12px 18px; border-radius: 8px; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <i class="fa-solid fa-bell" style="color: #38bdf8; margin-right: 8px;"></i>
                    <span><?php echo htmlspecialchars($notification['service_provider_notification_message']); ?></span>
                </div>
                <a href="customer-bookings.php" class="fn-btn fn-btn-primary" style="padding: 6px 14px; font-size: 13px;">View Bookings</a>
            </div>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
            <div>
                <span class="fn-hero-badge" style="background: rgba(2, 132, 199, 0.3); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.4);">
                    <i class="fa-solid fa-id-badge"></i> Certified Specialist
                </span>
                <h1 style="font-size: 2.2rem; font-weight: 800; margin: 8px 0;">
                    Welcome back, <?php echo htmlspecialchars($vendor['full_name']); ?>!
                </h1>
                <p style="color: #94a3b8; font-size: 15px; margin: 0;">
                    Trade: <strong><?php echo htmlspecialchars($vendor['service_type'] ?? 'General Service'); ?></strong> &bull; Location: <strong><?php echo htmlspecialchars($vendor['town_name'] ?? 'Local'); ?></strong>
                </p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="customer-bookings.php" class="fn-btn fn-btn-primary fn-btn-pill">
                    <i class="fa-solid fa-calendar-check"></i> Manage Bookings
                </a>
                <a href="invoice.php" class="fn-btn fn-btn-outline fn-btn-pill" style="border-color: #38bdf8; color: #38bdf8 !important;">
                    <i class="fa-solid fa-receipt"></i> Subscription Status
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Key Performance Metrics Grid -->
<div class="fn-container" style="margin-top: -30px; position: relative; z-index: 2;">
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px;">
        <div class="fn-stat-card">
            <div class="fn-stat-icon" style="background: #e0f2fe; color: #0284c7;">
                <i class="fa-solid fa-calendar-days"></i>
            </div>
            <div>
                <span style="font-size: 13px; color: #64748b; font-weight: 600;">Total Bookings</span>
                <h3 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin: 0;"><?php echo $total_bookings; ?></h3>
            </div>
        </div>

        <div class="fn-stat-card">
            <div class="fn-stat-icon" style="background: #fef3c7; color: #b45309;">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <span style="font-size: 13px; color: #64748b; font-weight: 600;">Pending Tasks</span>
                <h3 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin: 0;"><?php echo $pending_bookings; ?></h3>
            </div>
        </div>

        <div class="fn-stat-card">
            <div class="fn-stat-icon" style="background: #dcfce7; color: #15803d;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span style="font-size: 13px; color: #64748b; font-weight: 600;">Completed Tasks</span>
                <h3 style="font-size: 1.8rem; font-weight: 800; color: #0f172a; margin: 0;"><?php echo $completed_tasks; ?></h3>
            </div>
        </div>

        <div class="fn-stat-card">
            <div class="fn-stat-icon" style="background: #f1f5f9; color: #475569;">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div>
                <span style="font-size: 13px; color: #64748b; font-weight: 600;">Net Payouts (90%)</span>
                <h3 style="font-size: 1.6rem; font-weight: 800; color: #0284c7; margin: 0;">Rs. <?php echo number_format($total_earnings, 2); ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Recent Appointments Section -->
<section style="padding: 50px 0 80px 0;">
    <div class="fn-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0;">Recent Client Bookings</h2>
                <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Incoming appointment requests and customer work orders.</p>
            </div>
            <a href="customer-bookings.php" class="fn-btn fn-btn-outline" style="padding: 8px 16px; font-size: 14px;">
                View All <i class="fa-solid fa-arrow-right fa-xs"></i>
            </a>
        </div>

        <?php if (!empty($recent_bookings)): ?>
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <?php foreach ($recent_bookings as $b): 
                    $b_id = $b['id'];
                    $b_status = strtolower($b['booking_status'] ?? 'pending');
                    $t_status = strtolower($b['task_status'] ?? 'pending');
                    $status_class = ($b_status === 'confirmed' || $b_status === 'approved') ? 'fn-status-confirmed' : 'fn-status-pending';
                ?>
                <div class="fn-card" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <span style="font-size: 12px; font-weight: 700; color: #0284c7; background: #e0f2fe; padding: 2px 8px; border-radius: 4px;">
                            #<?php echo $b_id; ?> &bull; <?php echo htmlspecialchars($b['service_type'] ?? 'Service'); ?>
                        </span>
                        <h4 style="font-size: 1.2rem; font-weight: 800; color: #0f172a; margin: 6px 0 4px 0;">
                            <?php echo htmlspecialchars($b['customer_name']); ?>
                        </h4>
                        <p style="color: #64748b; font-size: 13.5px; margin: 0;">
                            <i class="fa-regular fa-calendar"></i> <?php echo htmlspecialchars($b['booking_date']); ?> (<?php echo htmlspecialchars($b['time_slot'] ?? '09:00 AM - 11:00 AM'); ?>) &bull; <i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($b['address']); ?>
                        </p>
                    </div>

                    <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                        <div style="text-align: right;">
                            <span style="font-size: 12px; color: #64748b; display: block;">Your Payout (90%)</span>
                            <strong style="color: #0284c7; font-size: 16px;">Rs. <?php echo number_format($b['vendor_payout'], 2); ?></strong>
                        </div>

                        <span class="fn-status <?php echo $status_class; ?>">
                            <?php echo ucfirst($b_status); ?>
                        </span>

                        <a href="customer-bookings.php" class="fn-btn fn-btn-primary" style="padding: 8px 14px; font-size: 13px;">
                            Details
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <i class="fa-regular fa-calendar-xmark fa-2x" style="color: #94a3b8; margin-bottom: 12px;"></i>
                <h4 style="color: #1e293b; margin-bottom: 6px;">No bookings assigned yet</h4>
                <p style="color: #64748b; font-size: 14px;">As customers book your services, incoming orders will appear here automatically.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Footer for Service Vendor -->
<footer style="background: #0f172a; color: #94a3b8; padding: 30px 0; text-align: center; font-size: 13.5px; border-top: 1px solid #1e293b;">
    <div class="fn-container">
        &copy; <?php echo date('Y'); ?> FIXNOW Specialist Portal. All rights reserved.
    </div>
</footer>

</body>
</html>
