<?php 
include('./partials/header.php'); 

if (!isset($_SESSION['customer_id'])) {
    echo "<div class='fn-container' style='padding: 80px 20px; text-align: center;'>
            <i class='fa-solid fa-lock fa-3x' style='color:#94a3b8; margin-bottom:15px;'></i>
            <h2 style='color:#0f172a;'>Sign In Required</h2>
            <p style='color:#64748b; margin-bottom:20px;'>Please sign in to view your scheduled bookings and service history.</p>
            <a href='signin.php' class='fn-btn fn-btn-primary'>Sign In Now</a>
          </div>";
    include('./partials/footer.php');
    exit();
}

$customer_id = intval($_SESSION['customer_id']);

// Fetch bookings securely
$bookings = db_fetch_all(
    $conn,
    "SELECT 
        b.id, b.service_id, b.customer_name, b.booking_date, b.time_slot, b.total_amount, b.platform_fee, b.vendor_payout,
        b.booking_status, b.task_status, b.payment_status,
        s.service_type, b.message, b.contact_number, 
        sp.full_name AS provider_name, sp.contact_no AS provider_phone
      FROM tbl_booking b
      LEFT JOIN tbl_service s ON b.service_id = s.id
      LEFT JOIN tbl_service_providers sp ON b.service_provider_id = sp.id
      WHERE b.customer_id = ? 
      ORDER BY b.id DESC",
    [$customer_id],
    "i"
);
?>

<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 25px 0;">
    <div class="fn-container">
        <h1 class="fn-section-title">My Service Bookings</h1>
        <p class="fn-section-subtitle">
            Track your appointment status, scheduled arrival windows, and escrow payment confirmations.
        </p>
    </div>
</div>

<section style="padding: 10px 0 80px 0;">
    <div class="fn-container">
        <?php if (!empty($bookings)): ?>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php foreach ($bookings as $b): 
                    $b_id = $b['id'];
                    $b_status = strtolower($b['booking_status'] ?? 'pending');
                    $t_status = strtolower($b['task_status'] ?? 'pending');
                    $p_status = strtolower($b['payment_status'] ?? 'pending');

                    $status_class = 'fn-status-pending';
                    if ($b_status === 'confirmed' || $b_status === 'approved') $status_class = 'fn-status-confirmed';
                    if ($b_status === 'cancelled' || $b_status === 'rejected') $status_class = 'fn-status-cancelled';
                ?>
                <div class="fn-card" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                    <!-- Card Top Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 14px;">
                        <div>
                            <span style="font-size: 13px; font-weight: 700; color: #0284c7; background: #e0f2fe; padding: 3px 8px; border-radius: 4px;">
                                Booking #<?php echo $b_id; ?>
                            </span>
                            <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-top: 6px;">
                                <?php echo htmlspecialchars($b['service_type'] ?? 'Specialist Service'); ?>
                            </h3>
                        </div>

                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <span class="fn-status <?php echo $status_class; ?>">
                                <i class="fa-solid fa-circle-dot fa-xs"></i> <?php echo ucfirst($b_status); ?>
                            </span>
                            <?php if ($p_status === 'paid'): ?>
                                <span class="fn-status fn-status-paid"><i class="fa-solid fa-lock"></i> Escrow Paid</span>
                            <?php else: ?>
                                <span class="fn-status fn-status-pending"><i class="fa-solid fa-clock"></i> Payment Pending</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; font-size: 14.5px;">
                        <div>
                            <span style="color: #64748b; font-size: 13px; display: block;">Assigned Specialist</span>
                            <strong style="color: #1e293b;"><?php echo htmlspecialchars($b['provider_name'] ?? 'Assigned Technician'); ?></strong>
                        </div>

                        <div>
                            <span style="color: #64748b; font-size: 13px; display: block;">Scheduled Arrival Window</span>
                            <strong style="color: #1e293b;">
                                <i class="fa-regular fa-calendar" style="color: #0284c7;"></i> <?php echo htmlspecialchars($b['booking_date']); ?> 
                                (<?php echo htmlspecialchars($b['time_slot'] ?? '09:00 AM - 11:00 AM'); ?>)
                            </strong>
                        </div>

                        <div>
                            <span style="color: #64748b; font-size: 13px; display: block;">Escrow Total</span>
                            <strong style="color: #0284c7; font-size: 16px;">Rs. <?php echo number_format($b['total_amount'], 2); ?> /=</strong>
                        </div>

                        <div>
                            <span style="color: #64748b; font-size: 13px; display: block;">Work Order Status</span>
                            <strong style="color: #334155; text-transform: capitalize;"><?php echo htmlspecialchars($t_status); ?></strong>
                        </div>
                    </div>

                    <?php if (!empty($b['message'])): ?>
                        <div style="background: #f8fafc; border-radius: 8px; padding: 12px 14px; font-size: 14px; color: #475569;">
                            <strong>Your Notes:</strong> <?php echo nl2br(htmlspecialchars($b['message'])); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Action Bar -->
                    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 10px; margin-top: 6px;">
                        <?php if ($p_status !== 'paid' && $b_status !== 'cancelled'): ?>
                            <a href="payment.php?booking_id=<?php echo $b_id; ?>" class="fn-btn fn-btn-primary" style="padding: 8px 18px; font-size: 13.5px;">
                                <i class="fa-solid fa-credit-card"></i> Complete Escrow Payment
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 70px 20px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <i class="fa-regular fa-calendar-xmark fa-3x" style="color: #94a3b8; margin-bottom: 16px;"></i>
                <h3 style="color: #1e293b; font-size: 1.5rem; margin-bottom: 8px;">No Bookings Yet</h3>
                <p style="color: #64748b; margin-bottom: 20px;">You haven't scheduled any services. Explore verified specialists in your area now.</p>
                <a href="vendors.php" class="fn-btn fn-btn-primary">Browse Specialists</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include('./partials/footer.php'); ?>
