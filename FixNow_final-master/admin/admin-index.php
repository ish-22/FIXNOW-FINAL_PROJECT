<?php 
include('partials/main.php'); 

// Fetch total gross volume and platform commission from bookings
$commission_res = db_fetch_one($conn, "SELECT SUM(total_amount) AS total_gross, SUM(platform_fee) AS total_commission FROM tbl_booking WHERE payment_status = 'Paid'");
$total_revenue = ($commission_res && $commission_res['total_gross']) ? floatval($commission_res['total_gross']) : 0;
$total_commission = ($commission_res && $commission_res['total_commission']) ? floatval($commission_res['total_commission']) : round($total_revenue * 0.10, 2);

// Fetch total revenue from vendor payments (tbl_invoice where status = 'paid')
$row_vendor = db_fetch_one($conn, "SELECT SUM(paid_amount) AS total_vendor_revenue FROM tbl_invoice WHERE status = 'paid'");
$total_vendor_revenue = ($row_vendor && $row_vendor['total_vendor_revenue']) ? floatval($row_vendor['total_vendor_revenue']) : 0; 

// Fetch all service providers from tbl_service_providers
$vendor_result = mysqli_query($conn, "SELECT sp.id, sp.full_name, sp.email, sp.contact_no, sp.servicetype_id, sp.status, s.service_type 
                 FROM tbl_service_providers sp
                 JOIN tbl_service s ON sp.servicetype_id = s.id
                 ORDER BY sp.id ASC");
?>

<!-- content goes here -->
<div class="main-content">

    <h2 class="main-welcome">Welcome Admin DashBoard</h2>

    <!-- Shortcut buttons -->
    <div class="content-grid">
        <a class="content-button" href="bookings.php">
            Customer Booking Escrow: <?php echo number_format($total_revenue, 2); ?> /=
        </a>
        <a class="content-button" href="bookings.php" style="background: linear-gradient(135deg, #16a34a, #15803d); color: white;">
            Platform Commission (10%): <?php echo number_format($total_commission, 2); ?> /=
        </a>
        <a class="content-button" href="manage-invoice.php">
            Vendor Subscription Fees: <?php echo number_format($total_vendor_revenue, 2); ?> /=
        </a>
        <a class="content-button" href="analyzing.php">Analyze Platform</a>
    </div>

</div>

<!-- Vendors table -->
<div class="vendors-table">
    <h2 class="main-vendors">Available Vendors</h2>
    <table>
        <thead>
            <tr>
                <th>Vendor ID</th>
                <th>Vendor Name</th>
                <th>Contact Number</th>
                <th>Email</th>
                <th>Service Type</th>
                <th>Status</th>
                <th>Actions</th> <!-- Add Actions Column -->
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($vendor_result) > 0) {
                while ($vendor = mysqli_fetch_assoc($vendor_result)) {
                    echo "<tr>
                        <td>{$vendor['id']}</td>
                        <td>{$vendor['full_name']}</td>
                        <td>{$vendor['contact_no']}</td>
                        <td>{$vendor['email']}</td>
                        <td>{$vendor['service_type']}</td>
                        <td>{$vendor['status']}</td>
                        <td>
                            <a href='delete-vendor.php?id={$vendor['id']}' class='delete_vendor'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No vendors found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php include('partials/footer.php'); ?>
