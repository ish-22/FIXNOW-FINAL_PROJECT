
<?php
// Start the session to track user login status
include('config/constant.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FIXNOW - Find Your Opportunity</title>
  <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700;900&display=swap" rel="stylesheet">
  <link rel="preload" as="image" href="./assets/images/hero-banner.png">
  <script src="./js/script.js"></script>

</head>

<body id="top">

  <header class="header" data-header>
    <div class="container">

    <h1 style="display: flex; align-items: center; justify-content: center;">
      <a href="./assets/images/fixnow-logo.png" class="logo" style="display: flex; align-items: center; font-size: 2.5rem; font-weight: 700; line-height: 1; color: #333;">
        <img src="./assets/images/fixnow-logo.png" alt="logo" style="max-width: 150px; height: auto; margin-right: 10px;">
        <span></span>
      </a>
    </h1>

      <nav class="navbar container" data-navbar>
        <ul class="navbar-list">

          <li class="navbar-item">
            <a href="index.php" class="navbar-link" data-nav-link>Home</a>
          </li>

          <li class="navbar-item">
            <a href="services.php" class="navbar-link" data-nav-link>Services</a>
          </li>

          <li class="navbar-item">
            <a href="offers.php" class="navbar-link" data-nav-link>Offers</a>
          </li>


          <li class="navbar-item">
            <a href="vendors.php" class="navbar-link" data-nav-link>Service Providers</a>
          </li>

          <li class="navbar-item">
            <a href="faq.php" class="navbar-link" data-nav-link>FAQs</a>
          </li>

          <li class="navbar-item">
            <a href="dailyHacks.php" class="navbar-link" data-nav-link>Daily Hacks</a>
          </li>


          <li class="navbar-item">
            <?php
              if (isset($_SESSION['customer_id'])) {
                  // If the customer is logged in, show the profile link
                  echo '<a href="profile.php" class="navbar-link">Profile</a>';
              }
            ?>
          </li>

          <li class="navbar-item">
            <?php



              if (isset($_SESSION['customer_id'])) {
                  // If the customer is logged in, show the logout button
                  echo '<a href="logout.php" class="btn btn-secondary" style="padding: 15px 25px; background-color: #007BFF; color: #fff; text-decoration: none; font-size: 2.5rem; border-radius: 50px;">Logout</a>';
              } else {
                  // If not logged in, show the Join Us button
                  echo '<a href="signup.php" class="btn btn-secondary" id="find-employee-btn" style="padding: 15px 25px; background-color: #007BFF; color: #fff; text-decoration: none; font-size: 2.5rem; border-radius: 50px;">Join Us</a>';
              }
            ?>
          </li>

        </ul>
      
      </nav>

      <button class="nav-toggle-btn" aria-label="Toggle menu" data-nav-toggle-btn>
        <ion-icon name="menu-outline" class="menu-icon"></ion-icon>
        <ion-icon name="close-outline" class="close-icon"></ion-icon>
      </button>

    </div>
  </header>