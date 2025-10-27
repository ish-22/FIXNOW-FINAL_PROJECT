<?php



// Include database connection
include('../config/constant.php');

// Handle feedback submission
if (isset($_POST['customer_name'], $_POST['feedback'], $_POST['rating'], $_POST['vendor_id']) && isset($_POST['submit_feedback'])) {
    // Get the POST data
    $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    $rating = (int)$_POST['rating']; // Ensure the rating is an integer
    $vendor_id = (int)$_POST['vendor_id'];

    // Validate rating to be between 1 and 5
    if ($rating >= 1 && $rating <= 5) {
        // Insert the feedback into the tbl_feedback table
        $query = "INSERT INTO tbl_feedback (vendor_id, customer_name, feedback, rating) 
                  VALUES ('$vendor_id', '$customer_name', '$feedback', '$rating')";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            // Redirect to the same page to avoid re-submission on page refresh
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        } else {
            echo "Error submitting feedback: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid rating value.";
    }
}

// Get vendor_id from URL (passed from navbar click)
$vendor_id = isset($_GET['service_provider_id']) ? (int)$_GET['service_provider_id'] : 0;

// Check if vendor_id is valid
if ($vendor_id == 0) {
    echo "Invalid vendor ID.";
    exit;
}

// Fetch vendor information based on vendor_id
$vendor_query = "SELECT full_name, vendor_image FROM tbl_service_providers WHERE id = '$vendor_id'";
$vendor_result = mysqli_query($conn, $vendor_query);

// Check if vendor exists
if (mysqli_num_rows($vendor_result) > 0) {
    $vendor = mysqli_fetch_assoc($vendor_result);
} else {
    echo "Vendor not found.";
    exit;
}

// Fetch feedback for the specific vendor
$feedback_query = "SELECT * FROM tbl_feedback WHERE vendor_id = '$vendor_id' ORDER BY created_at DESC";
$feedback_result = mysqli_query($conn, $feedback_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback for <?php echo htmlspecialchars($vendor['full_name']); ?></title>
    <style>
        /* General Page Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }

        h2, h3 {
            text-align: center;
            color: #444;
        }

        /* Container for Bookings and Feedback */
        .bookings-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .contact-box {
            display: flex;
            gap: 20px;
            align-items: center;
            padding: 20px;
            border-bottom: 2px solid #eaeaea;
        }

        .contact-box .left img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #ddd;
        }

        .contact-box .right {
            flex: 1;
        }

        .contact-box .right h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #555;
        }

        .contact-box .right .field {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }

        .contact-box .right textarea {
            height: 80px;
            resize: none;
        }

        .rating {
            display: flex;
            gap: 5px;
            justify-content: center;
            margin-bottom: 10px;
        }

        .rating label {
            font-size: 24px;
            color: #ddd;
            cursor: pointer;
        }

        .rating input[type="radio"] {
            display: none;
        }

        .rating input[type="radio"]:checked ~ label {
            color: #FFD700;
        }

        .contact-box .right .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .contact-box .right .btn:hover {
            background: #0056b3;
        }

        .feedback-section {
            margin-top: 20px;
        }

        .feedback-section h3 {
            font-size: 22px;
            margin-bottom: 10px;
            border-bottom: 2px solid #007BFF;
            display: inline-block;
            padding-bottom: 5px;
        }

        .feedback-item {
            background: #f0f8ff;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .feedback-item h4 {
            font-size: 18px;
            color: #333;
            margin-bottom: 5px;
        }

        .feedback-item p {
            font-size: 14px;
            margin-bottom: 10px;
            color: #555;
        }

        .feedback-item .rating {
            font-size: 20px;
            color: #FFD700;
        }

        /* Back Button Styles */
        .back-button {
            display: block; /* Initially hidden */
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
            margin-top: 10px;
            margin-left: 20px;
        }

        .back-button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
<section class="section-hero-bookings">
    <?php
    // Display the back button only if the user is logged in (i.e., has vendor_id in session)
    if (isset($_SESSION['vendor_id']) && $_SESSION['vendor_id'] == $vendor_id) {
        echo '<button class="back-button" onclick="window.history.back()">Back</button>';
    }
    ?>
    <div class="bookings-container">
        <!-- Vendor Information -->
        <div class="contact-box">
            <div class="left">
                <img src="../images/vendor_images/<?php echo htmlspecialchars($vendor['vendor_image']); ?>" alt="Vendor Image">
            </div>
            <div class="right">
                <h2>Feedback for <?php echo htmlspecialchars($vendor['full_name']); ?></h2>

                <?php
                // Check if the logged-in user is the service provider by checking session for vendor_id
                if (isset($_SESSION['vendor_id']) && $_SESSION['vendor_id'] == $vendor_id) {
                    // If the user is the service provider, don't show the feedback form
                  "";
                } else {
                ?>
                    <form action="" method="POST">
                        <input type="hidden" name="vendor_id" value="<?php echo $vendor_id; ?>">
                        <input type="text" name="customer_name" placeholder="Enter your name" class="field" required>
                        <textarea name="feedback" placeholder="Enter your feedback" class="field" required></textarea>

                        <div class="rating">
                            <input type="radio" id="star5" name="rating" value="5">
                            <label for="star5">&#9733;</label>
                            <input type="radio" id="star4" name="rating" value="4">
                            <label for="star4">&#9733;</label>
                            <input type="radio" id="star3" name="rating" value="3">
                            <label for="star3">&#9733;</label>
                            <input type="radio" id="star2" name="rating" value="2">
                            <label for="star2">&#9733;</label>
                            <input type="radio" id="star1" name="rating" value="1">
                            <label for="star1">&#9733;</label>
                        </div>

                        <button class="btn" type="submit" name="submit_feedback">Submit Feedback</button>
                    </form>
                <?php
                }
                ?>
            </div>
        </div>

        <!-- Feedback Display Section -->
        <div class="feedback-section">
            <h3>Customer Feedbacks</h3>

            <?php while ($feedback = mysqli_fetch_assoc($feedback_result)) { ?>
                <div class="feedback-item">
                    <h4><?php echo htmlspecialchars($feedback['customer_name']); ?></h4>
                    <p>"<?php echo nl2br(htmlspecialchars($feedback['feedback'])); ?>"</p>
                    <div class="rating">
                        <?php echo str_repeat("★", $feedback['rating']) . str_repeat("☆", 5 - $feedback['rating']); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
</body>
</html>
