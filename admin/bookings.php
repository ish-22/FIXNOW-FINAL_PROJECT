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

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Customer Booking Orders &amp; Escrow</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Monitor dispatched orders, 10% platform protection fee, and vendor escrow releases.</p>
        </div>
    </div>

    <table class="tbl-full">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Service Requested</th>
                <th>Assigned Specialist</th>
                <th>Customer</th>
                <th>Appointment Slot</th>
                <th>Gross Escrow</th>
                <th>Platform Net (10%)</th>
                <th>Vendor Payout (90%)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $service_type = $row['service_type'];
                    $provider_name = $row['provider_name'];
                    $customer_email = $row['email'];
                    $customer_name = $row['customer_name'];
                    $booking_date = $row['booking_date'];
                    $time_slot = !empty($row['time_slot']) ? $row['time_slot'] : '09:00 AM - 12:00 PM';
                    $total_amount = floatval($row['total_amount']);
                    $platform_fee = $row['platform_fee'] > 0 ? floatval($row['platform_fee']) : round($total_amount * 0.10, 2);
                    $vendor_payout = $row['vendor_payout'] > 0 ? floatval($row['vendor_payout']) : round($total_amount - $platform_fee, 2);
                    $booking_status = $row['booking_status']; 
                    $task_status = $row['task_status']; 
                    
                    $badge_class = 'status-pending';
                    if ($booking_status == 'Confirmed') $badge_class = 'status-confirmed';
                    elseif ($booking_status == 'Completed') $badge_class = 'status-completed';
                    elseif ($booking_status == 'Cancelled') $badge_class = 'status-cancelled';
            ?>
            <tr>
                <td style="font-weight: 700; color: #0284c7;">#BK-<?php echo $row['id']; ?></td>
                <td>
                    <strong style="color: #0f172a; display: block;"><?php echo htmlspecialchars($service_type); ?></strong>
                </td>
                <td>
                    <span style="font-weight: 600; color: #334155;"><?php echo htmlspecialchars($provider_name); ?></span>
                </td>
                <td>
                    <strong style="color: #0f172a; display: block;"><?php echo htmlspecialchars($customer_name); ?></strong>
                    <small style="color: #64748b;"><?php echo htmlspecialchars($customer_email); ?></small>
                </td>
                <td>
                    <div style="font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($booking_date); ?></div>
                    <small style="color: #0284c7; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; font-weight: 600;"><?php echo htmlspecialchars($time_slot); ?></small>
                </td>
                <td style="font-weight: 700; color: #0f172a;">Rs. <?php echo number_format($total_amount, 2); ?></td>
                <td style="font-weight: 700; color: #10b981;">Rs. <?php echo number_format($platform_fee, 2); ?></td>
                <td style="font-weight: 700; color: #6366f1;">Rs. <?php echo number_format($vendor_payout, 2); ?></td>
                <td>
                    <span class="status-label <?php echo $badge_class; ?>">
                        <?php echo htmlspecialchars($booking_status); ?>
                    </span>
                    <div style="font-size: 11px; color: #64748b; margin-top: 4px;">Task: <?php echo htmlspecialchars($task_status); ?></div>
                </td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='9' style='text-align: center; padding: 30px; color: #94a3b8;'>No paid bookings currently in escrow.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php 
include('partials/footer.php'); 
?>
