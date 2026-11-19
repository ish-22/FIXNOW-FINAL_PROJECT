<?php
include('./config/constant.php');

if (!isset($_SESSION['customer_id'])) {
    header("Location: signin.php");
    exit();
}

// Support both vendor_id and service_provider_id parameter names
$vendor_id = 0;
if (isset($_GET['vendor_id'])) {
    $vendor_id = intval($_GET['vendor_id']);
} elseif (isset($_GET['service_provider_id'])) {
    $vendor_id = intval($_GET['service_provider_id']);
} elseif (isset($_POST['service_provider_id'])) {
    $vendor_id = intval($_POST['service_provider_id']);
} elseif (isset($_POST['vendor_id'])) {
    $vendor_id = intval($_POST['vendor_id']);
}

if ($vendor_id <= 0) {
    die("
    <div style='max-width:500px;margin:80px auto;padding:40px;background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,0.08);text-align:center;font-family:sans-serif;'>
        <div style='color:#ef4444;font-size:48px;margin-bottom:15px;'>⚠️</div>
        <h2 style='color:#0f172a;margin-bottom:10px;'>Invalid Specialist Selected</h2>
        <p style='color:#64748b;margin-bottom:25px;'>Please choose an active specialist from the directory to schedule your appointment.</p>
        <a href='vendors.php' style='display:inline-block;padding:12px 24px;background:#0284c7;color:#fff;text-decoration:none;border-radius:50px;font-weight:700;'>Browse Verified Specialists</a>
    </div>
    ");
}

// Fetch dates that should be disabled (cannot be selected)
$booked_dates_rows = db_fetch_all(
    $conn,
    "SELECT booking_date FROM tbl_booking 
     WHERE service_provider_id = ? 
     AND (booking_status = 'Pending' OR task_status = 'Pending' OR booking_status = 'Confirmed') 
     AND booking_status != 'Cancelled'",
    [$vendor_id],
    "i"
);

$booked_dates = [];
foreach ($booked_dates_rows as $row) {
    $booked_dates[] = trim($row['booking_date']);
}
$booked_dates_json = json_encode($booked_dates);

// Fetch vendor details with parameterized query
$vendor = db_fetch_one(
    $conn,
    "SELECT tbl_service_providers.*, tbl_service.service_type, tbl_service.booking_price, tbl_service.id as service_id, tbl_towns.town_name
     FROM tbl_service_providers
     JOIN tbl_service ON tbl_service.id = tbl_service_providers.servicetype_id
     LEFT JOIN tbl_towns ON tbl_towns.id = tbl_service_providers.town_id
     WHERE tbl_service_providers.id = ? AND tbl_service_providers.status = 'Confirmed'",
    [$vendor_id],
    "i"
);

if (!$vendor) {
    die("
    <div style='max-width:500px;margin:80px auto;padding:40px;background:#fff;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,0.08);text-align:center;font-family:sans-serif;'>
        <div style='color:#f59e0b;font-size:48px;margin-bottom:15px;'>🔍</div>
        <h2 style='color:#0f172a;margin-bottom:10px;'>Specialist Not Available</h2>
        <p style='color:#64748b;margin-bottom:25px;'>The specialist you requested is currently inactive or not confirmed.</p>
        <a href='vendors.php' style='display:inline-block;padding:12px 24px;background:#0284c7;color:#fff;text-decoration:none;border-radius:50px;font-weight:700;'>Browse Other Specialists</a>
    </div>
    ");
}

// Fetch active offers
$offer = db_fetch_one(
    $conn,
    "SELECT * FROM tbl_offer WHERE offer_type_id = ? AND status = 'active'",
    [intval($vendor['servicetype_id'])],
    "i"
);

$booking_price = floatval($vendor['booking_price']);
if ($offer && isset($offer['discounted_price']) && floatval($offer['discounted_price']) > 0) {
    $booking_price = floatval($offer['discounted_price']);
}

// Fetch average rating
$rank = db_fetch_one($conn, "SELECT AVG(rating) AS average_rating FROM tbl_feedback WHERE vendor_id = ?", [$vendor_id], "i");
$average_rating = ($rank && $rank['average_rating']) ? round(floatval($rank['average_rating']), 1) : 0;

// Fetch count of completed tasks
$completed_data = db_fetch_one($conn, "SELECT COUNT(*) AS completed_tasks FROM tbl_booking WHERE service_provider_id = ? AND task_status = 'Completed'", [$vendor_id], "i");
$completed_tasks = $completed_data ? intval($completed_data['completed_tasks']) : 0;

$overall_ranking = round($average_rating + $completed_tasks, 1);

// Calculate price tiers
$display_price = $booking_price;
if ($overall_ranking >= 5) {
    $display_price += 200; // Rs. 200 priority expert rate
}

$platform_fee = round($display_price * 0.10, 2); // 10% Platform fee
$vendor_payout = round($display_price - $platform_fee, 2);

$logged_customer_id = intval($_SESSION['customer_id']);
$customer = db_fetch_one($conn, "SELECT * FROM tbl_customer WHERE id = ?", [$logged_customer_id], "i");

if (!$customer) {
    header("Location: signin.php");
    exit();
}

$cus_name = $customer['cus_name'];
$cus_email = $customer['cus_email'];
$cus_phone = $customer['cus_phone'];
$cus_address = $customer['cus_address'];

// Handle Booking Submission
if (isset($_POST['submit-booking'])) {
    $customer_name = trim($_POST['customer_name']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $booking_date = trim($_POST['booking_date']);
    $time_slot = isset($_POST['time_slot']) ? trim($_POST['time_slot']) : '09:00 AM - 11:00 AM';
    $message = trim($_POST['message']);
    $service_id = intval($vendor['service_id']);

    // Check conflict for same vendor on same date and time slot
    $conflict = db_fetch_one(
        $conn,
        "SELECT id FROM tbl_booking 
         WHERE service_provider_id = ? AND booking_date = ? AND time_slot = ? AND booking_status != 'Cancelled'",
        [$vendor_id, $booking_date, $time_slot],
        "iss"
    );

    if ($conflict) {
        $booking_error = "This time slot is already booked for this specialist. Please choose another date or time slot.";
    } else {
        $insert_res = db_execute(
            $conn,
            "INSERT INTO tbl_booking (service_provider_id, service_id, customer_name, contact_number, email, address, booking_date, time_slot, message, total_amount, platform_fee, vendor_payout, payment_status, task_status, customer_id)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', 'Pending', ?)",
            [$vendor_id, $service_id, $customer_name, $contact_number, $email, $address, $booking_date, $time_slot, $message, $display_price, $platform_fee, $vendor_payout, $logged_customer_id],
            "iisssssssdddi"
        );

        if (!empty($insert_res['success'])) {
            $booking_id = $insert_res['insert_id'];
            header("Location: payment.php?booking_id=$booking_id");
            exit();
        } else {
            $booking_error = "Could not create booking: " . ($insert_res['error'] ?? 'Database error');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Service Booking | FIXNOW</title>
    <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/book_vendor.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        .ui-datepicker-unselectable .ui-state-default {
            background: #ff6961 !important;
            color: white !important;
            text-decoration: line-through;
        }
        .price-breakdown {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
            margin: 18px 0;
            font-size: 14.5px;
        }
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            color: #475569;
        }
        .price-row.total {
            border-top: 1.5px solid #cbd5e1;
            margin-top: 8px;
            padding-top: 10px;
            font-weight: bold;
            font-size: 16.5px;
            color: #0f172a;
        }
        .slot-select {
            width: 100%;
            padding: 12px 14px;
            font-size: 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            background: #fff;
        }
        .error-banner {
            background: #fee2e2;
            color: #b91c1c;
            padding: 14px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <button type="button" class="back-button" onclick="window.location.href='vendors-details.php?vendor_id=<?php echo $vendor_id; ?>'">&larr; Back to Specialist</button>

        <div class="form-header">
            <img src="./images/vendor_images/<?php echo htmlspecialchars($vendor['vendor_image']); ?>" alt="Vendor Image">
            <h1><?php echo htmlspecialchars($vendor['full_name']); ?></h1>
            <p>Verified <?php echo htmlspecialchars($vendor['service_type']); ?> in <?php echo htmlspecialchars($vendor['town_name'] ?? 'Your Area'); ?></p>
        </div>

        <div class="booking-details">
            <strong>Operating Hours:</strong> <?php echo htmlspecialchars($vendor['available_dates_and_times']); ?><br>
            <strong>Service Ranking:</strong> ⭐ <?php echo $overall_ranking; ?> / 5.0 (<?php echo $completed_tasks; ?> Completed Tasks)
        </div>

        <div class="price-breakdown">
            <div class="price-row">
                <span>Base Service Rate:</span>
                <span>Rs. <?php echo number_format($vendor_payout, 2); ?></span>
            </div>
            <div class="price-row">
                <span>Platform Safety & Guarantee (10%):</span>
                <span>Rs. <?php echo number_format($platform_fee, 2); ?></span>
            </div>
            <div class="price-row total">
                <span>Total Escrow Amount:</span>
                <span style="color: #0284c7;">Rs. <?php echo number_format($display_price, 2); ?> /=</span>
            </div>
        </div>

        <?php if (!empty($booking_error)): ?>
            <div class="error-banner">
                <i class="fas fa-exclamation-circle"></i>
                <div><?php echo htmlspecialchars($booking_error); ?></div>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <input type="hidden" name="service_provider_id" value="<?php echo $vendor_id; ?>">
            <input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>">

            <div class="form-group">
                <label for="name"><i class="fas fa-user"></i> Full Name</label>
                <input type="text" id="name" name="customer_name" value="<?php echo htmlspecialchars($cus_name); ?>" required>
            </div>

            <div class="form-group">
                <label for="contact"><i class="fas fa-phone-alt"></i> Contact Number</label>
                <input type="tel" id="contact" name="contact_number" value="<?php echo htmlspecialchars($cus_phone); ?>" required>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i> Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cus_email); ?>" required>
            </div>

            <div class="form-group">
                <label for="address"><i class="fas fa-home"></i> Service Address</label>
                <textarea id="address" name="address" required><?php echo htmlspecialchars($cus_address); ?></textarea>
            </div>

            <div class="form-group">
                <label for="date"><i class="fas fa-calendar-alt"></i> Service Date</label>
                <input type="text" id="date" name="booking_date" placeholder="Click to choose appointment date" required readonly>
            </div>

            <div class="form-group">
                <label for="time_slot"><i class="fas fa-clock"></i> Select Time Window</label>
                <select id="time_slot" name="time_slot" class="slot-select" required>
                    <option value="">-- Choose Arrival Time Slot --</option>
                    <option value="08:00 AM - 10:00 AM">08:00 AM - 10:00 AM (Early Morning)</option>
                    <option value="10:00 AM - 12:00 PM">10:00 AM - 12:00 PM (Mid Morning)</option>
                    <option value="01:00 PM - 03:00 PM">01:00 PM - 03:00 PM (Early Afternoon)</option>
                    <option value="03:00 PM - 05:00 PM">03:00 PM - 05:00 PM (Late Afternoon)</option>
                    <option value="05:00 PM - 07:00 PM">05:00 PM - 07:00 PM (Evening)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message"><i class="fas fa-comment-dots"></i> Describe Task Requirements</label>
                <textarea id="message" name="message" placeholder="Provide any details about the issue, required tools, or access instructions"></textarea>
            </div>

            <input type="hidden" name="total_amount" value="<?php echo $display_price; ?>">

            <button type="submit" name="submit-booking" class="submit-button">
                <i class="fa-solid fa-lock"></i> Proceed to Escrow Payment (Rs. <?php echo number_format($display_price, 2); ?>)
            </button>
        </form>
    </div>

    <script>
    $(document).ready(function () {
        var bookedDates = <?php echo $booked_dates_json; ?>;

        $("#date").datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0,
            beforeShowDay: function (date) {
                var dateString = $.datepicker.formatDate('yy-mm-dd', date);
                if (bookedDates.includes(dateString)) {
                    return [false, "ui-datepicker-unselectable", "Fully Booked"];
                }
                return [true, "", "Available"];
            }
        });
    });
    </script>
</body>
</html>
