<?php
/**
 * FixNow PayHere Webhook / IPN Listener
 * Automatically confirms customer bookings and vendor subscription payments
 */
include('config/constant.php');
include('config/payment.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $merchant_id = $_POST['merchant_id'] ?? '';
    $order_id = $_POST['order_id'] ?? '';
    $payhere_amount = $_POST['payhere_amount'] ?? '';
    $payhere_currency = $_POST['payhere_currency'] ?? '';
    $status_code = $_POST['status_code'] ?? '';
    $md5sig = $_POST['md5sig'] ?? '';

    $local_md5sig = strtoupper(
        md5(
            $merchant_id . 
            $order_id . 
            $payhere_amount . 
            $payhere_currency . 
            $status_code . 
            strtoupper(md5(PAYHERE_MERCHANT_SECRET))
        )
    );

    // Verify signature & status code (2 means success in PayHere)
    if (($local_md5sig === $md5sig) && ($status_code == 2)) {
        if (strpos($order_id, 'BOOKING-') === 0) {
            $booking_id = intval(str_replace('BOOKING-', '', $order_id));
            db_execute($conn, "UPDATE tbl_booking SET payment_status = 'Paid' WHERE id = ?", [$booking_id], "i");
            
            // Insert notification
            $booking = db_fetch_one($conn, "SELECT customer_id, service_provider_id FROM tbl_booking WHERE id = ?", [$booking_id], "i");
            if ($booking) {
                $msg = "New confirmed booking #" . $booking_id . " received. Check schedule.";
                db_execute(
                    $conn,
                    "INSERT INTO tbl_notification (customer_id, serviceProvider_id, service_provider_notification_message, status_customer, status_provider, type, booking_id) 
                     VALUES (?, ?, ?, 'unread', 'unread', 'provider', ?)",
                    [$booking['customer_id'], $booking['service_provider_id'], $msg, $booking_id],
                    "iisi"
                );
            }
        } elseif (strpos($order_id, 'REG-') === 0 || strpos($order_id, 'VENDOR-REG-') === 0) {
            $vendor_id = intval(preg_replace('/[^0-9]/', '', $order_id));
            db_execute($conn, "UPDATE tbl_service_providers SET registration_fee_paid = 'Yes', payment_status = 'Paid' WHERE id = ?", [$vendor_id], "i");
        } elseif (strpos($order_id, 'SUB-') === 0 || strpos($order_id, 'VENDOR-SUB-') === 0) {
            $parts = explode('-', $order_id);
            $vendor_id = isset($parts[1]) ? intval($parts[1]) : intval(preg_replace('/[^0-9]/', '', $order_id));
            $vendor = db_fetch_one($conn, "SELECT * FROM tbl_service_providers WHERE id = ?", [$vendor_id], "i");
            if ($vendor) {
                db_execute(
                    $conn,
                    "INSERT INTO tbl_invoice (service_provider_id, service_type_id, mobile_no, email, paid_month, paid_amount, message, status) 
                     VALUES (?, ?, ?, ?, ?, ?, 'PayHere Online Payment Received', 'paid')",
                    [$vendor_id, $vendor['servicetype_id'], $vendor['contact_no'], $vendor['email'], date('F Y'), floatval($payhere_amount)],
                    "iisssd"
                );
            }
        }
        http_response_code(200);
        echo "Payment successfully verified and updated.";
        exit();
    } else {
        http_response_code(400);
        echo "Payment verification signature mismatch.";
        exit();
    }
}
?>

