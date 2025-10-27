<?php 
include('partials/main.php'); 

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $registration_fee = $_POST['fee_amount'];

    // Insert the registration fee into the tbl_registration_fees table
    $sql = "INSERT INTO tbl_registration_fees (fee_amount) VALUES (?)"; // Adjust the table and column name as needed
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $registration_fee);
        
        if ($stmt->execute()) {
            header("Location: " . SITEURL . 'admin/manage-registrationFee.php?status=success&action=add_fee');
        } else {
            header("Location: " . SITEURL . 'admin/manage-registrationFee.php?status=error&action=add_fee');
        }

        $stmt->close();
    }
}

?>

<div class="main-content">
    <div class="wrapper">
      <br><br><br><br><br><br>

        <!-- Add Registration Fee Section as a Form -->
        <form action="" method="POST" class="form-container">
            <label for="registration-fee" class="text_center">Add Registration Fee:</label>
            <input type="number" id="registration-fee" name="fee_amount" placeholder="Enter amount here" min="0" required>
            <input type="submit" name="submit" value="Add Fee" class="btn-primary">
        </form>

        <!-- Display Registration Fee and Action Buttons -->
        <div class="registration-fee-display">
            <?php
            // Fetch the current registration fee from the tbl_registration_fees table
            $sql = "SELECT id, fee_amount FROM tbl_registration_fees ORDER BY id DESC LIMIT 1"; // Fetch the latest fee
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $fee_id = $row['id']; // Define the fee_id here
                echo "<p>Current Registration Fee: <strong>" . $row['fee_amount'] . " /=</strong></p>";
            } else {
                echo "<p>No fee set yet.</p>";
            }
            ?>

            <!-- Show Delete Button if fee_id is set -->
            <?php if (isset($fee_id)): ?>
                <a href="delete-fee.php?id=<?php echo $fee_id; ?>" class="btn-danger">Delete</a>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>
