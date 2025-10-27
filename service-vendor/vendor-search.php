<?php include './partials/headerwithoutcss.php'; ?>
<link rel="stylesheet" href="./assets/css/custom.css">

<?php 


// Get the selected service_id and city from the URL
$service_id = isset($_GET['service_type']) ? $_GET['service_type'] : ''; // Get service_id from URL if set
$city = isset($_GET['city']) ? $_GET['city'] : ''; // Get city from URL if set

// Fetch vendors based on the selected service_id and city (if selected)
$search_query =  "SELECT tbl_service_providers.*, tbl_service.service_type, tbl_towns.town_name 
                 FROM tbl_service_providers 
                 JOIN tbl_service ON tbl_service.id = tbl_service_providers.servicetype_id
                 LEFT JOIN tbl_towns ON tbl_towns.id = tbl_service_providers.town_id
                 WHERE tbl_service_providers.servicetype_id = '$service_id' 
                 AND tbl_service_providers.status = 'Confirmed'"; // Only show vendors with "Confirmed" status





// Add the city filter if a city is selected
if ($city != '') {
    $search_query .= " AND tbl_service_providers.town_id = '$city'";
}

// Fetch towns for the city filter
$towns_query = "SELECT * FROM tbl_towns";
$towns_result = mysqli_query($conn, $towns_query);

$vendor_result = mysqli_query($conn, $search_query);
$service_count = mysqli_num_rows($vendor_result); // Get the total number of vendors
?>

<section class="hero section" id="hero">
  <div class="container">
    <div class="hero-content">
      <p class="section-text">&nbsp;</p>
      <h1 class="h1">All Vendors of Selected Type</h1>

      <!-- Filter Options -->
      <div class="filter-options" style="margin-top: 20px; display: flex; gap: 20px; justify-content: center;">
        <select id="cityFilter" class="filter-select" style="padding: 10px; font-size: 1.1rem; border-radius: 5px; border: 1px solid #ccc; width: 200px;">
          <option value="">Select City</option>
          <?php
            // Populate the dropdown with cities (towns)
            while ($town_row = mysqli_fetch_assoc($towns_result)) {
                $selected = ($town_row['id'] == $city) ? 'selected' : '';  // Preselect the town if it's already selected in the URL
                echo "<option value='{$town_row['id']}' {$selected}>{$town_row['town_name']}</option>";
            }
          ?>
        </select>
      </div>

      <!-- Services Grid Section -->
      <div class="services-grid">
        <?php
        // Display vendor details
        if (mysqli_num_rows($vendor_result) > 0) {
            $service_count = mysqli_num_rows($vendor_result); // Get number of service providers
            while ($vendor = mysqli_fetch_assoc($vendor_result)) {
                $vendor_name = $vendor['full_name'];
                $vendor_image = $vendor['vendor_image'] ? $vendor['vendor_image'] : 'default.jpg'; // Handle missing images
                $service_type = $vendor['service_type'];
                $contact_number = $vendor['contact_no'];
                $vendor_id = $vendor['id']; // Correct vendor ID

                // Apply the CSS class for small or normal box based on service count
                $box_class = ($service_count <= 2) ? 'service-box-small' : 'service-box-normal';

                echo "
                <div class='service-box {$box_class}'>
                    <img src='../images/vendor_images/{$vendor_image}' alt='{$vendor_name}' class='service-image' />
                    <div class='service-details'>
                    <span style='display: inline-block; background-color: #28a745; color: white; padding: 2px 2px; font-size: 12px; font-weight: bold; border-radius: 5px; margin-bottom: 5px;'>✔ Verified</span>
                        <h3 class='service-title'>{$vendor_name}</h3>
                        <p class='service-description'>Service Type: {$service_type}</p>
                        <p class='service-description'>Contact: {$contact_number}</p>
                        <!-- Correctly passing the vendor_id to the next page -->
            <a href='vendors-details.php?vendor_id={$vendor_id}' class='btn btn-primary' id='find-job-btn' style='padding: 10px 10px; text-decoration: none; font-size: 2.5rem; border-radius: 5px; display: block; margin: 0 auto; width: 200px;'>See More</a>
                    </div>
                </div>
                ";
            }
        } else {
            echo "<p>No vendors found for your search. Please select another filter.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</section>

<?php include './partials/footer.php'; ?>

<script>
// JavaScript to filter by city (without reloading the page)
document.getElementById('cityFilter').addEventListener('change', function() {
    const city = this.value;
    const serviceType = '<?php echo $service_id; ?>';  // Use the selected service type ID from the URL

    // Redirect to the same page with the selected service type and city
    window.location.href = `vendor-search.php?service_type=${serviceType}&city=${city}`;
});
</script>
