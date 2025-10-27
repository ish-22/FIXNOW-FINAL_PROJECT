<?php

include('./config/constant.php');

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php"); 
    exit();
}
$customer_id = $_SESSION['customer_id']; 

$query = "SELECT * FROM tbl_customer WHERE id = '$customer_id'";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);

// Fetch districts for the dropdown
$district_query = "SELECT * FROM tbl_districts";
$district_result = mysqli_query($conn, $district_query);

// Fetch towns for the dropdown based on customer's district
$town_query = "SELECT * FROM tbl_towns WHERE district_id = '" . $customer['district_id'] . "'";
$town_result = mysqli_query($conn, $town_query);

// Check if form is submitted to update profile
if (isset($_POST['submit'])) {
    // Get form data
    $cus_name = mysqli_real_escape_string($conn, $_POST['cus_name']);
    $cus_phone = mysqli_real_escape_string($conn, $_POST['cus_phone']);
    $cus_email = mysqli_real_escape_string($conn, $_POST['cus_email']);
    $cus_address = mysqli_real_escape_string($conn, $_POST['cus_address']);
    $district_id = mysqli_real_escape_string($conn, $_POST['district_id']);
    $town_id = mysqli_real_escape_string($conn, $_POST['town_id']);

  
    $update_query = "UPDATE tbl_customer SET cus_name = '$cus_name', cus_phone = '$cus_phone', cus_email = '$cus_email', cus_address = '$cus_address', district_id = '$district_id', town_id = '$town_id' WHERE id = '$customer_id'";

    if (mysqli_query($conn, $update_query)) {
        // If a file is uploaded (for profile image)
        if (isset($_FILES['cus_image']) && $_FILES['cus_image']['name'] != "") {
            $image = $_FILES['cus_image'];
            $image_name = $customer_id . "_" . basename($image["name"]);
            $image_path = "images/cus_images/" . $image_name;

            if (move_uploaded_file($image["tmp_name"], $image_path)) {
                // Update the database with the new image
                $update_image_query = "UPDATE tbl_customer SET cus_image = '$image_name' WHERE id = '$customer_id'";
                mysqli_query($conn, $update_image_query);
            }
        }

        // Redirect to profile settings page with a success message
        header("Location: " . SITEURL . 'profile.php?status=success&action=update_profite');
    } else {
        // Redirect with error message
        header("Location: " . SITEURL . 'index.php?status=error&action=add-offer');
    }
}

// Fetch the updated customer data after modification
$query = "SELECT * FROM tbl_customer WHERE id = '$customer_id'";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings | FIXNOW</title>
    <link rel="shortcut icon" href="./assets/images/logo.png" type="image/svg+xml">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="profile.css">
    <script src="./js/script.js"></script>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            background: url('https://media.giphy.com/media/BHNfhgU63qrks/giphy.gif') fixed;
            padding: 0;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h4 {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .profile-img {
    width: 150px;
    height: 150px;
    border-radius: 50%; 
    object-fit: cover;
    border: 5px solid #007bff; 
    display: block;
    margin: 0 auto 20px auto;
    transition: transform 0.3s ease-in-out;
}

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            font-size: 16px;
            color: #333;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 8px;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
            width: 100%;
            margin-top: 20px;
            cursor: pointer;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .file-upload-btn {
            text-align: center;
            margin-top: 20px;
        }

        .file-upload-btn label {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .file-upload-btn label:hover {
            background-color: #0056b3;
        }

/* Button Container */
.button-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

/* Back Button */
.back-button {
    background-color: #007bff; /* Blue background */
    color: white; /* White text */
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.back-button:hover {
    background-color: #0056b3;
}

/* Booking History Button */
.booking-history-button {
    background-color: #28a745; /* Green background */
    color: white; /* White text */
    text-decoration: none;
    padding: 10px 20px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.booking-history-button:hover {
    background-color: #218838;
    text-decoration: none;
}


    </style>

    <script>
        // JavaScript to load towns dynamically based on selected district
        function fetchTowns(districtId) {
            let townSelect = document.getElementById('town_id');
            let xhr = new XMLHttpRequest();
            xhr.open('GET', 'profile_townfetch.php?district_id=' + districtId, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    let towns = JSON.parse(xhr.responseText);
                    townSelect.innerHTML = '<option value="">Select Town</option>';
                    towns.forEach(function(town) {
                        let option = document.createElement('option');
                        option.value = town.id;
                        option.textContent = town.town_name;
                        townSelect.appendChild(option);
                    });

                    // Set the current town as selected
                    if (townSelect) {
                        townSelect.value = '<?php echo $customer['town_id']; ?>';
                    }
                }
            };
            xhr.send();
        }

        // Load towns based on the current district ID (for when the page is loaded)
        window.onload = function() {
            fetchTowns(document.getElementById('district_id').value);
        }
    </script>
</head>
<body>

    <div class="container">
    <div class="button-container">
            <button class="back-button" onclick="window.history.back()">Back</button>
        </div>

        <h4>Account Settings</h4>

        <div class="card">
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="text-center">
                        <img src="./images/cus_images/<?php echo isset($customer['cus_image']) && file_exists('./images/cus_images/' . $customer['cus_image']) ? $customer['cus_image'] : 'default.jpg'; ?>" alt="Customer Image" class="profile-img">
                    </div>


                    <div class="file-upload-btn">
                        <input type="file" name="cus_image" id="cus_image" class="account-settings-fileinput">
                    </div>

                    <div class="form-group">
                        <label for="cus_name">Full Name</label>
                        <input type="text" class="form-control" value="<?php echo isset($customer['cus_name']) ? $customer['cus_name'] : ''; ?>" name="cus_name" required>
                    </div>

                    <div class="form-group">
                        <label for="cus_phone">Contact Number</label>
                        <input type="tel" class="form-control" value="<?php echo isset($customer['cus_phone']) ? $customer['cus_phone'] : ''; ?>" name="cus_phone" required>
                    </div>

                    <div class="form-group">
                        <label for="cus_email">E-mail</label>
                        <input type="text" class="form-control" value="<?php echo isset($customer['cus_email']) ? $customer['cus_email'] : ''; ?>" name="cus_email" required>
                    </div>

                    <div class="form-group">
                        <label for="cus_address">Address</label>
                        <input type="text" class="form-control" value="<?php echo isset($customer['cus_address']) ? $customer['cus_address'] : ''; ?>" name="cus_address" required>
                    </div>

                    <div class="form-group">
                        <label for="district_id">Select District:</label>
                        <select id="district_id" name="district_id" class="form-control" onchange="fetchTowns(this.value)" required>
                            <option value="">Select District</option>
                            <?php while ($district = mysqli_fetch_assoc($district_result)) { ?>
                                <option value="<?php echo $district['id']; ?>" <?php echo ($district['id'] == $customer['district_id']) ? 'selected' : ''; ?>>
                                    <?php echo $district['district_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="town_id">Select Town:</label>
                        <select id="town_id" name="town_id" class="form-control" required>
                            <!-- Town options will be loaded dynamically -->
                        </select>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>