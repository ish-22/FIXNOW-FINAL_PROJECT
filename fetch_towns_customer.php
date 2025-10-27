<?php
include('./config/constant.php');

$district_id = isset($_GET['district_id']) ? $_GET['district_id'] : '';

// Fetch towns for the selected district
$query = "SELECT * FROM tbl_towns WHERE district_id = '$district_id'";
$result = mysqli_query($conn, $query);

$towns = [];
while ($town = mysqli_fetch_assoc($result)) {
    $towns[] = $town;
}

// Return towns as JSON
echo json_encode($towns);
?>
