<?php

include('../config/constant.php');

// Get the vendor ID from the URL or set a default
$vendor_id = isset($_GET['vendor_id']) ? $_GET['vendor_id'] : null;

if (!$vendor_id) {
    echo "<p>Invalid vendor ID.</p>";
    exit;
}

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
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #fff;
        }

        .feedback-card {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
        }

        .feedback-card:hover {
            transform: scale(1.02);
        }

        .customer-name {
            font-weight: bold;
            font-size: 1.1rem;
            margin-bottom: 5px;
            color: #00e676;
        }

        .feedback-text {
            font-size: 1rem;
            margin: 10px 0;
            color: #f1f1f1;
        }

        .rating {
            color: #ffeb3b;
            margin-top: 5px;
        }

        .rating i {
            margin-right: 2px;
        }

        .date {
            font-size: 0.8rem;
            color: #ccc;
            margin-top: 10px;
        }

        .back-button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 1rem;
        font-weight: bold;
        color: #fff;
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
        border: none;
        border-radius: 25px;
        text-transform: uppercase;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        outline: none;
    }

    .back-button:hover {
        background: linear-gradient(135deg, #ff4b2b, #ff416c);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.4);
        transform: scale(1.05);
    }

    .back-button:active {
        transform: scale(0.98);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            h1 {
                font-size: 1.5rem;
            }

            .feedback-card {
                padding: 10px;
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
