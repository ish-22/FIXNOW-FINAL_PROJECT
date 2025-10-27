<?php
// Include the database connection file
include('partials/main.php');

// Check if category ID is passed
if (isset($_POST['offer_category_id'])) {  // Updated this to match the new form field name
    $category_id = $_POST['offer_category_id'];  // Use 'offer_category_id' to get the category ID

    // Fetch services based on selected category
    $service_query = "SELECT * FROM tbl_service WHERE category_id = '$category_id'";
    $service_result = mysqli_query($conn, $service_query);

    if (mysqli_num_rows($service_result) > 0) {
        // Loop through services and create options
        while ($service = mysqli_fetch_assoc($service_result)) {
            echo '<option value="' . $service['id'] . '" data-price="' . $service['booking_price'] . '">' . $service['service_type'] . '</option>';
        }
    } else {
        echo '<option value="">No services available</option>';
    }
}
?>
