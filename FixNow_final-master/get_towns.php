<?php
include('./config/constant.php');


if (isset($_GET['district_id'])) {
    $district_id = $_GET['district_id'];
    $query = "SELECT * FROM tbl_towns WHERE district_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $district_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $towns = [];
    while ($row = $result->fetch_assoc()) {
        $towns[] = $row;
    }

    echo json_encode($towns);
}
?>
