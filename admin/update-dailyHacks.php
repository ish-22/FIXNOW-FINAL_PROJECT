<?php
// Include the database connection
include('partials/main.php');

// Check if 'id' is passed in the URL to edit a specific hack
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the hack details from the database based on the ID
    $sql = "SELECT * FROM tbl_dailyhacks WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the hack details
            $hack = $result->fetch_assoc();
            $title = $hack['title'];
            $description = $hack['description'];
            $image = $hack['image']; // Keep the current image in case it is not updated
        } else {
            // If hack not found, redirect to the manage page
            header("Location: manage-dailyHacks.php?status=error&action=not-found");
            exit();
        }

        $stmt->close();
    }
} else {
    // If 'id' is not provided in the URL, redirect to the manage page
    header("Location: manage-dailyHacks.php?status=error&action=no-id");
    exit();
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Initialize the image variable (it will be NULL if no new image is uploaded)
    $new_image = $image; // Default to the existing image if no new image is provided

    // Handle the image upload if there is a new image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/hacks_images/"; // Set the directory where the images will be stored
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        // Check if the file is an image
        if (getimagesize($_FILES["image"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $new_image = $target_file; // Save the new image path if provided
            }
        }
    }

    // SQL query to update the hack in the database
    $sql = "UPDATE tbl_dailyhacks SET title = ?, description = ?, image = ? WHERE id = ?";

    // Prepare and bind parameters
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sssi", $title, $description, $new_image, $id);

        // Execute the query and check if the update was successful
        if ($stmt->execute()) {
            // Redirect to the manage page with success message
            header("Location: " . SITEURL . 'admin/manage-dailyHacks.php?status=success&action=update-hack');
        } else {
            // Redirect to the same page with error message
            header("Location: " . SITEURL . 'admin/manage-dailyHacks.php?status=error&action=update-hack');        }

        $stmt->close();
    } else {
        echo "Error preparing statement.";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

<div class="main-content">
    <div class="wrapper">
        <br />

        <!-- Form for updating a hack -->
        <form action="" method="POST" enctype="multipart/form-data" class="add-service-form">
            <h2>Update Hack</h2>

            <!-- Hack Title -->
            <div class="input-field">
                <label for="hack-title">Hack Title:</label>
                <input type="text" id="hack-title" name="title" value="<?php echo htmlspecialchars($title); ?>" required />
            </div>

            <!-- Hack Description -->
            <div class="input-field">
                <label for="hack-description">Hack Description:</label>
                <textarea id="hack-description" name="description" placeholder="Enter hack description" rows="10" cols="89" required><?php echo htmlspecialchars($description); ?></textarea>
            </div>

            <!-- Hack Image (Optional) -->
            <div class="input-field">
                <label for="hack-image">Hack Image (Optional):</label>
                <input type="file" id="hack-image" name="image" accept="image/*" />
            </div>

            <!-- Display current image if available -->
            <?php if ($image) { ?>
                <div class="input-field">
                    <label>Current Image:</label>
                    <img src="<?php echo $image; ?>" alt="Current Hack Image" style="width: 150px; height: auto;" />
                </div>
            <?php } ?>

            <!-- Submit Button -->
            <div class="input-field">
                <button type="submit" name="submit" class="btn-primary">Update Hack</button>
            </div>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>












