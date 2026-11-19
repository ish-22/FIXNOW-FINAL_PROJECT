<?php
include('partials/main.php');

if (isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);

    $services = db_fetch_all($conn, "SELECT id, service_type, booking_price FROM tbl_service WHERE category_id = ? ORDER BY service_type ASC", [$category_id], "i");

    if (!empty($services)) {
        foreach ($services as $service) {
            echo '<option value="' . htmlspecialchars($service['id']) . '" data-price="' . htmlspecialchars($service['booking_price']) . '">' . htmlspecialchars($service['service_type']) . '</option>';
        }
    } else {
        echo '<option value="">No services available</option>';
    }
}
?>
