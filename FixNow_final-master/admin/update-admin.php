<?php 
include('partials/main.php'); 

// Ensure that an ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id']; // Get the admin ID from the URL parameter
} else {
    // Redirect if no ID is provided
    header('Location: manage-admin.php');
    exit;
}

// Fetch current data for the given admin ID from the database
$sql = "SELECT * FROM tbl_admin WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $full_name = $row['full_name'];
    $username = $row['username'];
    $email = $row['email'];
} else {
    // Redirect if no matching admin found
    header('Location: manage-admin.php');
    exit;
}

?>

<div class="main-content">
    <div class="wrapper">
     
        <br />

        <!-- Form for managing admin details -->
        <form action="" method="POST" class="form-container">
        <h1 class="text_center">Update Admin</h1>
            <div class="input-field">
                <label for="full-name">Full Name:</label>
                <input type="text" id="full-name" name="full-name" value="<?php echo $full_name; ?>" required />
            </div>

            <div class="input-field">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required />
            </div>

            <div class="input-field">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required />
            </div>

            <!-- Hidden field for ID to identify the specific admin -->
            <input type="hidden" name="admin-id" value="<?php echo $id; ?>" />

            <div class="input-field">
                <input type="submit" name="submit" value="Update Admin" class="btn-primary">
            </div>
        </form>
    </div>
</div>

<?php

// Handle form submission
if (isset($_POST['submit'])) {
    // Get updated data from the form
    $id = $_POST['admin-id']; // Get the admin ID
    $full_name = $_POST['full-name'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Update the admin data in the database
    $sql = "UPDATE tbl_admin SET full_name = ?, username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $full_name, $username, $email, $id);

    // Check if the update was successful
    if ($stmt->execute()) {
        header("Location: " . SITEURL . 'admin/manage-user.php?status=success&action=update');
    } else {
        header("Location: " . SITEURL . 'admin/manage-user.php?status=error&action=update');
    }
}

include('partials/footer.php'); 
?>
