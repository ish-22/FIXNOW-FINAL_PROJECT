
<?php
include('../config/constant.php');
$vendor_id = isset($_SESSION['vendor_id']) ? $_SESSION['vendor_id'] : ''; 

if (empty($vendor_id)) {
    header("Location: login.php");
    exit();
}
$query_messages = "SELECT cm.id, cm.customer_id, cm.message, cm.vendor_reply, cm.sent_at, cm.status, c.cus_name 
                   FROM tbl_chat_messages cm
                   JOIN tbl_customer c ON cm.customer_id = c.id
                   WHERE cm.vendor_id = '$vendor_id'
                   ORDER BY cm.sent_at DESC";

$result_messages = mysqli_query($conn, $query_messages);

$messages = [];
$customers = []; 

if (mysqli_num_rows($result_messages) > 0) {
    while ($row = mysqli_fetch_assoc($result_messages)) {
        $messages[] = $row;
        if (!in_array($row['customer_id'], $customers)) {
            $customers[] = $row['customer_id'];
        }
    }
}

if (isset($_POST['send_reply'])) {
    $customer_id = mysqli_real_escape_string($conn, $_POST['customer_id']);
    $reply_message = mysqli_real_escape_string($conn, $_POST['reply_message']);
    
    $query_reply = "UPDATE tbl_chat_messages 
                    SET vendor_reply = '$reply_message', status = 'sent' 
                    WHERE customer_id = '$customer_id' AND vendor_id = '$vendor_id' AND vendor_reply IS NULL 
                    ORDER BY sent_at DESC LIMIT 1";
    
    if (mysqli_query($conn, $query_reply)) {
        header("Location: chat_serviceProvider.php?vendor_id=$vendor_id&status=success&action=reply_vendor");
                exit();
    } else {
        echo "<script>alert('Failed to send reply.');</script>";
    }
}




// Clear messages for display (without deleting from database)
if (isset($_POST['clear_chat'])) {
    // Update the status of all messages for the service provider to 'cleared' so they won't display
    $query_clear = "UPDATE tbl_chat_messages SET status = 'cleared' WHERE vendor_id = '$vendor_id' AND status = 'sent'";
    if (mysqli_query($conn, $query_clear)) {
        // Redirect to avoid form resubmission
        header("Location: chat_serviceProvider.php?vendor_id=$vendor_id");
        exit();
    }
}
?>

<!-- HTML for Service Provider's Chat Page -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with Customers</title>
    <script src="../js/script.js"></script>
 
    <style>
/* Modern Chat System Styling */
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f5f7fa;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.chat-container {
    width: 90%;
    max-width: 800px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-top: 100px;
}

h2 {
    text-align: center;
    color: #333;
    font-weight: 600;
    margin: 0;
}

.back-btn {
    display: flex;
    justify-content: flex-start;
    margin-bottom: 5px;
}

.back-btn a {
    text-decoration: none;
    color: #fff;
    background-color: #007bff;
    padding: 8px 10px;
    border-radius: 5px;
    font-weight: 400;
    transition: 0.3s;
}

.back-btn a:hover {
    background-color: #0056b3;
}


/* Chat Box */
.chat-box {
    background: #f0f2f5;
    border-radius: 8px;
    padding: 15px;
    max-height: 300px;
    overflow-y: auto;
}

.message {
    background: #fff;
    border-radius: 8px;
    padding: 10px 15px;
    margin-bottom: 10px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    position: relative;
}

.message strong {
    color: #007bff;
    font-size: 14px;
}

.message p {
    font-size: 14px;
    margin: 5px 0;
    color: #444;
}

.message small {
    font-size: 12px;
    color: #888;
}

.reply {
    background: #e9f5ff;
    border-left: 4px solid #007bff;
    padding: 8px;
    border-radius: 5px;
    margin-top: 5px;
}

.reply strong {
    color: #007bff;
}

/* Reply Section */
.reply-section {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
}

.reply-section label {
    font-weight: 500;
    display: block;
    margin: 10px 0 5px;
}

select, textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 14px;
    background: #f9f9f9;
}
#reply_message{
    width: 98%;
}

.reply-btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px;
    width: 100%;
    border-radius: 5px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    margin-top: 10px;
}

.reply-btn:hover {
    background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 600px) {
    .chat-container {
        width: 95%;
    }

    .reply-btn {
        padding: 12px;
    }
}

    </style>
</head>
<body>


    <div class="chat-container">
        <h2>Service Provider's Chat Dashboard</h2>
        <div class="back-btn">
    <a href="index.php">← Back</a>
</div>
        <!-- Display Chat Messages -->
        <div class="chat-box">
            <?php if (count($messages) > 0): ?>
                <?php foreach ($messages as $message): ?>
                    <?php if ($message['status'] !== 'cleared'): ?>
                        <div class="message">
                            <strong><?php echo htmlspecialchars($message['cus_name']); ?>:</strong>
                            <p><?php echo htmlspecialchars($message['message']); ?></p>
                            <small><?php echo $message['sent_at']; ?> (Status: <?php echo $message['status']; ?>)</small>
                            <?php if (!empty($message['vendor_reply'])): ?>
                                <div class="reply">
                                    <strong>Vendor:</strong>
                                    <p><?php echo htmlspecialchars($message['vendor_reply']); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No messages yet.</p>
            <?php endif; ?>
        </div>

        <!-- Reply Section -->
        <div class="reply-section">
            <form action="" method="POST">
                <label for="customer_id">Select Customer:</label>
                <select name="customer_id" required>
                    <?php foreach ($customers as $customer_id): ?>
                        <?php 
                        // Get the customer details (name)
                        $query_customer = "SELECT cus_name FROM tbl_customer WHERE id = '$customer_id'";
                        $result_customer = mysqli_query($conn, $query_customer);
                        $customer = mysqli_fetch_assoc($result_customer);
                        ?>
                        <option value="<?php echo $customer_id; ?>"><?php echo htmlspecialchars($customer['cus_name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="reply_message">Reply:</label>
                <textarea name="reply_message" id="reply_message" rows="4" cols="40" placeholder="Type your reply..." required></textarea>

                <button type="submit" name="send_reply" class="reply-btn">Send Reply</button>
            </form>
        </div>

        <!-- Clear Messages Button -->
         
   
    </div>
</body>
</html>








