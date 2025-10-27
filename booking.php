
<?php
include('./config/constant.php');

// Fetch vendor details (if needed)
$vendor_id = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : '';
if (empty($vendor_id)) {
    echo "<p>Invalid vendor ID provided.</p>";
    exit;
}

// Fetch dates that should be disabled (cannot be selected)
$booked_dates_query = "SELECT booking_date FROM tbl_booking 
                       WHERE service_provider_id = '$vendor_id' 
                       AND (booking_status = 'Pending' OR task_status = 'Pending') 
                       AND booking_status != 'Cancelled'"; // Allow 'Cancelled' dates

$booked_dates_result = mysqli_query($conn, $booked_dates_query);

// Store booked dates in an array
$booked_dates = [];
while ($row = mysqli_fetch_assoc($booked_dates_result)) {
    $booked_dates[] = trim($row['booking_date']); // Trim whitespace if needed
}

// Convert PHP array to JavaScript-compatible JSON
$booked_dates_json = json_encode($booked_dates);





// Fetch vendor details based on vendor_id
$vendor_query = "SELECT tbl_service_providers.*, tbl_service.service_type, tbl_service.booking_price, tbl_service.id as service_id, tbl_towns.town_name
                 FROM tbl_service_providers
                 JOIN tbl_service ON tbl_service.id = tbl_service_providers.servicetype_id
                 LEFT JOIN tbl_towns ON tbl_towns.id = tbl_service_providers.town_id
                 WHERE tbl_service_providers.id = '$vendor_id' AND tbl_service_providers.status = 'Confirmed'";

$vendor_result = mysqli_query($conn, $vendor_query);

if (mysqli_num_rows($vendor_result) === 0) {
    echo "<p>No vendor found or the vendor is not confirmed.</p>";
    exit;
}

$vendor = mysqli_fetch_assoc($vendor_result);

$offer_query = "SELECT * FROM tbl_offer WHERE offer_type_id = '{$vendor['servicetype_id']}' AND status = 'active'";
$offer_result = mysqli_query($conn, $offer_query);
$offer = mysqli_fetch_assoc($offer_result);

$booking_price = $vendor['booking_price'];
$offer_price = null;
if ($offer && isset($offer['discounted_price']) && $offer['discounted_price'] > 0) {
    $offer_price = $offer['discounted_price'];
    $booking_price = $offer_price;
}

if (isset($_POST['submit-booking'])) {
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $booking_date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $total_amount = mysqli_real_escape_string($conn, $_POST['total_amount']);

    // Assuming the customer is logged in, their ID is stored in the session
    $customer_id = $_SESSION['customer_id'];  // Customer ID from session

    // Insert booking data into tbl_bookings table, including customer_id and total amount
    $insert_query = "INSERT INTO tbl_booking (service_provider_id, service_id, customer_name, contact_number, email, address, booking_date, message, total_amount, payment_status, task_status, customer_id)
                     VALUES ('$vendor_id', '{$vendor['service_id']}', '$customer_name', '$contact_number', '$email', '$address', '$booking_date', '$message', '$total_amount', 'Pending', 'Pending', '$customer_id')";

    $result = mysqli_query($conn, $insert_query);

    if ($result) {
        // Booking was successful, redirect to the payment page
        $booking_id = mysqli_insert_id($conn); // Get the last inserted booking ID
        header("Location: payment.php?booking_id=$booking_id");
        exit;
    } else {
        echo "<p>Error: Could not create booking. Please try again.</p>";
    }
}

// Fetch average rating from tbl_feedback
$sql_rank = "SELECT AVG(rating) AS average_rating FROM tbl_feedback WHERE vendor_id = '$vendor_id'";
$result_rank = mysqli_query($conn, $sql_rank);
$rank = mysqli_fetch_assoc($result_rank);
$average_rating = $rank['average_rating'] ? round($rank['average_rating'], 1) : 0; // Default to 0 if no reviews

// Fetch count of completed tasks from tbl_booking
$sql_completed_tasks = "SELECT COUNT(*) AS completed_tasks FROM tbl_booking WHERE service_provider_id = '$vendor_id' AND task_status = 'Completed'";
$result_completed_tasks = mysqli_query($conn, $sql_completed_tasks);
$completed_data = mysqli_fetch_assoc($result_completed_tasks);
$completed_tasks = $completed_data['completed_tasks'];

