<?php 
include('partials/main.php'); 
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Services Directory Catalog</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Configure available trade categories, baseline booking pricing, and cover visuals.</p>
        </div>
        <a href="add-services.php" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Add New Service
        </a>
    </div>

    <div class="services-container">
        <?php
        // Query to get all services from the database
        $sql = "SELECT s.id as service_id, s.service_type, s.booking_price, s.service_image, c.category_name 
                FROM tbl_service s 
                INNER JOIN tbl_category c ON s.category_id = c.id
                ORDER BY s.id DESC";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $service_id = $row['service_id'];
                $service_type = $row['service_type'];
                $category_name = $row['category_name'];
                $service_image = $row['service_image'];
                $booking_price = $row['booking_price'];

                $image_path = "../images/service_images/" . $service_image;
                if (!file_exists($image_path) || empty($service_image)) {
                    $image_path = "../assets/images/default-service.jpg";
                }
        ?>
                <div class="service-card">
                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($service_type); ?>" class="service-image" onerror="this.src='../assets/images/default-service.jpg';">
                    
                    <div class="service-details">
                        <span style="display: inline-block; background: #e0f2fe; color: #0284c7; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; margin-bottom: 8px;">
                            <?php echo htmlspecialchars($category_name); ?>
                        </span>
                        <h3 class="service-name"><?php echo htmlspecialchars($service_type); ?></h3>
                        <div class="service-price">LKR <?php echo number_format($booking_price, 2); ?></div>

                        <div class="service-actions">
                            <a href="update-services.php?id=<?php echo $service_id; ?>" class="btn-secondary" style="flex: 1; text-align: center;">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>
                            <a href="delete-services.php?id=<?php echo $service_id; ?>" class="btn-danger" style="flex: 1; text-align: center;" onclick="return confirm('Are you sure you want to delete this service?');">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p style='padding: 30px; text-align: center; color: #94a3b8;'>No services available in the catalog.</p>";
        }
        ?>
    </div>
</div>

<?php 
include('partials/footer.php'); 
?>
