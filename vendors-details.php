<?php

include('./config/constant.php');

// Get the selected vendor ID from the URL
$vendor_id = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : ''; // Get the vendor_id from URL

if (empty($vendor_id)) {
    // If vendor_id is not provided in the URL
    echo "<p>Invalid vendor ID provided.</p>";
    exit;
}

// Fetch vendor details based on vendor_id
$vendor_query = "SELECT tbl_service_providers.*, tbl_service.service_type, tbl_service.booking_price, tbl_towns.town_name 
                 FROM tbl_service_providers
                 JOIN tbl_service ON tbl_service.id = tbl_service_providers.servicetype_id
                 LEFT JOIN tbl_towns ON tbl_towns.id = tbl_service_providers.town_id
                 WHERE tbl_service_providers.id = '$vendor_id' AND tbl_service_providers.status = 'Confirmed'";

$vendor_result = mysqli_query($conn, $vendor_query);

// Check if the vendor exists
if (mysqli_num_rows($vendor_result) === 0) {
    // If no vendor is found or status is not confirmed
    echo "<p>No vendor found or the vendor is not confirmed.</p>";
    exit;
}

$vendor = mysqli_fetch_assoc($vendor_result);

// Fetch offer details from the tbl_offer table based on the offer_type_id and status
$offer_query = "SELECT * FROM tbl_offer WHERE offer_type_id = '{$vendor['servicetype_id']}' AND status = 'active'";
$offer_result = mysqli_query($conn, $offer_query);
$offer = mysqli_fetch_assoc($offer_result);

// Prepare data
$vendor_name = $vendor['full_name'];
$vendor_image = $vendor['vendor_image'] ? $vendor['vendor_image'] : 'default.jpg';
$service_type = $vendor['service_type'];
$contact_number = $vendor['contact_no'];
$email = $vendor['email'];
$about_you = $vendor['about_you'];
$experience = $vendor['experience'];
$available_times = $vendor['available_dates_and_times'];
$town_name = $vendor['town_name'];
$booking_price = $vendor['booking_price']; // Fetching booking price from tbl_service