// Add 1.0 for each completed task
$overall_ranking = round($average_rating + $completed_tasks, 1);

$logged_customer_id = $_SESSION['customer_id']; // Make sure this is set during login

// Fetch customer details
$customer_query = "SELECT * FROM tbl_customer WHERE id = '$logged_customer_id'";
$customer_result = mysqli_query($conn, $customer_query);

if (mysqli_num_rows($customer_result) === 1) {
    $customer = mysqli_fetch_assoc($customer_result);
    $cus_name = $customer['cus_name'];
    $cus_email = $customer['cus_email'];
    $cus_phone = $customer['cus_phone'];
    $cus_address = $customer['cus_address'];
} else {
    // If customer not found (session corrupted or invalid), redirect to login
    header("Location: customer-login.php");
    exit;
}



?>

<!-- HTML for the Booking Form -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book A Vendor | FIXNOW</title>
    <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/book_vendor.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<style>
    /* Style to mark booked dates */
    .ui-datepicker-unselectable .ui-state-default {
        background: #ff6961 !important;
        /* Red color for booked dates */
        color: white !important;
        text-decoration: line-through;
    }
</style>

<body>
    <div class="form-container">
        <!-- Back Button -->
        <button class="back-button" onclick="window.location.href='vendors-details.php?vendor_id=<?php echo $vendor_id; ?>'">Back</button>

        <div class="form-header">
            <img src="./images/vendor_images/<?php echo $vendor['vendor_image']; ?>" alt="Vendor Image">
            <h1><?php echo $vendor['full_name']; ?></h1>
            <p>Certified <?php echo $vendor['service_type']; ?></p>
        </div>
        <div class="booking-details">
            <?php
            $display_price = $booking_price; // Default booking price
            if ($overall_ranking >= 5) {
                $display_price += 200; // Increase price by Rs. 200 if ranking is 5 or more
            }
            ?>
            <strong>Booking Price:</strong> Rs. <?php echo $display_price; ?> (materials & vandor costs not included) <br><br>
            <strong>Available Times:</strong> <?php echo $vendor['available_dates_and_times']; ?>
        </div>

        <form action="" method="post">
        <div class="form-group">
    <label for="name"><i class="fas fa-user"></i> Full Name</label>
    <input type="text" id="name" name="customer_name" value="<?php echo htmlspecialchars($cus_name); ?>" required>
</div>

<div class="form-group">
    <label for="contact"><i class="fas fa-phone-alt"></i> Contact Number</label>
    <input type="tel" id="contact" name="contact_number" value="<?php echo htmlspecialchars($cus_phone); ?>" required>
</div>

<div class="form-group">
    <label for="email"><i class="fas fa-envelope"></i> Email</label>
    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($cus_email); ?>" required>
</div>

<div class="form-group">
    <label for="address"><i class="fas fa-home"></i> Address</label>
    <textarea id="address" name="address" required><?php echo htmlspecialchars($cus_address); ?></textarea>
</div>

            <div class="form-group">
                <label for="date"><i class="fas fa-calendar-alt"></i> Select Date</label>
                <input type="text" id="date" name="booking_date" required readonly>
            </div>


            <div class="form-group">
                <label for="message"><i class="fas fa-comment-dots"></i> Message</label>
                <textarea id="message" name="message" placeholder="Any special requests or queries"></textarea>
            </div>

            <!-- Hidden Field to Pass the Display Price -->
            <input type="hidden" name="total_amount" value="<?php echo $display_price; ?>">

            <div class="form-button">
                <input type="submit" name="submit-booking" value="Pay and Confirm Booking" class="btn-primary">
            </div>
        </form>
    </div>




</body>

<script>
$(document).ready(function () {
    var bookedDates = <?php echo $booked_dates_json; ?>; // Disabled dates

    $("#date").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: 0,  // Disable past dates
        beforeShowDay: function (date) {
            var dateString = $.datepicker.formatDate('yy-mm-dd', date);
            
            if (bookedDates.includes(dateString)) {
                return [false, "ui-datepicker-unselectable", "Booked"]; // Disable dates
            }
            
            return [true, "", "Available"]; // Enable other dates
        }
    });
});



</script>

</html>