<?php 
include('partials/main.php'); 

// Fetch all offers from tbl_offer
$offer_query = "SELECT o.*, c.category_name, s.service_type 
                FROM tbl_offer o
                LEFT JOIN tbl_category c ON o.offer_category_id = c.id
                LEFT JOIN tbl_service s ON o.offer_type_id = s.id
                ORDER BY o.id DESC";
$offer_result = mysqli_query($conn, $offer_query);
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Promotions &amp; Special Deals</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Manage homepage discounts, promotional banners, and seasonal pricing.</p>
        </div>
        <a href="add-offer.php" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Add New Promotion
        </a>
    </div>

    <table class="tbl-full">
        <thead>
            <tr>
                <th>Banner</th>
                <th>Title</th>
                <th>Applicable Service</th>
                <th>Duration</th>
                <th>Original Price</th>
                <th>Discount</th>
                <th>Promo Price</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($offer_result && mysqli_num_rows($offer_result) > 0) {
                while ($offer = mysqli_fetch_assoc($offer_result)) {
                    $offer_id = $offer['id'];
                    $offer_title = $offer['title'];
                    $offer_image = $offer['offer_image'];
                    $duration = $offer['duration'];
                    $current_price = floatval($offer['current_price']);
                    $discount_rate = floatval($offer['discount_rate']);
                    $discounted_price = floatval($offer['discounted_price']);
                    $status = $offer['status'];
                    $service_type = $offer['service_type'] ?? 'All Services';
                    $category_name = $offer['category_name'] ?? 'General';
                    
                    $img_url = "../images/offer_images/" . $offer_image;
                    $is_active = (strtolower($status) == 'active');
            ?>
            <tr>
                <td style="width: 80px;">
                    <img src="<?php echo $img_url; ?>" alt="" style="width: 60px; height: 45px; object-fit: cover; border-radius: 6px;" onerror="this.src='../assets/images/Offers.jpg';">
                </td>
                <td>
                    <strong style="color: #0f172a; display: block;"><?php echo htmlspecialchars($offer_title); ?></strong>
                    <small style="color: #64748b;"><?php echo htmlspecialchars($category_name); ?></small>
                </td>
                <td><?php echo htmlspecialchars($service_type); ?></td>
                <td><span style="font-size: 12.5px; color: #475569;"><?php echo htmlspecialchars($duration); ?></span></td>
                <td style="text-decoration: line-through; color: #94a3b8;">Rs. <?php echo number_format($current_price, 2); ?></td>
                <td><span style="background: #fee2e2; color: #ef4444; padding: 2px 8px; border-radius: 4px; font-weight: 700; font-size: 12px;">-<?php echo $discount_rate; ?>%</span></td>
                <td style="font-weight: 800; color: #0284c7;">Rs. <?php echo number_format($discounted_price, 2); ?></td>
                <td>
                    <span class="status-label <?php echo $is_active ? 'status-confirmed' : 'status-pending'; ?>">
                        <?php echo htmlspecialchars($status); ?>
                    </span>
                </td>
                <td style="text-align: right; white-space: nowrap;">
                    <a href="update-offer.php?id=<?php echo $offer_id; ?>" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <a href="delete-offers.php?id=<?php echo $offer_id; ?>" class="btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Are you sure you want to delete this offer?');">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='9' style='text-align: center; padding: 30px; color: #94a3b8;'>No promotions currently active.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php 
include('partials/footer.php'); 
?>