// Check if there's an active offer and apply the discounted price
$offer_price = null;
if ($offer && isset($offer['discounted_price']) && $offer['discounted_price'] > 0) {
    $offer_price = $offer['discounted_price']; // Set offer price if available
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

$final_offer_price = $offer_price;
if ($overall_ranking >= 5) {
    $final_offer_price += 200; // Add Rs. 200 if ranking is 5 or more
}

// Prepare the final display price with the offer price
$final_price = $offer_price ? $final_offer_price : $booking_price;
// No more capping at 5


?>

<!-- Start of the vendor profile page -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Profile</title>
    <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    <link rel="stylesheet" href="./assets/css/vendordetails.css">
    <link rel="stylesheet" href="fonts/remixicon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css">


    <span class="main_bg"></span>
    <div class="container">

        <header>
            <div class="brandLogo">
                <figure><img src="./assets/images/fixnow-logo-icon.png" alt="logo" width="40px" height="40px"></figure>
                <span>FIXNOW</span>
            </div>
        </header>

        <!-- Back Button -->
        <button class="back-button" onclick="window.location.href='services.php'">Back</button>

        <section class="userProfile card">
            <div class="profile">
                <figure><img src="./images/vendor_images/<?php echo $vendor_image; ?>" alt="profile" width="250px" height="250px"></figure>
            </div>
        </section>

        <section class="work_skills card">
            <div class="work">
                <h1 class="heading">Work</h1>
                <div class="vendor-details">
                    <h1><?php echo $vendor_name; ?></h1>
                    <span>Certified (<?php echo $service_type; ?>)</span>
                    <br>
                    <p><strong><?php echo $service_type; ?></strong> Services in <strong><?php echo $town_name; ?></strong></p>
                    <br>
                    <p><strong>Experience:</strong> <?php echo $experience; ?></p>
                    <br>
                    <p><strong>About:</strong> <?php echo $about_you; ?></p>
                </div>

            </div>
        </section>





        <section class="userDetails card">
            <div class="userName">
                <h1 class="name"><?php echo $vendor_name; ?></h1>
               
                <p>Certified (<?php echo $service_type; ?>)</p>
            </div>

            <!-- Display Ranking -->
            <div class="rank">
                <h1>Overall Ranking</h1>
                <span><?php echo $overall_ranking; ?></span>
                <div class="rating">
                    <?php
                    // Ensure a max of 5 stars
                    $stars_to_display = min(5, ceil($overall_ranking));

                    for ($i = 1; $i <= $stars_to_display; $i++) {
                        echo '<i class="ri-star-fill"></i>';
                    }
                    ?>
                </div>
            </div>


            <div class="btns">
                <ul>
                    <!-- Check if the customer is logged in -->
                    <?php
                    if (isset($_SESSION['customer_id'])) {
                        // If logged in, show the booking button
                        echo '<li class="sendMsg active">
                                <i class="ri-check-fill ri"></i>
                                <a href="booking.php?vendor_id=' . $vendor['id'] . '">Book Now</a>
                            </li>';
                    } else {
                        // If not logged in, show the "Login to Book" button
                        echo '<li class="sendMsg active">
                                <i class="ri-check-fill ri"></i>
                                <a href="signin.php">Login to Book</a>
                            </li>';
                    }
                    ?>



                    <li class="sendMsg">
                        <i class="ri-chat-4-fill ri"></i>
                        <a href="mailto:<?php echo $email; ?>">Send Email</a>
                    </li>
                    <li class="sendMsg">
                        <i class="ri-chat-4-fill ri"></i>
                        <a href="chat.php?vendor_id=<?php echo $vendor['id']; ?>">Chat Now</a>
                    </li>


                    <li class="viewFeedbak">
                        <a href="feedback.php?vendor_id=<?php echo $vendor_id; ?>">View Feedback</a>
                    </li>
                </ul>
            </div>
        </section>

        <section class="timeline_about card">
            <div class="tabs">
                <ul>
                    <li class="about active">
                        <i class="ri-user-3-fill ri"></i>
                        <span>About</span>
                    </li>
                </ul>
            </div>

            <div class="basic_info">
                <h1 class="heading">Contact Information</h1>
                <ul>
                    <!-- <li class="phone">
                        <h1 class="label">Phone:</h1>
                        <span class="info"></span>
                    </li> -->
                    

                    <li class="address">
                        <h1 class="label">Town:</h1>
                        <span class="info"><?php echo $town_name; ?></span>
                    </li>

                    <li class="email">
                        <h1 class="label">E-mail:</h1>
                        <span class="info"><?php echo $email; ?></span>
                    </li>
                </ul>
            </div>

            <div class="basic_info">
                <h1 class="heading">Basic Information</h1>
                <ul>
                    <li class="experience">
                        <h1 class="label">Experience:</h1>
                        <span class="info"><?php echo $experience; ?></span>
                    </li>

                    <li class="price">
                        <h1 class="label">Booking Price:</h1>
                        <?php
                        $display_price = $booking_price;
                        if ($overall_ranking >= 5) {
                            $display_price += 200;
                        }
                        ?>
                        <span class="info"><?php echo $display_price; ?> (Materials and vendor costs not include)</span>
                    </li>


                    <?php if ($offer_price): ?>
                        <li class="offer_price">
                            <h1 class="label" style="color: red;">Offer Price:</h1>
                            <span class="info" style="color: red;"><?php echo $final_offer_price; ?> (Discounted Price)</span>
                            </li>
                    <?php endif; ?>

                    <li class="availability">
                        <h1 class="label">Available Times:</h1>
                        <span class="info"><?php echo $available_times; ?></span>
                    </li>
                </ul>
            </div>

            <div class="offer_info">
                <ul>
                    <li class="offer">
                        <h1 class="label">Booking Offer:</h1>
                        <span class="info"><?php echo $offer ? $offer['description'] : 'No Offer Available.'; ?></span>
                    </li>
                </ul>
            </div>

        </section>
    </div>

    </body>

</html>