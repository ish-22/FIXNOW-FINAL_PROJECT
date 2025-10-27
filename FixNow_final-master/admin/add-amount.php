<?php 
include('partials/main.php'); 

// Handle form submission for adding payment fee
if (isset($_POST['submit'])) {
    // Get the form data
    $payment_amount = mysqli_real_escape_string($conn, $_POST['payment_amount']);

    // Validate the input
    if (empty($payment_amount)) {
        // Handle error if fields are empty
        echo "<script>alert('Please fill in the payment amount.');</script>";
    } else {
        // Prepare the SQL query to insert the payment fee
        $query = "INSERT INTO tbl_payment (payment_amount) VALUES ('$payment_amount')";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            // Redirect to the same page after successful insertion
            echo "<script>
                    alert('Payment fee added successfully!');
                    window.location.href = 'add-amount.php'; // Redirect to the manage payment fees page
                  </script>";
        } else {
            // Handle query execution failure
            echo "<script>alert('Error adding payment fee: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// Fetch the latest payment fee from the database
$query = "SELECT payment_amount FROM tbl_payment ORDER BY id DESC LIMIT 1"; // Get the latest payment
$result = mysqli_query($conn, $query);
$payment = mysqli_fetch_assoc($result);
?>

<div class="main-content">
    <div class="wrapper">
     <br><br><br><br><br>

        <!-- Add Payment Fee Section as a Form -->
        <form action="" method="POST" class="form-container">
            <label for="payment_amount" class="text_center">Add Payment Fee</label>
            <input type="number" id="payment_amount" name="payment_amount" placeholder="Enter amount here" min="0" required>

            <input type="submit" name="submit" value="Add Fee" class="btn-primary">
        </form>

        <!-- Display the Current Payment Fee -->
        <div class="registration-fee-display">
            <?php if ($payment): ?>
                <p>Current payment amount: <strong><?php echo $payment['payment_amount']; ?> /=</strong></p>
            <?php else: ?>
                <p>No payment fees have been set yet.</p>
            <?php endif; ?>
            <a href="update-amount.php" class="btn-secondary">Update</a>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>
