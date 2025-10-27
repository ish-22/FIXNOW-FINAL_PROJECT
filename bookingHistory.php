<?php include('./config/constant.php'); ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="stylesheet" href="./assets/css/bookingHistory.css">
</head>
<body>
    <section class="section-hero-bookings">
        <div class="back-button-container">
            <button class="back-button" onclick="window.history.back()">← Back</button>
        </div>
        <h2 class="all-bookings-title">All Booking History</h2>
        <div class="bookings-container">

            <?php
            if (isset($_SESSION['customer_id'])) {
                $customer_id = $_SESSION['customer_id'];

                // Query to fetch bookings for the logged-in customer
                $query = "SELECT 
                            b.id, b.service_id, b.customer_name, b.booking_date, b.total_amount, 
                            LOWER(b.booking_status) AS booking_status, 
                            LOWER(b.task_status) AS task_status, 
                            s.service_type, b.message, b.contact_number, 
                            sp.full_name AS provider_name
                          FROM tbl_booking b
                          JOIN tbl_service s ON b.service_id = s.id
                          JOIN tbl_service_providers sp ON b.service_provider_id = sp.id
                          WHERE b.customer_id = '$customer_id' 
                          ORDER BY b.created_at DESC";

                $result = mysqli_query($conn, $query);

                if (!$result) {
                    die("Error fetching bookings: " . mysqli_error($conn));
                }

                if (mysqli_num_rows($result) > 0) {
                    while ($booking = mysqli_fetch_assoc($result)) {
                        
                        // Get status values
                        $booking_status = strtolower($booking['booking_status']);
                        $task_status = strtolower($booking['task_status']);
                        ?>
                        <div class="booking-details"> 
                            <h3>Booking History</h3>
                            <p><strong>Service Type:</strong> <?php echo htmlspecialchars($booking['service_type']); ?></p>
                            <p><strong>Provider Name:</strong> <?php echo htmlspecialchars($booking['provider_name']); ?></p>
                            <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($booking['customer_name']); ?></p>
                            <p><strong>Service Date:</strong> <?php echo htmlspecialchars($booking['booking_date']); ?></p>
                            <p><strong>Booking Price:</strong> Rs. <?php echo number_format($booking['total_amount'], 2); ?> /=</p>
                            <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($booking['message'])); ?></p>
                            <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($booking['contact_number']); ?></p>

                            <p><strong>Booking Status:</strong> 
                                <span class="status-label booking-status <?php echo $booking_status; ?>">
                                    <?php echo ucfirst($booking_status); ?>
                                </span>
                            </p>

                            <?php if ($booking_status !== 'cancelled') { ?>
                            <p><strong>Task Status:</strong> 
                                <span class="status-label task-status <?php echo $task_status; ?>">
                                    <?php echo ucfirst($task_status); ?>
                                </span>
                            </p>
                            <?php } ?>

                        </div>
                        <?php
                    }
                } else {
                    echo "<p class='no-bookings'>No bookings found.</p>";
                }
            } else {
                echo "<p class='no-bookings'>Please log in to view your bookings.</p>";
            }
            ?>
        </div>
    </section>
</body>
</html>