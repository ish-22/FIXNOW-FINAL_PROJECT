<?php 
include('partials/main.php'); 

// Get the current payment fee to display
$query = "SELECT * FROM tbl_payment ORDER BY id DESC LIMIT 1"; // Get the latest payment fee
$result = mysqli_query($conn, $query);
$payment = mysqli_fetch_assoc($result);

// Handle form submission for updating payment fee
if (isset($_POST['submit'])) {
    // Get the form data
    $payment_amount = mysqli_real_escape_string($conn, $_POST['payment_amount']);

    // Validate the input
    if (empty($payment_amount)) {
        // Handle error if fields are empty
        echo "<script>alert('Please fill in the payment amount.');</script>";
    } else {
        // Prepare the SQL query to update the payment fee
        $query = "UPDATE tbl_payment SET payment_amount = '$payment_amount' WHERE id = '{$payment['id']}'";

        // Execute the query
        if (mysqli_query($conn, $query)) {
            // Redirect to the same page after successful update
            echo "<script>
                    alert('Payment fee updated successfully!');
                    window.location.href = 'add-amount.php'; // Redirect to the manage payment fees page
                  </script>";
        } else {
            // Handle query execution failure
            echo "<script>alert('Error updating payment fee: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <h2 class="text_center">Update Payment Fee</h2>

        <!-- Update Payment Fee Section as a Form -->
        <form action="" method="POST" class="form-container">
            <label for="payment_amount">Update Payment Fee:</label>
            <input type="number" id="payment_amount" name="payment_amount" placeholder="Enter amount here" min="0" value="<?php echo $payment['payment_amount']; ?>" required>

            <input type="submit" name="submit" value="Update Fee" class="btn-primary">
        </form>


    </div>
</div>

<?php include('partials/footer.php'); ?>
