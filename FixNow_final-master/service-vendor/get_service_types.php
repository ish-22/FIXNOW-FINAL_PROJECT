<?php
include('../config/constant.php');
header('Content-Type: application/json; charset=utf-8');

$category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

$service_types = [];
if ($category_id > 0) {
    $service_types = db_fetch_all($conn, "SELECT id, category_id, service_type, about_service, service_image, booking_price FROM tbl_service WHERE category_id = ? ORDER BY service_type ASC", [$category_id], "i");
}

echo json_encode($service_types);
exit();
?>
