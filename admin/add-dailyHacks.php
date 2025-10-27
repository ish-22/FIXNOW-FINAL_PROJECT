<?php
// Include the database connection
include('partials/main.php');

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $title = $_POST['title'];
    $description = $_POST['description'];

    // Handle the image upload
    $image = NULL;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/hacks_images/"; // Set the directory where the images will be stored
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        // Check if the file is an image
        if (getimagesize($_FILES["image"]["tmp_name"])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file; // Save the image path to the image column
            }
        }
    }

    // SQL query to insert the new hack into the database
    $sql = "INSERT INTO tbl_dailyhacks (title, description, image) VALUES (?, ?, ?)";

    // Prepare and bind parameters
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $title, $description, $image);

        // Execute the query and check if the insert was successful
        if ($stmt->execute()) {
            header("Location: manage-dailyHacks.php?status=success&action=add-hack");
        } else {
            header("Location: manage-dailyHacks.php?status=error&action=add-hack");
        }
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

        <!-- Form for adding a new hack -->
        <form action="" method="POST" enctype="multipart/form-data" class="add-service-form">
            <h2>Add a New Hack</h2>

            <!-- Hack Title -->
            <div class="input-field">
                <label for="hack-title">Hack Title:</label>
                <input type="text" id="hack-title" name="title" placeholder="Enter hack title" required />
            </div>

            <!-- Hack Description -->
            <div class="input-field">
                <label for="hack-description">Hack Description:</label>
                <textarea id="hack-description" name="description" placeholder="Enter hack description" rows="10" cols="89" required></textarea>
            </div>

            <!-- Hack Image -->
            <div class="input-field">
                <label for="hack-image">Hack Image (Optional):</label>
                <input type="file" id="hack-image" name="image" accept="image/*" />
            </div>

            <!-- Submit Button -->
            <div class="input-field">
                <input type="submit" name="submit" value="Add Hack" class="btn-primary">
            </div>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
