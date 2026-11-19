<?php 
include('partials/main.php'); 

// 1. Fetch total gross volume and platform commission from bookings
$commission_res = db_fetch_one($conn, "SELECT SUM(total_amount) AS total_gross, SUM(platform_fee) AS total_commission FROM tbl_booking WHERE payment_status = 'Paid'");
$total_revenue = ($commission_res && $commission_res['total_gross']) ? floatval($commission_res['total_gross']) : 0;
$total_commission = ($commission_res && $commission_res['total_commission']) ? floatval($commission_res['total_commission']) : round($total_revenue * 0.10, 2);

// 2. Fetch total revenue from vendor subscription payments
$row_vendor = db_fetch_one($conn, "SELECT SUM(paid_amount) AS total_vendor_revenue FROM tbl_invoice WHERE status = 'paid'");
$total_vendor_revenue = ($row_vendor && $row_vendor['total_vendor_revenue']) ? floatval($row_vendor['total_vendor_revenue']) : 0; 

// 3. Count total active specialists
$total_specialists_res = db_fetch_one($conn, "SELECT COUNT(*) as total_specialists FROM tbl_service_providers WHERE status = 'Confirmed'");
$total_specialists = $total_specialists_res ? intval($total_specialists_res['total_specialists']) : 0;

// 4. Count total active customer accounts
$total_customers_res = db_fetch_one($conn, "SELECT COUNT(*) as total_customers FROM tbl_customer");
$total_customers = $total_customers_res ? intval($total_customers_res['total_customers']) : 0;

// 5. Fetch all service providers
$vendor_result = mysqli_query($conn, "SELECT sp.id, sp.full_name, sp.email, sp.contact_no, sp.servicetype_id, sp.status, s.service_type, t.town_name 
                 FROM tbl_service_providers sp
                 JOIN tbl_service s ON sp.servicetype_id = s.id
                 LEFT JOIN tbl_towns t ON sp.town_id = t.id
                 ORDER BY sp.id ASC");
?>

<!-- Welcome Banner -->
<div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border-radius: 16px; padding: 28px 32px; color: #ffffff; margin-bottom: 28px; box-shadow: 0 8px 20px rgba(2, 132, 199, 0.2); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
    <div>
        <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 10px;">
            <i class="fa-solid fa-shield-check"></i> System Control Hub
        </span>
        <h1 style="font-size: 26px; font-weight: 800; margin-bottom: 6px;">Welcome Back, <?php echo htmlspecialchars($admin_username); ?>!</h1>
        <p style="color: #e0f2fe; font-size: 14.5px; margin: 0;">Here is what is happening across the FIXNOW network today.</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <a href="bookings.php" class="admin-btn" style="background: #ffffff; color: #0284c7 !important; box-shadow: none;">
            <i class="fa-solid fa-list-check"></i> Manage Bookings
        </a>
        <a href="analyzing.php" class="admin-btn" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); box-shadow: none;">
            <i class="fa-solid fa-chart-pie"></i> Analytics
        </a>
    </div>
</div>

<!-- 4 High-Impact Metric Cards -->
<div class="admin-stats-grid">
    <!-- Card 1: Escrow Gross Volume -->
    <a href="bookings.php" class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-title">Booking Escrow Pool</span>
            <div class="admin-stat-icon primary">
                <i class="fa-solid fa-vault"></i>
            </div>
        </div>
        <div class="admin-stat-value">LKR <?php echo number_format($total_revenue, 2); ?></div>
        <div class="admin-stat-footer">
            <i class="fa-solid fa-lock" style="color: #0284c7;"></i> Held safely until task completion
        </div>
    </a>

    <!-- Card 2: Platform Commission (10%) -->
    <a href="bookings.php" class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-title">Platform Net (10%)</span>
            <div class="admin-stat-icon success">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #059669;">LKR <?php echo number_format($total_commission, 2); ?></div>
        <div class="admin-stat-footer">
            <i class="fa-solid fa-arrow-trend-up" style="color: #10b981;"></i> 10% auto-deducted escrow commission
        </div>
    </a>

    <!-- Card 3: Vendor Subscriptions -->
    <a href="manage-invoice.php" class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-title">Vendor Invoices Collected</span>
            <div class="admin-stat-icon info">
                <i class="fa-solid fa-file-invoice-dollar"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #4f46e5;">LKR <?php echo number_format($total_vendor_revenue, 2); ?></div>
        <div class="admin-stat-footer">
            <i class="fa-solid fa-circle-check" style="color: #6366f1;"></i> Specialist monthly subscription fees
        </div>
    </a>

    <!-- Card 4: Verified Specialists -->
    <a href="manage-vendor.php" class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-title">Active Specialists</span>
            <div class="admin-stat-icon warning">
                <i class="fa-solid fa-user-shield"></i>
            </div>
        </div>
        <div class="admin-stat-value" style="color: #d97706;"><?php echo $total_specialists; ?> <span style="font-size: 15px; font-weight: 600; color: #94a3b8;">Technicians</span></div>
        <div class="admin-stat-footer">
            <i class="fa-solid fa-users" style="color: #f59e0b;"></i> Across <?php echo $total_customers; ?> registered customers
        </div>
    </a>
</div>

<!-- Available Specialists Directory Card -->
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="main-vendors" style="margin-bottom: 4px;">Registered Service Specialists</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Verified service providers authorized to accept customer appointments.</p>
        </div>
        <a href="manage-vendor.php" class="btn-secondary">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Open Specialist Center
        </a>
    </div>

    <table class="tbl-full">
        <thead>
            <tr>
                <th>ID</th>
                <th>Specialist Name</th>
                <th>Trade Service</th>
                <th>Operating Town</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($vendor_result && mysqli_num_rows($vendor_result) > 0) {
                while ($vendor = mysqli_fetch_assoc($vendor_result)) {
                    $is_confirmed = ($vendor['status'] == 'Confirmed');
                    ?>
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">#<?php echo $vendor['id']; ?></td>
                        <td>
                            <strong style="color: #0f172a;"><?php echo htmlspecialchars($vendor['full_name']); ?></strong>
                        </td>
                        <td>
                            <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 600; color: #334155;">
                                <?php echo htmlspecialchars($vendor['service_type']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($vendor['town_name'] ?? 'Western Province'); ?></td>
                        <td><?php echo htmlspecialchars($vendor['contact_no']); ?></td>
                        <td style="color: #64748b;"><?php echo htmlspecialchars($vendor['email']); ?></td>
                        <td>
                            <span class="status-label <?php echo $is_confirmed ? 'status-confirmed' : 'status-pending'; ?>">
                                <i class="fa-solid <?php echo $is_confirmed ? 'fa-check' : 'fa-clock'; ?>"></i>
                                <?php echo htmlspecialchars($vendor['status']); ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <a href="delete-vendor.php?id=<?php echo $vendor['id']; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to remove this specialist?');">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='8' style='text-align: center; padding: 30px; color: #94a3b8;'>No specialists found in the directory.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php 
include('partials/footer.php'); 
?>
