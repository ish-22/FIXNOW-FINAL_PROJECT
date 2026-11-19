<?php 
include 'partials/header.php'; 

$vendor_id = isset($_SESSION['vendor_id']) ? intval($_SESSION['vendor_id']) : 0;
if ($vendor_id <= 0) {
    header("Location: ../signin.php");
    exit();
}

// Fetch bookings securely with time_slot and payout
$bookings = db_fetch_all(
    $conn,
    "SELECT b.id, b.service_id, b.customer_name, b.email, b.address, b.booking_date, b.time_slot, b.total_amount, b.vendor_payout,
            LOWER(b.booking_status) AS booking_status, 
            LOWER(b.task_status) AS task_status, 
            b.payment_status,
            s.service_type, b.message, b.contact_number, sp.full_name AS provider_name
     FROM tbl_booking b
     JOIN tbl_service s ON b.service_id = s.id
     JOIN tbl_service_providers sp ON b.service_provider_id = sp.id
     WHERE b.service_provider_id = ? AND b.payment_status = 'Paid'
     ORDER BY b.id DESC",
    [$vendor_id],
    "i"
);
?>

<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 25px 0;">
    <div class="fn-container">
        <h1 class="fn-section-title">Specialist Work Orders & Appointments</h1>
        <p class="fn-section-subtitle">
            Manage incoming client appointments, scheduled time slots, and confirm task completion.
        </p>
    </div>
</div>

<section style="padding: 10px 0 80px 0;">
    <div class="fn-container">
        <?php if (!empty($bookings)): ?>
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <?php foreach ($bookings as $b): 
                    $b_id = $b['id'];
                    $b_status = $b['booking_status'];
                    $t_status = $b['task_status'];
                    $payout = $b['vendor_payout'] > 0 ? $b['vendor_payout'] : ($b['total_amount'] * 0.90);

                    $status_class = 'fn-status-pending';
                    if ($b_status === 'confirmed' || $b_status === 'approved') $status_class = 'fn-status-confirmed';
                    if ($b_status === 'cancelled' || $b_status === 'rejected') $status_class = 'fn-status-cancelled';

                    $hideAllButtons = ($b_status === 'cancelled' || $t_status === 'completed');
                    $showOnlyComplete = ($b_status === 'confirmed');
                ?>
                <div class="fn-card" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                    <!-- Order Top Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; border-bottom: 1px dashed #e2e8f0; padding-bottom: 14px;">
                        <div>
                            <span style="font-size: 13px; font-weight: 700; color: #0284c7; background: #e0f2fe; padding: 3px 8px; border-radius: 4px;">
                                Order #<?php echo $b_id; ?> &bull; <?php echo htmlspecialchars($b['service_type']); ?>
                            </span>
                            <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-top: 6px;">
                                Client: <?php echo htmlspecialchars($b['customer_name']); ?>
                            </h3>
                        </div>

                        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                            <span class="fn-status <?php echo $status_class; ?>">
                                <i class="fa-solid fa-circle-dot fa-xs"></i> Booking: <?php echo ucfirst($b_status); ?>
                            </span>
                            <span class="fn-status fn-status-paid">
                                <i class="fa-solid fa-lock"></i> Escrow Secured
                            </span>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; font-size: 14.5px;">
                        <div>
                            <span style="color: #64748b; font-size: 13px; display: block;">Arrival Window</span>
                            <strong style="color: #1e293b;">
                                <i class="fa-regular fa-calendar" style="color: #0284c7;"></i> <?php echo htmlspecialchars($b['booking_date']); ?> 
                                (<?php echo htmlspecialchars($b['time_slot'] ?? '09:00 AM - 11:00 AM'); ?>)
                            </strong>
                        </div>

                        <div>
                            <span style="color: #64748b; font-size: 13px; display: block;">Client Contact</span>
                            <strong style="color: #1e293b;">
                                <i class="fa-solid fa-phone" style="color: #0284c7;"></i> <?php echo htmlspecialchars($b['contact_number']); ?>
                            </strong>
                        </div>

                        <div>
                            <span style="color: #64748b; font-size: 13px; display: block;">Net Specialist Payout (90%)</span>
                            <strong style="color: #15803d; font-size: 17px;">
                                Rs. <?php echo number_format($payout, 2); ?> /=
                            </strong>
                        </div>

                        <div>
                            <span style="color: #64748b; font-size: 13px; display: block;">Task Progress</span>
                            <strong style="color: #334155; text-transform: capitalize;">
                                <?php echo htmlspecialchars($t_status); ?>
                            </strong>
                        </div>
                    </div>

                    <?php if (!empty($b['address'])): ?>
                        <div style="background: #f8fafc; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #475569;">
                            <i class="fa-solid fa-location-dot" style="color: #0284c7; margin-right: 6px;"></i>
                            <strong>Service Location:</strong> <?php echo htmlspecialchars($b['address']); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($b['message'])): ?>
                        <div style="background: #f8fafc; border-radius: 8px; padding: 10px 14px; font-size: 14px; color: #475569;">
                            <strong>Client Instructions:</strong> <?php echo nl2br(htmlspecialchars($b['message'])); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <?php if (!$hideAllButtons): ?>
                        <form action="handle_booking_action.php" method="POST" style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 6px; flex-wrap: wrap;">
                            <input type="hidden" name="booking_id" value="<?php echo $b_id; ?>">
                            <input type="hidden" name="service_provider_id" value="<?php echo $vendor_id; ?>">

                            <?php if (!$showOnlyComplete): ?>
                                <button type="submit" name="action" value="cancel" class="fn-btn" style="background: #fef2f2; color: #b91c1c; border: 1px solid #fee2e2; padding: 8px 16px; font-size: 13.5px;">
                                    <i class="fa-solid fa-xmark"></i> Decline
                                </button>
                                <button type="submit" name="action" value="confirm" class="fn-btn fn-btn-primary" style="padding: 8px 16px; font-size: 13.5px;">
                                    <i class="fa-solid fa-check"></i> Accept Booking
                                </button>
                            <?php endif; ?>

                            <button type="submit" name="action" value="complete" class="fn-btn" style="background: linear-gradient(135deg, #10b981, #059669); color: #ffffff; padding: 8px 18px; font-size: 13.5px;">
                                <i class="fa-solid fa-circle-check"></i> Mark Task Completed
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 70px 20px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <i class="fa-regular fa-clipboard fa-3x" style="color: #94a3b8; margin-bottom: 16px;"></i>
                <h3 style="color: #1e293b; font-size: 1.5rem; margin-bottom: 8px;">No Paid Bookings Yet</h3>
                <p style="color: #64748b;">Confirmed customer appointments will appear here with scheduled time slots and payout details.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Specialist Portal Footer -->
<footer style="background: #0f172a; color: #94a3b8; padding: 30px 0; text-align: center; font-size: 13.5px; border-top: 1px solid #1e293b;">
    <div class="fn-container">
        &copy; <?php echo date('Y'); ?> FIXNOW Specialist Portal. All rights reserved.
    </div>
</footer>

</body>
</html>
