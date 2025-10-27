<?php include 'partials/headerwithoutcss.php'; ?>

<link rel="stylesheet" href="./assets/css/custom.css">
<link rel="stylesheet" href="./assets/css/bookings.css">

<section class="section-hero-bookings">
<h2 class="all-bookings-title">All Bookings</h2>
    <div class="bookings-container">


        <?php
        // Include database connection
        $result = null;

        if (isset($_SESSION['vendor_id'])) {
            $service_provider_id = $_SESSION['vendor_id'];

            // Query to fetch bookings
            $query = "SELECT b.id, b.service_id, b.customer_name, b.email, b.booking_date, b.total_amount, 
            LOWER(b.booking_status) AS booking_status, 
            LOWER(b.task_status) AS task_status, 
            s.service_type, b.message, b.contact_number, sp.full_name AS provider_name
     FROM tbl_booking b
     JOIN tbl_service s ON b.service_id = s.id
     JOIN tbl_service_providers sp ON b.service_provider_id = sp.id
     WHERE b.service_provider_id = '$service_provider_id' AND b.payment_status = 'Paid'
     ORDER BY b.created_at DESC";

            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Error fetching bookings: " . mysqli_error($conn));
            }
        }
        ?>

        <?php
        if ($result && mysqli_num_rows($result) > 0) {
            while ($booking = mysqli_fetch_assoc($result)) {
                // Set default class (White for confirmed, completed, or cancelled)
                $cardClass = "booking-normal";
                // If booking is canceled, keep normal color (White)
                if ($booking['booking_status'] == 'cancelled') {
                    $cardClass = "booking-normal";
                }

                // If booking is pending, highest priority color (Dark Pink)
                elseif ($booking['booking_status'] == 'pending') {
                    $cardClass = "booking-pending";
                }
                // If task is pending, second priority color (Dark Yellow)
                elseif ($booking['task_status'] == 'pending') {
                    $cardClass = "task-pending";
                }

                $hideAllButtons = ($booking['booking_status'] == 'cancelled' || $booking['task_status'] == 'completed');
                $showOnlyComplete = ($booking['booking_status'] == 'confirmed');

        ?>
                <div class="booking-details <?php echo $cardClass; ?>">
                    <h3>Booking Information</h3>
                    <p><strong>Service Type:</strong> <?php echo $booking['service_type']; ?></p>
                    <p><strong>Provider Id:</strong> <?php echo $service_provider_id; ?></p>
                    <p><strong>Provider Name:</strong> <?php echo $booking['provider_name']; ?></p>
                    <p><strong>Customer Name:</strong> <?php echo $booking['customer_name']; ?></p>
                    <p><strong>Customer Email:</strong> <?php echo $booking['email']; ?></p>
                    <p><strong>Service Date:</strong> <?php echo $booking['booking_date']; ?></p>
                    <p><strong>Booking Price:</strong> Rs. <?php echo $booking['total_amount']; ?> /=</p>
                    <p><strong>Message:</strong> <?php echo $booking['message']; ?></p>
                    <p><strong>Contact Number:</strong> <?php echo $booking['contact_number']; ?></p>

                    <p><strong>Booking Status:</strong> <?php echo ucfirst($booking['booking_status']); ?></p>
                    <p><strong>Task Status:</strong> <?php echo ucfirst($booking['task_status']); ?></p>

                    <?php if (!$hideAllButtons) { ?>
                        <form action="handle_booking_action.php" method="POST">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
                            <input type="hidden" name="service_provider_id" value="<?php echo $service_provider_id; ?>">

                            <div class="booking-actions">
                                <?php if (!$showOnlyComplete) { ?>
                                    <input type="submit" name="action" value="cancel" class="cancel-btn">
                                    <input type="submit" name="action" value="confirm" class="confirm-btn">
                                <?php } ?>
                                <input type="submit" name="action" value="complete" class="complete-btn">
                            </div>
                        </form>
                    <?php } ?>
                </div>
        <?php }
        } else {
            echo "<p>No bookings found for this service provider.</p>";
        }
        ?>
    </div>
</section>

<?php include '../partials/footer.php'; ?>