<?php 
include('partials/main.php'); 

// Fetch all offers from tbl_offer
$offer_query = "SELECT * FROM tbl_offer";
$offer_result = mysqli_query($conn, $offer_query);
?>

<div class="main-content">
    <h2 class="main-welcome">Manage Offers</h2>
  
  
    <a href="add-offer.php" class="btn-primary">Add Offer</a>

    <br />
    <br />

    <table class="tbl-full">
        <tr>
            <th>S.N</th>
            <th>Offer Title</th>
            <th>Offer Image</th>
            <th>Offer Categories</th>
            <th>Offer Type</th>
            <th>Offer Duration</th>
            <th>Current Price</th>
            <th>Discount Rate</th>
            <th>Discounted Price</th>
            <th>Status</th> 
            <th>Actions</th>
        </tr>

        <?php 
        // Check if any offers are available
        if (mysqli_num_rows($offer_result) > 0) {
            $counter = 1;
            while ($offer = mysqli_fetch_assoc($offer_result)) {
                // Fetch offer details from the row
                $offer_id = $offer['id'];
                $offer_title = $offer['title'];
                $offer_image = $offer['offer_image'];
                $offer_category_id = $offer['offer_category_id'];
                $offer_type_id = $offer['offer_type_id'];
                $duration = $offer['duration'];
                $current_price = $offer['current_price'];
                $discount_rate = $offer['discount_rate'];
                $discounted_price = $offer['discounted_price'];
                $status = $offer['status'];

                // Fetch category name from tbl_category
                $category_query = "SELECT category_name FROM tbl_category WHERE id = '$offer_category_id'";
                $category_result = mysqli_query($conn, $category_query);
                $category_name = mysqli_fetch_assoc($category_result)['category_name'];

                // Fetch service type name from tbl_service
                $service_query = "SELECT service_type FROM tbl_service WHERE id = '$offer_type_id'";
                $service_result = mysqli_query($conn, $service_query);
                $service_type = mysqli_fetch_assoc($service_result)['service_type'];
        ?>

        <tr>
            <td><?php echo $counter++; ?></td>
            <td><?php echo $offer_title; ?></td>
            <td><img src="../images/offer_images/<?php echo $offer_image; ?>" alt="offer" class="offer_image" /></td>
            <td><?php echo $category_name; ?></td>
            <td><?php echo $service_type; ?></td>
            <td><?php echo $duration; ?></td>
            <td>Rs.<?php echo $current_price; ?></td>
            <td><?php echo $discount_rate; ?>%</td>
            <td>Rs.<?php echo $discounted_price; ?></td>
            <td><span class="status-<?php echo $status; ?>"><?php echo ucfirst($status); ?></span></td> <!-- Status column -->
            <td>
                <a href="update-offer.php?id=<?php echo $offer_id; ?>" class="btn-secondaryOffer">Update</a>
                <a href="delete-offers.php?id=<?php echo $offer_id; ?>" class="btn-dangerOffer">Delete</a>
            </td>
        </tr>

        <?php 
            }
        } else {
            echo "<tr><td colspan='11'>No offers available</td></tr>";
        }
        ?>

    </table>

</div>

<?php include('partials/footer.php'); ?>
