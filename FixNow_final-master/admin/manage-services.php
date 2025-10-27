<?php 
include('partials/main.php'); 
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="main-welcome">Manage Services</h1>


    
        <a href="add-services.php" class="btn-primary">Add Service</a>

        <br /><br />

     
        <div class="services-container">
            <?php
            // Query to get all services from the database
            $sql = "SELECT s.id as service_id, s.service_type, s.booking_price, s.service_image, c.category_name 
                    FROM tbl_service s 
                    INNER JOIN tbl_category c ON s.category_id = c.id";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are any services in the database
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $service_id = $row['service_id'];
                    $service_type = $row['service_type'];
                    $category_name = $row['category_name'];
                    $service_image = $row['service_image'];
                    $booking_price = $row['booking_price'];

                    // Set the image path
                    $image_path = "../images/service_images/" . $service_image;
            ?>
                    <div class="service-card">
                        <img src="<?php echo $image_path; ?>" alt="Service Image" class="service-image">
                        
                        <div class="service-details">
                            <h3 class="service-name"><?php echo $service_type; ?></h3>
                            <p class="service-category">Category: <?php echo $category_name; ?></p>
                            <p class="service-type">Type: <?php echo $service_type; ?></p>
                            <p class="service-price">Booking Price: <?php echo $booking_price; ?> /=</p>

                            <div class="service-actions">
                                <a href="update-services.php?id=<?php echo $service_id; ?>" class="btn-secondary">Update</a>
                                <a href="delete-services.php?id=<?php echo $service_id; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
                            </div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo "<p>No services available.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>
