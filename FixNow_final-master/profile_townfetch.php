<?php
include('./config/constant.php');
header('Content-Type: application/json; charset=utf-8');

$district_id = isset($_GET['district_id']) ? intval($_GET['district_id']) : 0;

$towns = [];
if ($district_id > 0) {
    $towns = db_fetch_all($conn, "SELECT id, town_name FROM tbl_towns WHERE district_id = ? ORDER BY town_name ASC", [$district_id], "i");
}

echo json_encode($towns);
exit();
?>
