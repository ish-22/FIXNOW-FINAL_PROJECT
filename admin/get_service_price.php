<?php
include('../config/constant.php');
include('partials/login-check.php');

header('Content-Type: application/json');

if (isset($_POST['service_id']) || isset($_GET['service_id'])) {
    $service_id = intval($_POST['service_id'] ?? $_GET['service_id']);
    $service = db_fetch_one($conn, "SELECT id, booking_price FROM tbl_service WHERE id = ?", [$service_id], "i");

    if ($service) {
        echo json_encode([
            'success' => true,
            'price' => floatval($service['booking_price'])
        ]);
        exit();
    }
}

echo json_encode([
    'success' => false,
    'price' => 0.00
]);
?>

