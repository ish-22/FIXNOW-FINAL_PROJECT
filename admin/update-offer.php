<?php 
// Include the database connection file
include('partials/main.php');

// Check if offer ID is passed via GET
if (isset($_GET['id'])) {
    $offer_id = $_GET['id'];

    // Fetch the existing offer details from the database
    $offer_query = "SELECT * FROM tbl_offer WHERE id = '$offer_id'";
    $offer_result = mysqli_query($conn, $offer_query);

    if (mysqli_num_rows($offer_result) > 0) {
        $offer = mysqli_fetch_assoc($offer_result);

        // Fetch the category name for the selected category
        $category_query = "SELECT * FROM tbl_category WHERE id = '" . $offer['offer_category_id'] . "'";
        $category_result = mysqli_query($conn, $category_query);
        $category = mysqli_fetch_assoc($category_result);

        // Fetch the service type name for the selected offer type
        $service_query = "SELECT * FROM tbl_service WHERE id = '" . $offer['offer_type_id'] . "'";
        $service_result = mysqli_query($conn, $service_query);
        $service = mysqli_fetch_assoc($service_result);
    } else {
        // If offer not found, redirect with error message
        header("Location: " . SITEURL . 'admin/manage-offers.php?status=error&action=offer-not-found');
    }
} else {
    // If no offer ID is passed, redirect with error message
    header("Location: " . SITEURL . 'admin/manage-offers.php?status=error&action=invalid-request');
}

// Handle form submission for updating offer
if (isset($_POST['submit-offer'])) {
    // Capture form data
    $offer_title = $_POST['offer-title'];
    $offer_category_id = $_POST['offer_category'];
    $offer_type_id = $_POST['offer_type'];
    $duration = $_POST['offer-duration'];
    $current_price = $_POST['current-price'];
    $discount_rate = $_POST['discount-rate'];
    $discounted_price = $_POST['discounted-price'];
    $description = $_POST['description'];
    $offer_status = $_POST['offer-status'];
    $offer_image = $_FILES['offer-image']['name'];

    // Check if a new image is uploaded
    if ($offer_image) {
        $image_path = "../images/offer_images/$offer_image";
        move_uploaded_file($_FILES['offer-image']['tmp_name'], $image_path);
    } else {
        // If no new image is uploaded, keep the existing image
        $image_path = "../images/offer_images/" . $offer['offer_image'];
        $offer_image = $offer['offer_image'];
    }

    // Update query
    $update_query = "UPDATE tbl_offer 
                     SET title = '$offer_title', 
                         offer_category_id = '$offer_category_id', 
                         offer_type_id = '$offer_type_id', 
                         duration = '$duration', 
                         current_price = '$current_price', 
                         discount_rate = '$discount_rate', 
                         discounted_price = '$discounted_price', 
                         offer_image = '$offer_image', 
                         description = '$description', 
                         status = '$offer_status'
                     WHERE id = '$offer_id'";

    if (mysqli_query($conn, $update_query)) {
        // Redirect with success message
        header("Location: " . SITEURL . 'admin/manage-offers.php?status=success&action=update-offer');
    } else {
        // Redirect with error message
        header("Location: " . SITEURL . 'admin/manage-offers.php?status=error&action=update-offer');
    }
}

?>

<div class="main-content">
 

    <!-- Form for updating the offer -->
    <form action="" method="POST" enctype="multipart/form-data" class="add-service-form">
    <h2 class="text_center">Update Offers</h2>

        <!-- Offer Title -->
        <div class="input-field">
            <label for="offer-title">Offer Title:</label>
            <input type="text" id="offer-title" name="offer-title" value="<?php echo $offer['title']; ?>" placeholder="Enter offer title" required />
        </div>

        <!-- Offer Category -->
        <div class="input-field">
            <label for="offer-category">Offer category:</label>
            <select id="offer-category" name="offer_category" onchange="fetchServicesForUpdate()" required>
                <option value="">Select Offer Category</option>
                <?php
                // Fetch all categories for the dropdown
                $category_query = "SELECT * FROM tbl_category";
                $category_result = mysqli_query($conn, $category_query);
                while ($category_row = mysqli_fetch_assoc($category_result)) {
                    $selected = ($category_row['id'] == $offer['offer_category_id']) ? 'selected' : '';
                    echo "<option value='" . $category_row['id'] . "' $selected>" . $category_row['category_name'] . "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Offer Type -->
        <div class="input-field">
            <label for="offer-type">Offer type:</label>
            <select id="offer-type" name="offer_type" onchange="fetchServicePriceForUpdate()" required>
                <option value="">Select Offer Type</option>
                <?php
                // Fetch all services for the dropdown
                $service_query = "SELECT * FROM tbl_service WHERE category_id = '" . $offer['offer_category_id'] . "'";
                $service_result = mysqli_query($conn, $service_query);
                while ($service_row = mysqli_fetch_assoc($service_result)) {
                    $selected = ($service_row['id'] == $offer['offer_type_id']) ? 'selected' : '';
                    echo "<option value='" . $service_row['id'] . "' data-price='" . $service_row['booking_price'] . "' $selected>" . $service_row['service_type'] . "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Offer Description -->
        <div class="form-group">
            <label for="description">Offer Description:</label>
            <textarea id="description" name="description" placeholder="Enter Offer Description" rows="4" cols="89" required><?php echo $offer['description']; ?></textarea>
        </div>

        <!-- Offer Duration -->
        <div class="input-field">
            <label for="offer-duration">Offer Duration:</label>
            <input type="text" id="offer-duration" name="offer-duration" value="<?php echo $offer['duration']; ?>" placeholder="Enter offer duration" required />
        </div>

        <!-- Current Price -->
        <div class="input-field">
            <label for="current-price">Current Price:</label>
            <input type="number" id="current-price" name="current-price" value="<?php echo $offer['current_price']; ?>" placeholder="Enter current price" required />
        </div>

        <!-- Discount Rate -->
        <div class="input-field">
            <label for="discount-rate">Discount Rate (%):</label>
            <input type="number" id="discount-rate" name="discount-rate" value="<?php echo $offer['discount_rate']; ?>" placeholder="Enter discount rate" required oninput="calculateDiscountedPriceForUpdate()" />
        </div>

        <!-- Discounted Price -->
        <div class="input-field">
            <label for="discounted-price">Discounted Price:</label>
            <input type="number" id="discounted-price" name="discounted-price" value="<?php echo $offer['discounted_price']; ?>" placeholder="Enter discounted price" required />
        </div>

        <!-- Offer Image -->
        <div class="input-field">
            <label for="offer-image">Offer Image:</label>
            <input type="file" id="offer-image" name="offer-image" accept="image/*" />
            <!-- Display current image if available -->
            <img src="../images/offer_images/<?php echo $offer['offer_image']; ?>" alt="Current Offer Image" class="offer_image" />
        </div>

        <!-- Offer Status -->
        <div class="input-field">
            <label for="offer-status">Offer Status:</label>
            <select id="offer-status" name="offer-status" required>
                <option value="active" <?php if ($offer['status'] == 'active') echo 'selected'; ?>>Active</option>
                <option value="inactive" <?php if ($offer['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
            </select>
        </div>

        <!-- Hidden field for Offer ID -->
        <input type="hidden" name="offer-id" value="<?php echo $offer['id']; ?>" />

        <!-- Submit Button -->
        <div class="input-field">
            <button type="submit" name="submit-offer" class="btn-primary">Update Offer</button>
        </div>
    </form>

</div>

<?php include('partials/footer.php'); ?>
