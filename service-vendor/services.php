<?php include './partials/headerwithoutcss.php'; ?>

<link rel="stylesheet" href="./assets/css/custom.css">

<?php


// Fetch categories from the tbl_category table
$categories_query = "SELECT * FROM tbl_category";
$categories_result = mysqli_query($conn, $categories_query);

// Fetch services from the tbl_service table
$services_query = "SELECT * FROM tbl_service";
$services_result = mysqli_query($conn, $services_query);
?>

<section class="hero section" id="hero">
  <div class="container">
    <div class="hero-content">
      <h1 class="h1">Find the Best Services for Your Needs</h1>
      <p class="section-text">Search and filter from a variety of service categories tailored just for you.</p>
      
      <!-- Search Bar Section -->
      <div class="search-filter-bar">
        <input type="text" id="searchBar" class="search-input" placeholder="Search services or categories..." />
        <button id="filterButton" class="btn btn-secondary">Filter</button>
      </div>

      <!-- Service Categories Section -->
      <div class="service-categories">
        <div class="category-list-container">
          <ul class="categories-list" id="category-list">
            <?php
              while ($category = mysqli_fetch_assoc($categories_result)) {
                  echo "<li><button class='category-btn' data-category-id='{$category['id']}'>{$category['category_name']}</button></li>";
              }
            ?>
          </ul>
        </div>
      </div>

      <!-- Services Grid Section -->
<div class="services-grid" id="services-grid">
    <?php
      while ($service = mysqli_fetch_assoc($services_result)) {
        // Fetch the image from the service_image directory
        $service_image_path = '../images/service_images/' . $service['service_image']; // Correct path to the service image

        echo "
        <div class='service-box' data-category-id='{$service['category_id']}' data-service-name='{$service['service_type']}'>
          <img src='{$service_image_path}' alt='{$service['service_image']}' class='service-image' />
          <div class='service-details'>
            <h3 class='service-title'>{$service['service_type']}</h3>
            <p class='service-description'>{$service['about_service']}</p>
            <button class='btn btn-primary' onclick=\"window.location.href='vendor-search.php?service_type={$service['id']}'\">See more..</button>
          </div>
        </div>
        ";
      }
    ?>
</div>


      <!-- No services message -->
      <div id="no-services-message" style="display: none; text-align: center; color: #ff0000;">
        <p>No service types are available under this category or for this search term.</p>
      </div>
    </div>
  </div>
</section>

<?php include './partials/footer.php'; ?>

<script>
// Filter services based on the selected category
document.querySelectorAll('.category-btn').forEach(button => {
  button.addEventListener('click', function() {
    const categoryId = this.getAttribute('data-category-id');
    filterServices(categoryId);
  });
});

// Function to filter services by category
function filterServices(categoryId) {
  let isServiceAvailable = false; // Flag to check if any service is available for the selected category

  // Show all services initially
  const allServices = document.querySelectorAll('.service-box');
  allServices.forEach(service => {
    service.style.display = 'none'; // Hide all services
    if (service.getAttribute('data-category-id') === categoryId) {
      service.style.display = 'block'; // Show services matching the category
      isServiceAvailable = true; // If a service is found, set the flag to true
    }
  });

  // Display the "No services available" message if no services are found for the selected category
  const noServicesMessage = document.getElementById('no-services-message');
  if (isServiceAvailable) {
    noServicesMessage.style.display = 'none'; // Hide the message if services are available
  } else {
    noServicesMessage.style.display = 'block'; // Show the message if no services are available
  }
}

// Search functionality for the search bar
document.getElementById('searchBar').addEventListener('input', function() {
  const searchTerm = this.value.toLowerCase(); // Get the search term and convert it to lowercase
  const allServices = document.querySelectorAll('.service-box');
  let isServiceAvailable = false;

  // Loop through all services and check if the service name or category matches the search term
  allServices.forEach(service => {
    const serviceName = service.getAttribute('data-service-name').toLowerCase();
    const categoryName = service.closest('.service-box').querySelector('.category-btn').textContent.toLowerCase();
    
    if (serviceName.includes(searchTerm) || categoryName.includes(searchTerm)) {
      service.style.display = 'block'; // Show matching services
      isServiceAvailable = true; // Set flag to true
    } else {
      service.style.display = 'none'; // Hide non-matching services
    }
  });

  // Display the "No services found" message if no matching services are found
  const noServicesMessage = document.getElementById('no-services-message');
  if (isServiceAvailable) {
    noServicesMessage.style.display = 'none'; // Hide the message if services are available
  } else {
    noServicesMessage.style.display = 'block'; // Show the message if no services are available
  }
});
</script>
