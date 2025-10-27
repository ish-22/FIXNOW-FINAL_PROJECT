<?php
// Include the database connection
include('../config/constant.php');

if (isset($_POST['district_id'])) {
    $district_id = $_POST['district_id'];

    // Fetch towns based on the selected district
    $town_query = "SELECT * FROM tbl_towns WHERE district_id = '$district_id'";
    $town_result = mysqli_query($conn, $town_query);

    if (mysqli_num_rows($town_result) > 0) {
        echo '<option value="">Select Town</option>'; // Default option
        while ($town = mysqli_fetch_assoc($town_result)) {
            echo "<option value=\"{$town['id']}\">{$town['town_name']}</option>";
        }
    } else {
        echo '<option value="">No Towns Available</option>'; // No towns found
    }
}
?>
