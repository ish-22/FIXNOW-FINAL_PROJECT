<?php

include('./config/constant.php');

// Ensure the customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: signin.php");
    exit();
}

$customer_id = $_SESSION['customer_id']; 
$vendor_id = $_GET['vendor_id'];

if (empty($vendor_id)) {
    echo "<p>Invalid vendor ID provided.</p>";
    exit;
}

$vendor_query = "SELECT * FROM tbl_service_providers WHERE id = '$vendor_id' AND status = 'Confirmed'";
$vendor_result = mysqli_query($conn, $vendor_query);

if (mysqli_num_rows($vendor_result) === 0) {
    echo "<p>No vendor found or the vendor is not confirmed.</p>";
    exit;
}

$vendor = mysqli_fetch_assoc($vendor_result);

$query_chat_history = "SELECT * FROM tbl_chat_messages 
                       WHERE (customer_id = '$customer_id' AND vendor_id = '$vendor_id') 
                       OR (vendor_id = '$vendor_id' AND customer_id = '$customer_id') 
                       ORDER BY sent_at ASC";

$chat_result = mysqli_query($conn, $query_chat_history);

$query_update_status = "UPDATE tbl_chat_messages SET status = 'read' WHERE customer_id = '$customer_id' AND vendor_id = '$vendor_id' AND status = 'sent'";
mysqli_query($conn, $query_update_status);

if (isset($_POST['send_message'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $query_insert_message = "INSERT INTO tbl_chat_messages (customer_id, vendor_id, message, status) 
                             VALUES ('$customer_id', '$vendor_id', '$message', 'sent')";
    
    if (mysqli_query($conn, $query_insert_message)) {
        echo "<script>alert('Message sent successfully!'); window.location.href = 'chat.php?vendor_id=$vendor_id';</script>";
    } else {
        echo "<script>alert('Failed to send message.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Vendor</title>
    <style>
    /* General Styling */
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(to right, #f5f5f5, #d2a679); 
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    /* Back Button */
.back-button {
    position: absolute;
    top: 20px;
    left: 20px;
    background: #d2a679; /* Brown tone to match */
    color: white;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    padding: 10px 15px;
    border-radius: 8px;
    transition: 0.3s;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
}

.back-button:hover {
    background: #b37450; /* Darker brown on hover */
    transform: scale(1.05);
}


    /* Chat Container */
    .chat-container {
        width: 500px;
        background: white;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.5s ease-in-out;
    }

    /* Header */
    .chat-container h2 {
        text-align: center;
        color: #333;
        font-size: 22px;
        margin-bottom: 15px;
        font-weight: 600;
        padding-bottom: 10px;
        border-bottom: 2px solid #007bff;
    }

    /* Chat Box */
    .chat-box {
        max-height: 400px;
        overflow-y: auto;
        padding: 15px;
        background: #f1f1f1;
        border-radius: 10px;
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Chat Bubbles */
    .message {
        padding: 12px;
        margin: 5px 0;
        border-radius: 12px;
        font-size: 14px;
        max-width: 85%;
        word-wrap: break-word;
        box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.1);
        opacity: 0;
        animation: slideIn 0.5s ease-in-out forwards;
    }

    /* Customer Message */
    .message.customer {
        background: #007bff;
        color: white;
        text-align: right;
        align-self: flex-end;
    }

    /* Vendor Message */
    .message.vendor {
        background: #ddd;
        color: #333;
        text-align: left;
        align-self: flex-start;
    }

    /* Reply Info */
    .reply-message {
        text-align: center;
        font-size: 13px;
        color: #666;
        font-style: italic;
        margin-top: 5px;
    }

    /* Chat Input */
    form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    textarea {
        width: 100%;
        height: 60px;
        padding: 12px;
        border-radius: 10px;
        border: 1px solid #ccc;
        resize: none;
        font-size: 14px;
        box-shadow: inset 2px 2px 5px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
    }

    textarea:focus {
        border: 1px solid #007bff;
        outline: none;
        box-shadow: 0px 0px 8px rgba(0, 123, 255, 0.3);
    }

    /* Send Button */
    button {
        background: linear-gradient(to right, #007bff, #0056b3);
        color: white;
        border: none;
        padding: 12px 18px;
        border-radius: 10px;
        cursor: pointer;
        font-size: 15px;
        font-weight: 600;
        transition: 0.3s ease-in-out;
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
    }

    button:hover {
        background: linear-gradient(to right, #0056b3, #004199);
        transform: scale(1.05);
    }

    /* Scrollbar */
    .chat-box::-webkit-scrollbar {
        width: 8px;
    }

    .chat-box::-webkit-scrollbar-thumb {
        background: #007bff;
        border-radius: 10px;
    }

    .chat-box::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>



</head>
<body>

<a href="vendors-details.php?vendor_id=<?php echo $vendor_id; ?>" class="back-button">← Back</a>

<div class="chat-container">
    <h2>Chat with <?php echo htmlspecialchars($vendor['full_name']); ?></h2>

    <div class="chat-box">
        <?php
        // Display chat history - only show the logged-in customer's messages and vendor's replies
        while ($chat = mysqli_fetch_assoc($chat_result)) {
            // Display the customer's message (from the message field)
            if ($chat['customer_id'] == $customer_id && !empty($chat['message'])) {
                echo "<div class='message'><strong>You:</strong> " . htmlspecialchars($chat['message']) . "</div>";
            }

            // Display the vendor's reply (from the vendor_reply field)
            if ($chat['vendor_id'] == $vendor_id && !empty($chat['vendor_reply'])) {
                echo "<div class='message'><strong>" . htmlspecialchars($vendor['full_name']) . " (Reply):</strong> " . htmlspecialchars($chat['vendor_reply']) . "</div>";
            }
        }
        ?>
    </div>

    <!-- Display "Reply will be soon..." message if needed -->
    <div class="reply-message">
        <span>Reply will be soon...</span>
    </div>

    <!-- Message sending form -->
    <form method="POST" action="">
        <textarea name="message" placeholder="Type your message here..." required></textarea>
        <button type="submit" name="send_message">Send</button>
    </form>
</div>

</body>
</html>
