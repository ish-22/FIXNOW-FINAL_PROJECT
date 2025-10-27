<?php
include('../config/constant.php');

// Check if the form is submitted
if (isset($_POST['submit'])) {

    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password']; // Plain password for comparison

    // Prepare SQL query to check user credentials from the tbl_admin table
    $sql = "SELECT * FROM tbl_admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username); // Bind parameters to prevent SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify the password using password_verify()
        if (password_verify($password, $row['password'])) {
           
            $_SESSION['user'] = $username;
            $_SESSION['user_id'] = $row['id'];
            
            header('Location: ' . SITEURL . 'admin/admin-index.php?status=success&action=login');
        } else {
         
            header('Location: ' . SITEURL . 'admin/admin-login.php?status=error&action=login');
        }
    } else {
       
        header('Location: ' . SITEURL . 'admin/admin-login.php?status=error&action=login');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin_css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../js/script.js"></script>
</head>
<style>
    /* Common alert styles */
div[id*="status=success"], div[id*="status=error"] {
    font-size: 20px; /* Larger font size */
    padding: 15px;
    margin: 10px auto;
    border-radius: 5px;
    text-align: center;
    width: 80%;
    max-width: 600px;
}

/* Success message */
div[id*="status=success"] {
    color: green;
    background-color: #d4edda; /* Light green */
    border: 1px solid #c3e6cb;
}

/* Error message */
div[id*="status=error"] {
    color: red;
    background-color: #f8d7da; /* Light red */
    border: 1px solid #f5c6cb;
}

</style>
<body>

    <!-- Background Video -->
    <div class="background-video"> 
        <video autoplay muted loop>
            <source src="../images/handshake.mp4" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <!-- Main Container for Login -->
    <div class="main-container">
        <div class="login-box">
            <!-- Left Side: Login Form -->
            <div class="login-left">
                <h1>Admin Login</h1>
                <form action="" method="POST">
                    <div class="input-field">
                        <input type="text" name="username" placeholder="Username" required />
                    </div>
                    <div class="input-field">
                        <input type="password" name="password" placeholder="Password" required />
                    </div>
                    <button type="submit" name="submit" class="btn-primary">Sign In</button>
                </form>
                
                <!-- Social Media Icons -->
                <div class="social-login">
                    <button><i class="fab fa-facebook-f"></i></button>
                    <button><i class="fab fa-google"></i></button>
                    <button><i class="fab fa-linkedin-in"></i></button>
                </div>
            </div>

            <!-- Right Side: Company Details -->
            <div class="login-right">
                <h2>Welcome to FixNow Family</h2>
                <p>We provide the best services to help you grow your business and achieve success. Join us to experience top-notch solutions that meet all your needs.</p>
            </div>
        </div>
    </div>

</body>
</html>
