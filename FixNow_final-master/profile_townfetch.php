
<?php
include('./config/constant.php');

// Check if the district_id is passed
if (isset($_GET['district_id'])) {
    $district_id = $_GET['district_id'];

    // Fetch towns for the selected district
    $query = "SELECT * FROM tbl_towns WHERE district_id = '$district_id'";
    $result = mysqli_query($conn, $query);

    $towns = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $towns[] = $row;
    }

    // Return the towns as a JSON response
    echo json_encode($towns);
}
?>
