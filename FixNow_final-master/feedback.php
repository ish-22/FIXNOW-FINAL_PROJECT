<?php

include('./config/constant.php');

// Get the vendor ID from the URL or set a default
$vendor_id = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : null;

if (!$vendor_id) {
    echo "<p>Invalid vendor ID.</p>";
    exit;
}

// Fetch vendor's full name
$sql_vendor = "SELECT full_name FROM tbl_service_providers WHERE id = '$vendor_id'";
$result_vendor = mysqli_query($conn, $sql_vendor);
$vendor_data = mysqli_fetch_assoc($result_vendor);
$vendor_name = $vendor_data ? $vendor_data['full_name'] : 'Vendor';

// Fetch feedback data for the vendor
$sql_feedback = "SELECT customer_name, feedback, rating, created_at 
                 FROM tbl_feedback 
                 WHERE vendor_id = '$vendor_id' 
                 ORDER BY created_at DESC";
$result_feedback = mysqli_query($conn, $sql_feedback);

// Check if feedback exists
$feedback_data = [];
if (mysqli_num_rows($result_feedback) > 0) {
    while ($row = mysqli_fetch_assoc($result_feedback)) {
        $feedback_data[] = $row;
    }
} else {
    echo "<p style='
    color: #ff0000; 
    font-size: 18px; 
    font-weight: bold; 
    text-align: center; 
    background: #ffecec; 
    padding: 15px; 
    border-radius: 8px; 
    border: 1px solid #ffb3b3;
    max-width: 60%;
    margin: 20px auto;
    '>
    No feedback available for this vendor.
    </p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <title>Feedback</title>
    <style>
        /* General Styles */
/* Global Styles */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    background: rgb(199, 157, 157);  /* Pure White Background */
    color: #333; /* Dark Gray Text for Readability */
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Container Box */
.container {
    width: 90%;
    max-width: 1200px;
    background: rgb(199, 157, 157); /* White Background */
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); /* Subtle Shadow */
}

/* Main Title */
h1 {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 20px;
    color: #222; /* Slightly Darker Text for Contrast */
}

/* Feedback Cards */
.feedback-card {
    background:rgb(209, 183, 183); /* Light Grayish White */
    border-radius: 10px;
    padding: 18px;
    margin-bottom: 15px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05); /* Light Box Shadow */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.feedback-card:hover {
    transform: scale(1.02);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

/* Customer Name */
.customer-name {
    font-weight: bold;
    font-size: 1.2rem;
    margin-bottom: 5px;
    color: #000; /* Black for Strong Contrast */
}

/* Feedback Text */
.feedback-text {
    font-size: 1rem;
    margin: 10px 0;
    color: #444;
}

/* Rating Stars */
.rating {
    color: #666; /* Subtle Gray */
    margin-top: 5px;
}

.rating i {
    margin-right: 2px;
}

/* Date */
.date {
    font-size: 0.85rem;
    color: #777;
    margin-top: 10px;
}

/* Back Button */
.back-button {
    display: block;
    width: fit-content;
    margin: 20px auto;
    padding: 12px 25px;
    font-size: 1rem;
    font-weight: bold;
    color: #fff;
    background: #000; /* Black Button for Strong Contrast */
    border: none;
    border-radius: 30px;
    text-transform: uppercase;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
    outline: none;
}

.back-button:hover {
    background: #333; /* Dark Gray for Hover Effect */
    box-shadow: 0 5px 12px rgba(0, 0, 0, 0.3);
    transform: scale(1.05);
}

.back-button:active {
    transform: scale(0.98);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

/* Responsive Styles */
@media (max-width: 768px) {
    .container {
        padding: 18px;
    }

    h1 {
        font-size: 1.8rem;
    }

    .feedback-card {
        padding: 14px;
    }
}

    </style>
</head>
<body>

    <div class="container">
    <button class="back-button" onclick="window.history.back()">Back</button>

    <h1><?php echo htmlspecialchars($vendor_name); ?>'s Feedbacks</h1>
        <?php foreach ($feedback_data as $feedback): ?>
            <div class="feedback-card">
                <div class="customer-name"><?php echo htmlspecialchars($feedback['customer_name']); ?></div>
                <div class="feedback-text"><?php echo htmlspecialchars($feedback['feedback']); ?></div>
                <div class="rating">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= $feedback['rating']): ?>
                            <i class="ri-star-fill"></i>
                        <?php else: ?>
                            <i class="ri-star-line"></i>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <div class="date">Submitted on: <?php echo date('F j, Y, g:i a', strtotime($feedback['created_at'])); ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
