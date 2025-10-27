<?php 
include('partials/main.php'); 

// Fetch total revenue from customer bookings (tbl_booking where payment_status is 'Paid')
$total_revenue_query = "SELECT SUM(total_amount) AS total_revenue FROM tbl_booking WHERE payment_status = 'Paid'";
$result = mysqli_query($conn, $total_revenue_query);
$row = mysqli_fetch_assoc($result);
$total_revenue = $row['total_revenue'] ? $row['total_revenue'] : 0; 

// Fetch total revenue from vendor payments (tbl_invoice where status is 'paid')
$total_vendor_revenue_query = "SELECT SUM(paid_amount) AS total_vendor_revenue FROM tbl_invoice WHERE status = 'paid'";
$result_vendor = mysqli_query($conn, $total_vendor_revenue_query);
$row_vendor = mysqli_fetch_assoc($result_vendor);
$total_vendor_revenue = $row_vendor['total_vendor_revenue'] ? $row_vendor['total_vendor_revenue'] : 0; 

// Fetch all service providers from tbl_service_providers
$vendor_query = "SELECT sp.id, sp.full_name, sp.email, sp.contact_no, sp.servicetype_id, sp.status, s.service_type 
                 FROM tbl_service_providers sp
                 JOIN tbl_service s ON sp.servicetype_id = s.id
                 ORDER BY sp.id ASC";
$vendor_result = mysqli_query($conn, $vendor_query);
?>

<!-- content goes here -->
<div class="main-content">

    <h2 class="main-welcome">Welcome Admin DashBoard</h2>

    <!-- Shortcut buttons -->
    <div class="content-grid">
        <a class="content-button" href="#">
            Total Revenue (Customer Bookings): <?php echo number_format($total_revenue, 2); ?> /=
        </a>
        <a class="content-button" href="#">
            Total Revenue (Vendor Payments): <?php echo number_format($total_vendor_revenue, 2); ?> /=
        </a>
        <a class="content-button" href="analyzing.php">Analyze</a>
        <a class="content-button" href="add-locations.php">Locations</a>
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
