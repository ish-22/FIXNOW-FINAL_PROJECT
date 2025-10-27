<?php include('../config/constant.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Feedback URL - Service Provider</title>
    <script src="../js/script.js"></script>
    
    <!-- Link to your CSS files -->
    <style>
/* Global Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f6f9;
    margin: 0;
    padding: 0;
    color: #333;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin-top: 0;
}

/* Container for the Feedback URL Form */
.add-feedback-container {
    max-width: 600px;
    width: 100%;
    padding: 30px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    text-align: center;
    border: 1px solid #ddd;
}

.add-feedback-container h2 {
    font-size: 2rem;
    color: #007bff;
    margin-bottom: 20px;
    font-weight: 600;
}

.add-feedback-container label {
    display: block;
    font-size: 1rem;
    margin-bottom: 8px;
    color: #555;
    font-weight: bold;
}

.add-feedback-container input[type="url"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 2px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s ease;
}

.add-feedback-container input[type="url"]:focus {
    border-color: #007bff;
}

.add-feedback-container button {
    width: 100%;
    padding: 12px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 1.2rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.add-feedback-container button:hover {
    background-color: #218838;
}

/* Error and Success Messages */
.error-message, .success-message {
    font-size: 1rem;
    margin-bottom: 20px;
}

.error-message {
    color: #dc3545;
}

.success-message {
    color: #28a745;
}

/* Responsive Design for Mobile Devices */
@media (max-width: 768px) {
    .add-feedback-container {
        padding: 20px;
    }

    .add-feedback-container h2 {
        font-size: 1.5rem;
    }

    .add-feedback-container label {
        font-size: 0.9rem;
    }

    .add-feedback-container input[type="url"] {
        padding: 10px;
        font-size: 0.9rem;
    }

    .add-feedback-container button {
        font-size: 1rem;
    }
}

    </style>
</head>
<body>

<section class="section-hero-bookings">
    <div class="add-feedback-container">
        <h2>Add Your Feedback URL</h2>
        
        <?php
        // Check for session and service provider
        if (isset($_SESSION['vendor_id'])) {
            $service_provider_id = $_SESSION['vendor_id']; // Get service provider ID from session
        ?>
        
        <form action="add_feedback_url.php" method="POST">
            <label for="feedback_url">Enter Your Feedback URL:</label>
            <input type="url" name="feedback_url" id="feedback_url" placeholder="https://example.com/feedback" required>
            <button type="submit" name="submit">Add/Update Feedback URL</button>
        </form>

        <?php
        // Handle form submission and update URL if "submit" is set
        if (isset($_POST['submit'])) {
            if (isset($_POST['feedback_url'])) {
                $feedback_url = $_POST['feedback_url'];

                $feedback_url = mysqli_real_escape_string($conn, $feedback_url);

                // Validate URL
                if (filter_var($feedback_url, FILTER_VALIDATE_URL)) {
                    $update_query = "UPDATE tbl_service_providers SET feedback_url = '$feedback_url' WHERE id = '$service_provider_id'";
                    if (mysqli_query($conn, $update_query)) {
                        header("Location: " . SITEURL . 'service-vendor/profile.php?status=success&action=add_url');
                    } else {
                        echo '<div class="error-message">Error updating the URL. Please try again later.</div>';
                    }
                } else {
                    echo '<div class="error-message">Please enter a valid URL.</div>';
                }
            }
        }
        ?>

        <?php } else { ?>
            <div class="error-message">You must be logged in to update your feedback URL.</div>
        <?php } ?>
    </div>
</section>

</body>
</html>
