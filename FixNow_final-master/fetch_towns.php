
<?php
include('./config/constant.php'); 

// Check if district_id is set in the POST request
if (isset($_POST['district_id'])) {
    $district_id = $_POST['district_id']; // Get the district ID from the POST request

    // Query the database for towns related to the selected district
    $query = "SELECT * FROM tbl_towns WHERE district_id = '$district_id'";
    $result = mysqli_query($conn, $query);

    // Check if any towns exist and populate the dropdown
    if (mysqli_num_rows($result) > 0) {
        echo "<option value=''>Select Town</option>";  // Default prompt
        while ($town = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $town['id'] . "'>" . $town['town_name'] . "</option>";
        }
    } else {
        echo "<option value=''>No towns available</option>";
    }
}
?>
