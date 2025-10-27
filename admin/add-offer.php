<?php
// Include the database connection file
include('partials/main.php');  // Adjust the path as necessary

// Fetch categories from tbl_category
$category_query = "SELECT * FROM tbl_category";
$category_result = mysqli_query($conn, $category_query);

// Fetch services based on the selected category
if (isset($_POST['category-select'])) {
    $category_id = $_POST['category-select'];

    // Fetch services based on category_id
    $service_query = "SELECT * FROM tbl_service WHERE category_id = '$category_id'";
    $service_result = mysqli_query($conn, $service_query);
}

// Handle form submission for adding offer
if (isset($_POST['submit-offer'])) {
    // Get form data
    $offer_title = $_POST['title'];
    $offer_category_id = $_POST['offer_category_id']; 
    $offer_type_id = $_POST['offer_type_id']; 
    $duration = $_POST['duration'];
    $current_price = $_POST['current_price'];
    $discount_rate = $_POST['discount_rate'];
    $discounted_price = $_POST['discounted_price'];
    $description = $_POST['description'];

    // Handle the image upload for offer
    $offer_image = NULL;
    if (isset($_FILES['offer_image']) && $_FILES['offer_image']['error'] == 0) {
        $target_dir = "../images/offer_images/"; // Set the directory where the images will be stored
        $target_file = $target_dir . basename($_FILES["offer_image"]["name"]);

        // Check if the file is an image
        if (getimagesize($_FILES["offer_image"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["offer_image"]["tmp_name"], $target_file)) {
                $offer_image = basename($_FILES["offer_image"]["name"]); // Save the image filename to the database
            }
        }
    }

    // Insert offer details into tbl_offer
    $insert_query = "INSERT INTO tbl_offer (title, offer_category_id, offer_type_id, duration, current_price, discount_rate, discounted_price, offer_image, description, status) 
                     VALUES ('$offer_title', '$offer_category_id', '$offer_type_id', '$duration', '$current_price', '$discount_rate', '$discounted_price', '$offer_image', '$description', 'active')";

    if (mysqli_query($conn, $insert_query)) {
        // Redirect with success message
        header("Location: " . SITEURL . 'admin/add-offer.php?status=success&action=add-offer');
    } else {
        // Redirect with error message
        header("Location: " . SITEURL . 'admin/add-offer.php?status=error&action=add-offer');
    }
}


?>


<div class="main-content">
    <div class="wrapper">

        <br />

        <!-- Form for Add Offer -->
        <form class="add-service-form" action="" method="POST" enctype="multipart/form-data">
            <h2 class="text_center">Add Offer</h2>

            <div class="form-group">
                <label for="title">Offer Title:</label>
                <input type="text" id="title" name="title" placeholder="Enter Offer Title" required>
            </div>

            <div class="form-group">
                <label for="category-select">Select Category:</label>
                <select id="category-select" name="offer_category_id" onchange="fetchServices()" required>
                    <option value="">Select Category</option>
                    <?php while ($category = mysqli_fetch_assoc($category_result)) { ?>
                        <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="service-type">Select Service Type:</label>
                <select id="service-type" name="offer_type_id" onchange="fetchServicePrice()" required>
                    <option value="">Select Service Type</option>
                    <?php if (isset($service_result)) { 
                        while ($service = mysqli_fetch_assoc($service_result)) { ?>
                            <option value="<?php echo $service['id']; ?>" data-price="<?php echo $service['booking_price']; ?>"><?php echo $service['service_type']; ?></option>
                        <?php } 
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="description">Offer Description:</label>
                <textarea id="description" name="description" placeholder="Enter Offer Description" rows="4" cols="89" required></textarea>
            </div>

            <div class="form-group">
                <label for="duration">Offer Duration:</label>
                <input type="text" id="duration" name="duration" placeholder="Enter Duration" required>
            </div>

            <div class="form-group">
                <label for="current-price">Current Price (in rupees):</label>
                <input type="number" id="current-price" name="current_price" placeholder="Enter Current Price" min="0" step="any" required>
            </div>

            <div class="form-group">
                <label for="discount-rate">Discount Rate (%):</label>
                <input type="number" id="discount-rate" name="discount_rate" placeholder="Enter Discount Rate" min="0" step="any" oninput="calculateDiscountedPrice()" required>
            </div>

            <div class="form-group">
                <label for="discounted-price">Discounted Price:</label>
                <input type="number" id="discounted-price" name="discounted_price" placeholder="Enter Discounted Price" min="0" step="any" required>
            </div>

            <div class="form-group">
                <label for="offer-image">Add Offer Image:</label>
                <input type="file" id="offer-image" name="offer_image" accept="image/*" required>
            </div>

            <div class="form-group">
                <label for="offer-status">Offer Status:</label>
                <select id="offer-status" name="offer_status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="form-group">
                <input type="submit" name="submit-offer" value="Add Offer" class="btn-primary">
            </div>
        </form>

    </div>
</div>

<?php include('partials/footer.php'); ?>



