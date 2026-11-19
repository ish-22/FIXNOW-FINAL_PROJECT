<?php
include('./config/constant.php');

$booking_id = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;

if ($booking_id <= 0) {
    die("<div style='text-align:center;padding:50px;font-family:sans-serif;'><h2>Missing or invalid booking ID</h2><p><a href='index.php'>Return to Home</a></p></div>");
}

// Fetch booking securely
$booking_query = "SELECT b.*, sp.full_name AS provider_name, s.service_type 
                  FROM tbl_booking b 
                  LEFT JOIN tbl_service_providers sp ON b.service_provider_id = sp.id 
                  LEFT JOIN tbl_service s ON b.service_id = s.id 
                  WHERE b.id = ?";
$booking = db_fetch_one($booking_query, "i", [$booking_id]);

if (!$booking) {
    die("<div style='text-align:center;padding:50px;font-family:sans-serif;'><h2>Invalid booking ID</h2><p><a href='index.php'>Return to Home</a></p></div>");
}

// Update payment status securely
$update_query = "UPDATE tbl_booking SET payment_status = 'Paid', booking_status = 'Confirmed' WHERE id = ?";
$update_res = db_execute($update_query, "i", [$booking_id]);

if (!empty($update_res['success'])) {
    // Notify vendor
    $msg = "New confirmed booking #" . $booking_id . " received. Please check your schedule.";
    $notify_query = "INSERT INTO tbl_notification (customer_id, serviceProvider_id, customer_notification_message, service_provider_notification_message, status_customer, status_provider, type, booking_id)
                     VALUES (?, ?, NULL, ?, 'unread', 'unread', 'provider', ?)";
    db_execute($notify_query, "iisi", [$booking['customer_id'], $booking['service_provider_id'], $msg, $booking_id]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment Successful | FIXNOW</title>
    <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'League Spartan', sans-serif; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 50%, #a7f3d0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .success-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.15);
            max-width: 550px;
            width: 100%;
            padding: 40px 30px;
            text-align: center;
            animation: popIn 0.35s ease-out;
        }
        @keyframes popIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .icon-circle {
            width: 80px;
            height: 80px;
            background: #dcfce7;
            color: #16a34a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 38px;
            margin: 0 auto 20px;
        }
        h2 { color: #065f46; font-size: 26px; font-weight: 700; margin-bottom: 8px; }
        p { color: #475569; font-size: 15px; margin-bottom: 20px; }
        .details-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 24px;
            text-align: left;
            font-size: 14px;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            border-bottom: 1px dashed #cbd5e1;
        }
        .details-row:last-child { border-bottom: none; }
        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        .btn {
            padding: 12px 20px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn-primary { background: #10b981; color: white; }
        .btn-primary:hover { background: #059669; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background: #cbd5e1; }
    </style>
</head>
<body>

<div class="success-card">
    <div class="icon-circle">
        <i class="fa-solid fa-check"></i>
    </div>
    <h2>Payment Confirmed!</h2>
    <p>Your payment of <strong>Rs. <?php echo number_format($booking['total_amount'], 2); ?></strong> is secured in platform escrow.</p>

    <div class="details-box">
        <div class="details-row"><span>Booking ID:</span> <strong>#<?php echo $booking_id; ?></strong></div>
        <div class="details-row"><span>Service:</span> <strong><?php echo htmlspecialchars($booking['service_type'] ?? 'Service'); ?></strong></div>
        <div class="details-row"><span>Provider:</span> <strong><?php echo htmlspecialchars($booking['provider_name'] ?? 'Vendor'); ?></strong></div>
        <div class="details-row"><span>Scheduled Slot:</span> <strong><?php echo htmlspecialchars($booking['booking_date'] . ' (' . ($booking['time_slot'] ?? 'Anytime') . ')'); ?></strong></div>
        <div class="details-row"><span>Payment Status:</span> <strong style="color: #10b981;">PAID (Escrow)</strong></div>
    </div>

    <div class="btn-group">
        <a href="bookingHistory.php" class="btn btn-primary"><i class="fa-solid fa-calendar-check"></i> View Bookings</a>
        <a href="index.php" class="btn btn-secondary">Return to Home</a>
    </div>
</div>

</body>
</html>
