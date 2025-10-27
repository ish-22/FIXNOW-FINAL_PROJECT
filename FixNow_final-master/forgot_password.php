<?php
include('./config/constant.php');


if (isset($_POST['submit_email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check in both tables for the email
    $query_customer = "SELECT * FROM tbl_customer WHERE cus_email = '$email'";
    $query_vendor = "SELECT * FROM tbl_service_providers WHERE email = '$email'";

    $result_customer = mysqli_query($conn, $query_customer);
    $result_vendor = mysqli_query($conn, $query_vendor);

    if (mysqli_num_rows($result_customer) > 0) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['user_type'] = "customer";
        header("Location: reset_password.php");
        exit();
    } elseif (mysqli_num_rows($result_vendor) > 0) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['user_type'] = "vendor";
        header("Location: reset_password.php");
        exit();
    } else {
        $error = "Email not found. Please enter a registered email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | FIXNOW</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .forgot-password-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .forgot-password-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .input-field {
            position: relative;
            margin-bottom: 20px;
        }

        .input-field input {
            width: 100%;
            padding: 12px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
        }

        .input-field i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #2575fc;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #1e5ac3;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .back-to-login {
            margin-top: 15px;
        }

        .back-to-login a {
            color: #2575fc;
            text-decoration: none;
            font-weight: bold;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="forgot-password-container">
        <h2><i class="fas fa-lock"></i> Forgot Password</h2>
        <p>Please enter your registered email to reset your password.</p>
        
        <form method="POST">
            <div class="input-field">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
            <input type="submit" name="submit_email" class="submit-btn" value="Submit">
        </form>

        <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>

        <div class="back-to-login">
            <a href="signin.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
        </div>
    </div>

</body>
</html>
