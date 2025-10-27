
<?php
include('./config/constant.php');


if (isset($_POST['submit'])) {
    // Get form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $query = "INSERT INTO tbl_contact (name, phone, email, message) 
              VALUES ('$name', '$phone', '$email', '$message')";
    
    if (mysqli_query($conn, $query)) {
        header('Location: ' . SITEURL . 'index.php?status=success&action=contact_message');
        exit();
    } else {
        header("Location: " . SITEURL . 'index.php?status=error&action=contact_message');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNow - Contact Support</title>
    <link rel="shortcut icon" href="./assets/images/logo.png" type="image/svg+xml">
    <link rel="stylesheet" href="assets/css/contact.css">
    <script src="./js/script.js"></script>
</head>

<body>
    <div class="container">
        <h1>Contact Support</h1>
        <p>If you have any questions, concerns, or need assistance, please fill out the form below and our support team will get back to you as soon as possible.</p>

        <div class="contact-box">
            <div class="container-left">
                <h3>Submit Your Query</h3>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="input-row">
                        <div class="input-group">
                            <label for="name">Full Name*</label>
                            <input type="text" id="name" name="name" placeholder="Enter your Full Name" required>
                        </div>

                        <div class="input-group">
                            <label for="phone">Phone Number*</label>
                            <input type="text" id="phone" name="phone" placeholder="+94 1234567890" required>
                        </div>
                    </div>

                    <div class="input-row">
                        <div class="input-group" id="email-group">
                            <label for="email">Email Address*</label>
                            <input type="email" id="email" name="email" placeholder="youremail@gmail.com" required>
                        </div>
                    </div>

                    <label for="message">Message*</label>
                    <textarea rows="10" cols="30" id="message" name="message" placeholder="Describe your inquiry or issue" required></textarea>
                    <input type="submit" name="submit" value="Send" class="btn-primary">

                </form>

            </div>


            <div class="container-right">
                <h3>Contact Information</h3>
                <table>
                    <tr>
                        <td>Email:</td>
                        <td>fixnowcompanypvt@gmail.com</td>
                    </tr>

                    <tr>
                        <td>Phone:</td>
                        <td>+94 011-2345678</td>
                    </tr>

                    <tr>
                        <td>Address:</td>
                        <td>FixNow Solutions <br>
                            No. 75, Galle Road, <br>
                            Colombo 03, Sri Lanka</td>
                    </tr>
                </table>

                <!-- Map section -->
                <div class="map">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12611.355859295573!2d79.95823864697266!3d6.933054749999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2592836fca451%3A0x31b9a5cb6251b545!2sGalle%20Road%2C%20Colombo%2003%2C%20Sri%20Lanka!5e0!3m2!1sen!2sin!4v1711214006526!5m2!1sen!2sin" width="800" height="275" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
 
</body>



</html>