<?php

include('../config/constant.php');



// Check if the user is logged in
if (!isset($_SESSION['vendor_id'])) {
    echo "You need to log in to view this page.";
    exit();
}

// Get the logged-in vendor ID from the session
$vendor_id = $_SESSION['vendor_id'];

// Fetch vendor details from the database
$sql_vendor = "SELECT 
                sp.full_name AS vendor_name,
                sp.email,
                sp.contact_no AS contact_number,
                sp.experience,
                sp.available_dates_and_times AS available_times,
                sp.vendor_image,
                sp.about_you,
                sp.district_id,
                sp.town_id,
                d.district_name,
                t.town_name
            FROM tbl_service_providers sp
            LEFT JOIN tbl_districts d ON sp.district_id = d.id
            LEFT JOIN tbl_towns t ON sp.town_id = t.id
            WHERE sp.id = '$vendor_id'";

$result_vendor = mysqli_query($conn, $sql_vendor);

if ($result_vendor && mysqli_num_rows($result_vendor) == 1) {
    $vendor = mysqli_fetch_assoc($result_vendor);
} else {
    echo "Vendor not found.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $district_id = mysqli_real_escape_string($conn, $_POST['district_id']);
    $town_id = mysqli_real_escape_string($conn, $_POST['town_id']);
    $experience = mysqli_real_escape_string($conn, $_POST['experience']);
    $about_you = mysqli_real_escape_string($conn, $_POST['about_you']);

    // Handle profile picture upload
    $profile_picture = $vendor['vendor_image'];
    if (isset($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['name'] !== '') {
        $target_dir = "../images/vendor_images/";
        $profile_picture = basename($_FILES['profile_picture']['name']);
        $target_file = $target_dir . $profile_picture;
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
    }

    // Update vendor details in the database
    $sql_update = "UPDATE tbl_service_providers SET 
                    full_name = '$name',
                    email = '$email',
                    contact_no = '$phone',
                    district_id = '$district_id',
                    town_id = '$town_id',
                    experience = '$experience',
                    about_you = '$about_you',
                    vendor_image = '$profile_picture'
                  WHERE id = '$vendor_id'";

    if (mysqli_query($conn, $sql_update)) {
        echo "<script>alert('profile upadate successful!'); window.location.href = 'profile.php';</script>";
    } else {
        echo "<p>Error updating profile: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vendor Profile</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-edit-container {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }

        h1 {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select,
        textarea {
            width: 96%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            outline: none;
        }

        textarea {
            resize: vertical;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            font-weight: bold;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        /* Profile Picture */
        .profile-pic-preview {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 10px;
        }

        .profile-pic-preview img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.5);
        }

        select {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.2); /* Slightly transparent background */
    color: #fff; /* White text */
    outline: none;
    transition: border-color 0.3s ease, background-color 0.3s ease;
    -webkit-appearance: none; /* Remove default arrow styling in some browsers */
    -moz-appearance: none;
    appearance: none;
}

select option {
    background: #1e3c72; /* Dropdown option background color */
    color: #fff; /* Dropdown option text color */
}

select:focus {
    border-color: #00c6ff;
    background: rgba(255, 255, 255, 0.3);
}

option:checked {
    background: #0072ff; /* Selected option background color */
    color: #fff; /* Selected option text color */
}

    </style>
</head>
<body>
    <div class="profile-edit-container">
        <h1>Edit Vendor Profile</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <div class="profile-pic-preview">
                    <img id="preview" src="../images/vendor_images/<?php echo htmlspecialchars($vendor['vendor_image']); ?>" alt="Profile Picture">
                </div>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(event)">
            </div>

            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($vendor['vendor_name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($vendor['email']); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($vendor['contact_number']); ?>" required>
            </div>

            <div class="form-group">
    <label for="district_id">Select District:</label>
    <select id="district_id" name="district_id" required>
        <option value="">Select District</option>
        <?php
        $district_query = "SELECT * FROM tbl_districts";
        $district_result = mysqli_query($conn, $district_query);
        while ($district = mysqli_fetch_assoc($district_result)) {
            $selected = $district['id'] == $vendor['district_id'] ? 'selected' : '';
            echo "<option value=\"{$district['id']}\" $selected>{$district['district_name']}</option>";
        }
        ?>
    </select>
</div>

<div class="form-group">
    <label for="town_id">Select Town:</label>
    <select id="town_id" name="town_id" required>
        <option value="">Select Town</option>
        <?php
        $town_query = "SELECT * FROM tbl_towns WHERE district_id = '{$vendor['district_id']}'";
        $town_result = mysqli_query($conn, $town_query);
        while ($town = mysqli_fetch_assoc($town_result)) {
            $selected = $town['id'] == $vendor['town_id'] ? 'selected' : '';
            echo "<option value=\"{$town['id']}\" $selected>{$town['town_name']}</option>";
        }
        ?>
    </select>
</div>


            <div class="form-group">
                <label for="experience">Experience:</label>
                <input type="text" id="experience" name="experience" value="<?php echo htmlspecialchars($vendor['experience']); ?>" required>
            </div>

            <div class="form-group">
                <label for="about_you">About You:</label>
                <textarea id="about_you" name="about_you" rows="4" required><?php echo htmlspecialchars($vendor['about_you']); ?></textarea>
            </div>

            <div class="form-group">
                <button type="submit" name="update">Update Profile</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#district_id').on('change', function () {
            const districtId = $(this).val();
            if (districtId) {
                $.ajax({
                    url: 'get_towns.php', // The PHP file to handle the request
                    type: 'POST',
                    data: { district_id: districtId },
                    success: function (response) {
                        $('#town_id').html(response); // Update the town dropdown
                    },
                    error: function () {
                        alert('Failed to fetch towns. Please try again.');
                    }
                });
            } else {
                $('#town_id').html('<option value="">Select Town</option>'); // Reset town dropdown
            }
        });
    });
</script>
