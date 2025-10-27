<?php 
include('partials/main.php'); 
?>

<div class="main-content">
    <div class="wrapper">
       
        <br />

        <!-- Form for adding admin details -->
        <form action="#" method="POST" class="form-container">
        <h1 class="text_center">Add Admin</h1>
            <div class="input-field">
                <label for="full-name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter full name" required />
            </div>

            <div class="input-field">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter username" required />
            </div>

            <div class="input-field">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter email" required />
            </div>

            <!-- Password field -->
            <div class="input-field">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required />
            </div>

            <!-- Submit button -->
            <div class="input-field">
            <input type="submit" name="submit" value="Add Admin" class="btn-primary">
                    </div>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>

<?php
if (isset($_POST['submit'])) {
    // Getting form data
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    // SQL query to insert admin
    $sql = "INSERT INTO tbl_admin (full_name, username, email, password) VALUES (?, ?, ?, ?)";
    
    // Prepare and bind parameters
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $full_name, $username, $email, $password);
        
        // Execute the query and check if the insert was successful
        if ($stmt->execute()) {
            header("Location: " . SITEURL . 'admin/manage-user.php?status=success&action=add');
        } else {
            header("Location: " . SITEURL . 'admin/manage-user.php?status=error&action=add');
        }
    } else {
        echo "Error preparing statement.";
    }
}
?>


