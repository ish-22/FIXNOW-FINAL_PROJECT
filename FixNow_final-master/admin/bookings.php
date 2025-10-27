<?php 
include('partials/main.php'); 

// Fetch all bookings from the `tbl_booking` table
$query = "SELECT tbl_booking.*, tbl_service.service_type, tbl_service_providers.registration_code, tbl_service_providers.full_name as provider_name
          FROM tbl_booking
          JOIN tbl_service ON tbl_booking.service_id = tbl_service.id
          JOIN tbl_service_providers ON tbl_service_providers.id = tbl_booking.service_provider_id
          WHERE tbl_booking.payment_status = 'Paid' 
          ORDER BY tbl_booking.created_at DESC";

$result = mysqli_query($conn, $query);
?>

<div class="main-content">
    <h2 class="main-welcome">All Bookings</h2>

    <!-- Bookings Table -->
    <table class="tbl-full">
        <tr>
            <th>S.N</th>
            <th>Service Type</th>
            <th>Provider Name</th>
            <th>Customer Email</th>
            <th>Customer Name</th>
            <th>Booking Date</th>
            <th>Booking Amount</th>
            <th>Booking Status</th>
            <th>Task Status</th>
        </tr>

        <?php 
        if (mysqli_num_rows($result) > 0) {
            $serial_number = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $service_type = $row['service_type'];
                $provider_name = $row['provider_name'];
                $customer_email = $row['email'];
                $customer_name = $row['customer_name'];
                $booking_date = $row['booking_date'];
                $total_amount = $row['total_amount'];
                $booking_status = $row['booking_status']; 
                $task_status = $row['task_status']; 
        ?>
        <tr>
            <td><?php echo $serial_number++; ?></td>
            <td><?php echo $service_type; ?></td>
            <td><?php echo $provider_name; ?></td>
            <td><?php echo $customer_email; ?></td>
            <td><?php echo $customer_name; ?></td>
            <td><?php echo $booking_date; ?></td>
            <td><?php echo "Rs. " . number_format($total_amount, 2); ?></td>
            <td>
                <span class="status-label" style="
                    padding: 3px 6px;
                    font-weight: bold;
                    border-radius: 5px;
                    display: inline-block;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    text-align: center;
                    min-width: 130px;
                    <?php 
                        if ($booking_status == 'Pending') {
                            echo 'background-color: #ffebcc; color: #cc6600; border: 1px solid #cc6600;';
                        } elseif ($booking_status == 'Cancelled') {
                            echo 'background-color: #ffe6e6; color: #cc0000; border: 1px solid #cc0000;';
                        } elseif ($booking_status == 'Confirmed') {
                            echo 'background-color: #ccffcc; color: #008000; border: 1px solid #008000;';
                        } elseif ($booking_status == 'Completed') {
                            echo 'background-color: #cce5ff; color: #004085; border: 1px solid #004085;';
                        }
                    ?>
                ">
                    <?php echo $booking_status; ?>
                </span>
            </td>
            <td>
                <span class="status-label" style="
                    padding: 3px 6px;
                    font-weight: bold;
                    border-radius: 5px;
                    display: inline-block;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    text-align: center;
                    min-width: 130px;
                    <?php 
                        if ($task_status == 'Pending') {
                            echo 'background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;';
                        } elseif ($task_status == 'In Progress') {
                            echo 'background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb;';
                        } elseif ($task_status == 'Completed') {
                            echo 'background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;';
                        }
                    ?>
                ">
                    <?php echo $task_status; ?>
                </span>
            </td>
        </tr>
        <?php 
            }
        } else {
            echo "<tr><td colspan='9'>No bookings found</td></tr>";
        }
        ?>
    </table>
</div>

<?php include('partials/footer.php'); ?>