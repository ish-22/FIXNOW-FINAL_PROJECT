<?php
include('./config/constant.php');

$booking_id = $_GET['booking_id'] ?? '';

if (!$booking_id) {
    die("Missing booking ID");
}

// Fetch booking
$booking_query = "SELECT * FROM tbl_booking WHERE id = '$booking_id'";
$booking_result = mysqli_query($conn, $booking_query);
if (mysqli_num_rows($booking_result) > 0) {
    $booking = mysqli_fetch_assoc($booking_result);
    $service_provider_id = $booking['service_provider_id'];
    $customer_id = $booking['customer_id'];
} else {
    die("Invalid booking ID");
}

// Update payment status
$update_query = "UPDATE tbl_booking SET payment_status = 'Paid' WHERE id = '$booking_id'";
$update_result = mysqli_query($conn, $update_query);

if ($update_result) {
    // Add notification for service provider
    $message = "New booking available. Please check.";
    $notify_query = "INSERT INTO tbl_notification (customer_id, serviceProvider_id, customer_notification_message, service_provider_notification_message, status_customer, status_provider, type, booking_id)
        VALUES ('$customer_id', '$service_provider_id', NULL, '$message', 'unread', 'unread', 'provider', '$booking_id')";
    mysqli_query($conn, $notify_query);

    echo "<h2>Payment successful!</h2><p>Your booking is confirmed.</p><a href='index.php'>Back to Home</a>";
} else {
    echo "Payment succeeded, but failed to update booking.";
}
?>
