<?php
include('./config/constant.php');


if (!isset($_SESSION['reset_email']) || !isset($_SESSION['user_type'])) {
    header("Location: forgot_password.php");
    exit();
}

$email = $_SESSION['reset_email'];
$user_type = $_SESSION['user_type'];

if (isset($_POST['reset_password'])) {
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);

    if (strlen($new_password) < 4) {
        $error = "Password must be at least 4 characters.";
    } else {
        $hashed_password = md5($new_password); // Encrypt password

        if ($user_type == "customer") {
            $query = "UPDATE tbl_customer SET cus_password = '$hashed_password' WHERE cus_email = '$email'";
        } else {
            $query = "UPDATE tbl_service_providers SET password = '$hashed_password' WHERE email = '$email'";
        }

        if (mysqli_query($conn, $query)) {
            unset($_SESSION['reset_email']);
            unset($_SESSION['user_type']);
            header("Location: signin.php?message=Password changed successfully. Please log in.");
            exit();
        } else {
            $error = "Error updating password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | FIXNOW</title>
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

        .reset-password-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .reset-password-container h2 {
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

    <div class="reset-password-container">
        <h2><i class="fas fa-key"></i> Reset Password</h2>
        <p>Enter your new password below.</p>
        
        <form method="POST">
            <div class="input-field">
                <i class="fas fa-lock"></i>
                <input type="password" name="new_password" placeholder="Enter new password" required>
            </div>
            <input type="submit" name="reset_password" class="submit-btn" value="Change Password">
        </form>

        <?php if (isset($error)) { echo "<p class='error-message'>$error</p>"; } ?>

        <div class="back-to-login">
            <a href="signin.php"><i class="fas fa-arrow-left"></i> Back to Login</a>
        </div>
    </div>

</body>
</html>
