<?php

ob_start();

include('./config/constant.php');


function emailExists($conn, $email, $table, $column)
{
    $query = "SELECT * FROM $table WHERE $column = '$email'";
    $result = mysqli_query($conn, $query);
    return (mysqli_num_rows($result) > 0);
}

// Handle the form submission for vendor registration
if (isset($_POST['submit'])) {

    $full_name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $contact_no = isset($_POST['contact_no']) ? $_POST['contact_no'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $district_id = isset($_POST['district_id']) ? $_POST['district_id'] : '';
    $town_id = isset($_POST['town_id']) ? $_POST['town_id'] : '';
    $category_id = isset($_POST['category_id']) ? $_POST['category_id'] : '';
    $servicetype_id = isset($_POST['servicetype_id']) ? $_POST['servicetype_id'] : '';
    $experience = isset($_POST['experience']) ? $_POST['experience'] : '';
    $available_dates_and_times = isset($_POST['available_dates_and_times']) ? $_POST['available_dates_and_times'] : '';
    $about_you = isset($_POST['about_you']) ? $_POST['about_you'] : '';


    if (emailExists($conn, $email, "tbl_service_providers", "email")) {
        echo "<script>alert('This email is already registered. Please use a different email.'); window.history.back();</script>";
        exit();
    }

    // Encrypt password
    $encrypted_password = md5($password);

    // Handle the vendor image upload
    $vendor_image = "";
    if (isset($_FILES['vendor_image']) && $_FILES['vendor_image']['name'] != "") {
        // Get the image name and extension
        $image_name = $_FILES['vendor_image']['name'];
        $ext = pathinfo($image_name, PATHINFO_EXTENSION);

        // Rename the image to avoid duplicate names
        $image_name = "vendor_" . rand(1000, 9999) . "." . $ext;

        // Set the target folder (make sure the folder exists and is writable)
        $target_directory = "images/vendor_images/";
        $target_file = $target_directory . $image_name;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['vendor_image']['tmp_name'], $target_file)) {
            // Successfully uploaded the image, use the image file path in the query
            $vendor_image = $image_name;
        } else {
            echo "Error uploading image.";
        }
    }

    // Insert vendor data into the database (including the vendor image)
    $query = "INSERT INTO tbl_service_providers (full_name, email, contact_no, password, district_id, town_id, category_id, servicetype_id, experience, available_dates_and_times, about_you, registration_fee_paid, vendor_image) 
              VALUES ('$full_name', '$email', '$contact_no', '$encrypted_password', '$district_id', '$town_id', '$category_id', '$servicetype_id', '$experience', '$available_dates_and_times', '$about_you', 'no', '$vendor_image')";

    if (mysqli_query($conn, $query)) {
        $service_provider_id = mysqli_insert_id($conn);

        // Generate the feedback URL
        $feedback_url = "http://localhost/fixnow/service-vendor/feedback.php?service_provider_id=" . $service_provider_id;

        // Update the service provider record with the feedback URL
        $update_url_query = "UPDATE tbl_service_providers SET feedback_url = '$feedback_url' WHERE id = '$service_provider_id'";
        mysqli_query($conn, $update_url_query);

        // Redirect to the payment page
        header("Location: registration-payment.php?service_provider_id=" . $service_provider_id);
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Handle the form submission for customer registration
if (isset($_POST['submit_customer'])) {

    $cus_name = $_POST['cus_name'];
    $cus_email = $_POST['cus_email'];
    $cus_phone = $_POST['cus_phone'];
    $cus_address = $_POST['cus_address'];
    $cus_password = $_POST['cus_password'];
    $district_id = $_POST['district_id'];
    $town_id = $_POST['town_id'];
    $remember_me = isset($_POST['remember_me']) ? 1 : 0;


    $encrypted_password = md5($cus_password);

    if (emailExists($conn, $cus_email, "tbl_customer", "cus_email")) {
        echo "<script>alert('This email is already registered. Please use a different email.'); window.history.back();</script>";
        exit();
    }


    $query = "INSERT INTO tbl_customer (cus_name, cus_email, cus_phone, cus_address, cus_password, district_id, town_id) 
              VALUES ('$cus_name', '$cus_email', '$cus_phone', '$cus_address', '$encrypted_password', '$district_id', '$town_id')";


    if (mysqli_query($conn, $query)) {

        header("Location: " . SITEURL . 'signin.php?status=success&action=cus_registration');
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

ob_end_flush();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@700&family=Montserrat&display=swap"
        rel="stylesheet">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.79.0">
    <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    <title>Registration | FIXNOW</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">

    <style>
        html,
        body {
            height: auto;
            min-height: 150vh;
            margin: 0;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-top: 60px;
            padding-bottom: 100px;
            width: 100%;
            background: url('https://media.giphy.com/media/BHNfhgU63qrks/giphy.gif?cid=ecf05e47gs8juvz6gqrfrfs7kc86bon2su9g4tna6frmvflq&ep=v1_gifs_search&rid=giphy.gif&ct=g') no-repeat center center fixed;
            background-size: cover;
        }

        .form-signin {
            width: 100%;
            max-width: 700px;
            padding: 20px 40px 10px 40px;
            margin: 20px auto 50px auto;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;

        }

        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 20px;
            font-size: 16px;
            margin: 2px;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .head-title {
            font-family: Comfortaa;
            font-size: x-large;
            font-weight: bolder;
            color: #3B91AA;
        }

        .toggle-btn {
            margin-top: 20px;
            cursor: pointer;
            color: #007bff;
            font-weight: bold;
        }

        .login-link {
            margin-top: 15px;
        }

        .customer-form {
            display: none;
            margin-top: 30px;
        }
    </style>
</head>

<body class="text-center">
    <form class="form-signin" method="POST" action="" id="service-provider-form" enctype="multipart/form-data">
        <img src="./assets/images/fixnow-logo.png" alt="Company Logo" width="300" height="50"><br>
        <br>
        <h1 class="head-title">Vendor Registration</h1>
        <button type="button" class="toggle-btn" onclick="swapUserType()">Switch to Customer</button>

        <input type="text" id="customer_name" name="full_name" class="form-control" placeholder="Full Name" required
            autofocus>
        <input type="email" id="customer_email" name="email" class="form-control" placeholder="Email" required>
        <input type="number" id="customer_phone" name="contact_no" class="form-control" placeholder="Phone Number"
            required>

        <input type="password" id="customer_password" name="password" class="form-control" placeholder="Password"
            required>

        <label for="cus_image">Upload Image (Optional):</label>
        <input type="file" class="form-control" id="vendor_image" name="vendor_image" accept="image/*">
        <!-- New Image Upload Field -->

        <label for="status">Select District:</label>
        <select id="district_id" name="district_id" class="form-control" required>
            <option value="">Select District</option>
            <?php
            $districts_query = "SELECT * FROM tbl_districts";
            $districts_result = mysqli_query($conn, $districts_query);
            while ($district = mysqli_fetch_assoc($districts_result)): ?>
                <option value="<?php echo $district['id']; ?>"><?php echo $district['district_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="town_id">Select Town:</label>
        <select id="town_id" name="town_id" class="form-control" required>
            <option value="">Select Town</option>
        </select>



        <label for="category_id">Service Category:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <option value="">Select Category</option>
            <?php
            $categories_query = "SELECT * FROM tbl_category";
            $categories_result = mysqli_query($conn, $categories_query);
            while ($category = mysqli_fetch_assoc($categories_result)): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo $category['category_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="servicetype_id">Service Type:</label>
        <select id="servicetype_id" name="servicetype_id" class="form-control" required>
            <option value="">Select Service Type</option>
        </select>



        <br>

        <input type="text" id="experience" name="experience" class="form-control" placeholder="Experience" required>
        <input type="text" id="available_dates_and_times" name="available_dates_and_times" class="form-control"
            placeholder="Available dates and times" required>
        <input type="text" id="about_you" name="about_you" class="form-control" placeholder="About you" required>

        <p class="registration-fee-text">Register as new service provider you must pay the registration fee.
        </p>

        <!-- <a href="registration-payment.php"  class="w-100 btn btn-lg btn-primary" target="_blank" class="payment-link">Click here </a> -->

        <input type="submit" name="submit" class="btn btn-lg btn-primary" value="click here">


        <p class="login-link"><a href="signin.php">Already have an account? Login here.</a></p>
        <p class="mt-5 mb-3 text-muted">&copy; Company Name</p>
    </form>








    <form class="form-signin customer-form" id="customer-form" method="POST" action="">
        <img src="./assets/images/fixnow-logo.png" alt="Company Logo" width="300" height="50"><br>
        <h1 class="head-title">Customer Registration</h1>
        <button type="button" class="toggle-btn" onclick="swapUserType()">Switch to Vendor</button>

        <input type="text" id="customer_name" name="cus_name" class="form-control" placeholder="Full Name" required
            autofocus>
        <input type="email" id="customer_email" name="cus_email" class="form-control" placeholder="Email" required>
        <input type="number" id="customer_phone" name="cus_phone" class="form-control" placeholder="Phone Number"
            required>
        <input type="text" id="customer_address" name="cus_address" class="form-control" placeholder="Address" required>
        <input type="password" id="customer_password" name="cus_password" class="form-control" placeholder="Password"
            required>
        <!-- <input type="password" id="customer_password" name="customer_password" class="form-control" placeholder="Confirm Password" required> -->


        <label for="district_id_customer">Select District:</label>
        <select id="district_id_customer" name="district_id" class="form-control" required>
            <option value="">Select District</option>
            <?php
            $districts_query = "SELECT * FROM tbl_districts";
            $districts_result = mysqli_query($conn, $districts_query);
            while ($district = mysqli_fetch_assoc($districts_result)): ?>
                <option value="<?php echo $district['id']; ?>"><?php echo $district['district_name']; ?></option>
            <?php endwhile; ?>
        </select>

        <label for="town_id_customer">Select Town:</label>
        <select id="town_id_customer" name="town_id" class="form-control" required>
            <option value="">Select Town</option>
        </select>

        <!-- Remember Me checkbox -->
        <br> <br>

        <input class="w-100 btn btn-lg btn-primary" id="submit_customer" name="submit_customer" type="submit"
            value="Register Customer">


        <!-- Already have an account link -->
        <p class="login-link"><a href="signin.php">Already have an account? Login here.</a></p>

        <p class="mt-5 mb-3 text-muted">&copy; FIXNOW</p>
    </form>






    <script>
        function validateForm(formId) {
            var form = document.getElementById(formId);
            var password = form.querySelector("[name='password']").value;
            var email = form.querySelector("[name='email']").value;

            // Password validation: minimum 4 characters
            if (password.length < 4) {
                alert("Password must be at least 4 characters long.");
                return false;
            }

            // Email validation: simple format check
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                return false;
            }

            return true;
        }





        function swapUserType() {
            var customerForm = document.getElementById('customer-form');
            var serviceProviderForm = document.getElementById('service-provider-form');
            var toggleButton = document.querySelector('.toggle-btn');

            if (customerForm.style.display === "none") {
                serviceProviderForm.style.display = "none";
                customerForm.style.display = "block";
                toggleButton.innerText = "Switch to Vendor";
            } else {
                serviceProviderForm.style.display = "block";
                customerForm.style.display = "none";
                toggleButton.innerText = "Switch to Customer";
            }
        }



        // Dynamically load towns based on selected district
        document.getElementById('district_id_customer').addEventListener('change', function() {
            var districtId = this.value; // Get the selected district ID

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'fetch_towns.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Update the customer town dropdown with the response
                    document.getElementById('town_id_customer').innerHTML = xhr.responseText;
                }
            };
            xhr.send('district_id=' + districtId); // Send the district ID to the PHP script
        });






        // Fetch towns based on district selection
        document.getElementById('district_id').addEventListener('change', function() {
            var district_id = this.value;

            fetch(`get_towns.php?district_id=${district_id}`)
                .then(response => response.json())
                .then(data => {
                    var townSelect = document.getElementById('town_id');
                    townSelect.innerHTML = '<option value="">Select Town</option>';
                    data.forEach(function(town) {
                        var option = document.createElement('option');
                        option.value = town.id;
                        option.textContent = town.town_name;
                        townSelect.appendChild(option);
                    });
                });
        });

        document.getElementById('category_id').addEventListener('change', function() {
            var category_id = this.value;

            // Make an AJAX request to get service types based on selected category
            fetch(`get_service_types.php?category_id=${category_id}`)
                .then(response => response.json())
                .then(data => {
                    var serviceTypeSelect = document.getElementById('servicetype_id');
                    serviceTypeSelect.innerHTML =
                        '<option value="">Select Service Type</option>'; // Reset options
                    data.forEach(function(service_type) {
                        var option = document.createElement('option');
                        option.value = service_type.id;
                        option.textContent = service_type.service_type;
                        serviceTypeSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching service types:', error);
                });
        });
    </script>
</body>

</html>