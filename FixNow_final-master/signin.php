<?php

ob_start();

include('./config/constant.php');



// Handle customer login
if (isset($_POST['submit_customer'])) {
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $encrypted_password = md5($password);
  $query = "SELECT * FROM tbl_customer WHERE cus_email = '$email' AND cus_password = '$encrypted_password'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {

    $user = mysqli_fetch_assoc($result);

    $_SESSION['customer_id'] = $user['id'];
    $_SESSION['cus_name'] = $user['cus_name'];
    $_SESSION['cus_email'] = $user['cus_email'];

    header('Location:' . SITEURL . 'index.php?status=success&action=login_cus');
    exit();
  } else {
    header('Location:' . SITEURL . 'signin.php?status=error&action=login_cus');
  }
}

// Handle vendor login
if (isset($_GET['service_provider_id'])) {
  $service_provider_id = intval($_GET['service_provider_id']);

  // Update registration_fee_paid and payment_status
  $update_query = "UPDATE tbl_service_providers 
                   SET registration_fee_paid = 'Yes', payment_status = 'Paid'
                   WHERE id = $service_provider_id";

  if (mysqli_query($conn, $update_query)) {
      $_SESSION['payment_success'] = "Registration payment successful! Please log in.";
  } else {
      $_SESSION['payment_error'] = "Payment received, but failed to update the system.";
  }
}

if (isset($_POST['submit_vendor'])) {

  $registration_code = mysqli_real_escape_string($conn, $_POST['registration_code']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);

  $encrypted_password = md5($password);

  $query = "SELECT * FROM tbl_service_providers WHERE registration_code = '$registration_code' AND email = '$email' AND password = '$encrypted_password' AND status = 'Confirmed'";
  $result = mysqli_query($conn, $query);

  if (mysqli_num_rows($result) > 0) {
    $vendor = mysqli_fetch_assoc($result);

    $_SESSION['vendor_id'] = $vendor['id'];
    $_SESSION['vendor_name'] = $vendor['full_name'];
    $_SESSION['vendor_email'] = $vendor['email'];

    header('Location:' . SITEURL . 'service-vendor/index.php?status=success&action=login_vendor');
    exit();
  } else {

    header('Location:' . SITEURL . 'signin.php?status=error&action=login_vendor');
  }
}

// End output buffering and flush the output
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SignIn | FIXNOW</title>
  <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
  <link rel="stylesheet" type="text/css" href="./assets/css/signin.css" />
  <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
  <script src="./js/script.js"></script>

</head>

<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">

        <!-- Customer Sign In Form -->
        <form action="signin.php" class="sign-in-form" method="POST">
          <h2 class="title">Customer Sign In</h2>

          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" placeholder="Email" required />
          </div>

          <!-- <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" placeholder="Password" required />
          </div> -->

          <div class="input-field" style="position: relative;">
    <i class="fas fa-lock"></i>
    <input type="password" name="password" placeholder="Password" required 
           style="padding-right: 35px; width: 100%;" />
    <i class="fas fa-eye toggle-password" 
       style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #999; cursor: pointer;" 
       onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'"></i>
</div>


          <div class="options">
            <label class="remember-me">
        
            Forgot Password?
            </label>
            <a href="forgot_password.php" class="forgot-password">Click here</a>
            <p class="social-text">Don't have an Account? <a href="signup.php" class="forgot-password">Register here</a></p>
          </div>

          <input type="submit" name="submit_customer" class="btn solid" value="Sign In" />

          <p class="social-text">Or Sign in with social platforms</p>
          <div class="social-media">
            <a href="#" class="social-icon">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-google"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-linkedin-in"></i>
            </a>
          </div>
        </form>




        <!-- Vendor Sign In Form -->
        <form action="signin.php" class="sign-up-form" method="POST">
          <h2 class="title">Vendor Sign In</h2>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" placeholder="RegistrationCode" name="registration_code" required />
          </div>
          <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Email" name="email" required />
          </div>
          <!-- <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" placeholder="Password" name="password" required />
          </div> -->

          <div class="input-field" style="position: relative;">
    <i class="fas fa-lock"></i>
    <input type="password" placeholder="Password" name="password" required 
           style="padding-right: 35px; width: 100%;" />
    <i class="fas fa-eye toggle-password" 
       style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #999; cursor: pointer;" 
       onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'"></i>
</div>


          <div class="options">
            <label class="remember-me">
             
              Forgot Password?
            </label>
            <a href="forgot_password.php" class="forgot-password">Click here</a>
            <p class="social-text">Don't have an Account? <a href="signup.php" class="forgot-password">Register here</a></p>
          </div>

          <!-- 
            <div class="options">
    <label class="remember-me">
        <input type="checkbox" id="rememberMe"> Remember Me  
    </label>
    <a href="forgot_password.php" class="forgot-password">Forgot Password?</a>
    <p class="social-text">Don't have an Account? <a href="signup.php" class="forgot-password">Register here</a></p>
</div> -->


          <input type="submit" name="submit_vendor" value="Sign In" class="btn solid" />

          <p class="social-text">Or Sign in with social platforms</p>
          <div class="social-media">
            <a href="#" class="social-icon">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-google"></i>
            </a>
            <a href="#" class="social-icon">
              <i class="fab fa-linkedin-in"></i>
            </a>
          </div>
        </form>
      </div>
    </div>
    <div class="panels-container">

      <div class="panel left-panel">
        <div class="content">
          <h3>Are You a Vendor?</h3>
          <p>Join our platform and connect with customers looking for your services. Sign up today and start growing your business with us!</p>

          <button class="btn transparent" id="sign-up-btn">Sign in</button>
        </div>
        <img src="./assets/images/signin_signup/log.svg" class="image" alt="Vendor Sign Up">
      </div>

      <div class="panel right-panel">
        <div class="content">
          <h3>Looking for Services?</h3>
          <p>Find trusted professionals for all your needs! Sign in now to explore skilled vendors and get the job done effortlessly.</p>
          <button class="btn transparent" id="sign-in-btn">Sign In</button>
        </div>
        <img src="./assets/images/signin_signup/register.svg" class="image" alt="Customer Sign In">
      </div>


    </div>
  </div>

  <script src="./assets/js/signin.js"></script>
  <script src="./js/script.js"></script>

  <!-- <script>
document.addEventListener("DOMContentLoaded", function () {
    const rememberMe = document.getElementById("rememberMe");
    const emailField = document.getElementById("email");
    const passwordField = document.getElementById("password");

    if (localStorage.getItem("rememberMe") === "true") {
        emailField.value = localStorage.getItem("savedEmail") || "";
        passwordField.value = localStorage.getItem("savedPassword") || "";
        rememberMe.checked = true;
    }

    document.querySelector("form").addEventListener("submit", function () {
        if (rememberMe.checked) {
            localStorage.setItem("savedEmail", emailField.value);
            localStorage.setItem("savedPassword", passwordField.value);
            localStorage.setItem("rememberMe", "true");
        } else {
            localStorage.removeItem("savedEmail");
            localStorage.removeItem("savedPassword");
            localStorage.removeItem("rememberMe");
        }
    });
});
</script> -->


</body>

</html>