<?php
include('../config/constant.php');

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $query = "SELECT * FROM tbl_service WHERE category_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $service_types = [];
    while ($row = $result->fetch_assoc()) {
        $service_types[] = $row;
    }

    echo json_encode($service_types);
}
?>
