<?php

include('../config/constant.php');

// Check if the user is logged in
if (!isset($_SESSION['vendor_id'])) {
    echo "You need to log in to view this page.";
    exit();
}

// Get the logged-in vendor ID from the session
$vendor_id = $_SESSION['vendor_id'];

// Fetch vendor details based on vendor_id
$vendor_query = "SELECT 
                    sp.full_name AS vendor_name,
                    sp.email,
                    sp.contact_no AS contact_number,
                    sp.experience,
                    sp.about_you,
                    sp.available_dates_and_times AS available_times,
                    sp.vendor_image,
                    s.service_type,
                    t.town_name,
                    s.booking_price
                FROM tbl_service_providers sp
                LEFT JOIN tbl_service s ON sp.servicetype_id = s.id
                LEFT JOIN tbl_towns t ON sp.town_id = t.id
                WHERE sp.id = '$vendor_id' AND sp.status = 'Confirmed'";

$vendor_result = mysqli_query($conn, $vendor_query);

// Check if the vendor exists
if ($vendor_result && mysqli_num_rows($vendor_result) == 1) {
    $vendor = mysqli_fetch_assoc($vendor_result);
} else {
    echo "Vendor not found or not confirmed.";
    exit();
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

// No more capping at 5
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Profile</title>
    <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    <link rel="stylesheet" href="./assets/css/vendordetails.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EOeXteC1mW/V8wnxF60vsqW3UQuv0YhUOZr6bFz9XIlT0L0lLhxFLM6W8fqylS3A" crossorigin="anonymous">
    <link rel="stylesheet" href="fonts/remixicon.css">
    <link rel="stylesheet" href="fonts/remixicon.css">
    <script src="../js/script.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css">
    <style>
    /* Ranking Section */
    /* Ranking Section */
    .rank {
        background: linear-gradient(to right, #007bff, #0056b3);
        /* Blue gradient */
        color: #fff;
        text-align: center;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.2);
        margin: 0;
        font-size: 18px;
        font-weight: bold;
        transition: transform 0.3s ease-in-out;
        width: 280px;
        /* Reduced size */
        display: inline-block;
    }

    .rank:hover {
        transform: scale(1.03);
    }

    .rank h1 {
        font-size: 20px;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .rank span {
        font-size: 24px;
        margin-top: 20px;
        font-weight: bold;
        background: #fff;
        color: #0056b3;
        padding: 8px 15px;
        border-radius: 8px;
        display: inline-block;
        box-shadow: 2px 3px 8px rgba(0, 0, 0, 0.2);
    }



    /* Star Rating */
    .rating {
        margin-top: 10px;
        margin-left: 35px;

    }

    .rating i {
        font-size: 22px;

        color: #fff;
        /* White stars */
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        margin: 2px;
    }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <div class="brandLogo">
                <figure><img src="./assets/images/fixnow-logo-icon.png" alt="logo" width="40px" height="40px"></figure>
                <span>FIXNOW</span>
            </div>
        </header>

        <!-- Back Button -->
        <button class="back-button" onclick="window.location.href='index.php'">Back</button>

        <!-- Vendor Profile Section -->
        <section class="userProfile card">
            <div class="profile">
                <figure><img src="../images/vendor_images/<?php echo htmlspecialchars($vendor['vendor_image']); ?>"
                        alt="profile" width="250px" height="250px"></figure>
            </div>
        </section>

        <section class="work_skills card">
            <div class="work">
                <h1 class="heading">Work</h1>
                <div class="vendor-details">
                    <h1><?php echo htmlspecialchars($vendor['vendor_name']); ?></h1>
                    <br>
                    <span>Certified (<?php echo htmlspecialchars($vendor['service_type']); ?>)</span>
                    <p><strong><?php echo htmlspecialchars($vendor['service_type']); ?></strong> Services in
                        <strong></strong>
                        <><?php echo htmlspecialchars($vendor['town_name']); ?></strong>
                    </p>
                    <br>
                    <p><strong>experience:</strong> <?php echo htmlspecialchars($vendor['experience']); ?></p>
                    <br>
                    <p><strong>About:</strong> <?php echo htmlspecialchars($vendor['about_you']); ?></p>
                </div>
            </div>
        </section>




        <section class="userDetails card">
            <div class="userName">
                <h1 class="name"><?php echo htmlspecialchars($vendor['vendor_name']); ?></h1>
                <p>Certified (<?php echo htmlspecialchars($vendor['service_type']); ?>)</p>
            </div>

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
                    <!-- <li class="sendMsg">
                        <a href="Add_feedback_url.php" class="btn btn-primary">Add Feedback URL</a>
                    </li> -->
                    <li class="sendMsg">
                        <a href="update_profile.php" class="btn btn-secondary">Update Profile</a>
                    </li>





                </ul>
            </div>
        </section>

        <section class="timeline_about card">
            <div class="basic_info">
                <h1 class="heading">Contact Information</h1>
                <ul>
                    <li class="phone">
                        <h1 class="label">Phone:</h1>
                        <span class="info"><?php echo htmlspecialchars($vendor['contact_number']); ?></span>
                    </li>
                    <li class="address">
                        <h1 class="label">Town:</h1>
                        <span class="info"><?php echo htmlspecialchars($vendor['town_name']); ?></span>
                    </li>
                    <li class="email">
                        <h1 class="label">E-mail:</h1>
                        <span class="info"><?php echo htmlspecialchars($vendor['email']); ?></span>
                    </li>
                </ul>
            </div>

            <div class="basic_info">
                <h1 class="heading">Basic Information</h1>
                <ul>
                    <li class="experience">
                        <h1 class="label">Experience:</h1>
                        <span class="info"><?php echo htmlspecialchars($vendor['experience']); ?></span>
                    </li>

                    <h1 class="label">Booking Price:</h1>
                    <?php
                    $display_price = $vendor['booking_price'];
                    if ($overall_ranking >= 5) {
                        $vendor['booking_price'] += 200;
                    }
                    ?>
                    <span class="info"><?php echo htmlspecialchars($vendor['booking_price']); ?> (Materials and
                        additional costs extra)</span>
                    </li>


                    <li class="availability">
                        <h1 class="label">Available Times:</h1>
                        <span class="info"><?php echo htmlspecialchars($vendor['available_times']); ?></span>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</body>

</html>