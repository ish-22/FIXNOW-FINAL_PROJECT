<?php include('../config/constant.php');
include('login-check.php')
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNow Navbar</title>
    <link rel="stylesheet" href="admin_css/style.css">
    <script src="../js/script.js"></script>
</head>

<body>
    <div class="sidebar" id="left-sidebar"></div>



    <div class="sidebar" id="sidebar">
        <div class="close-btn" id="close-btn">&times;</div>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">User</a></li>
            <li><a href="#">Service</a></li>
            <li><a href="#">Offers</a></li>
            <li><a href="#">Vendor</a></li>
            <li><a href="#">Invoice</a></li>
            <li><a href="#">Inquries</a></li>
            <li><a href="#">Daily Hacks</a></li>
            <li><a href="admin-logout.php">Logout</a></li>

        </ul>
    </div>
    <nav class="navbar">
        <div class="brand">
            <div class="logo-container">
                <img src="../images/logo.png" alt="Logo">
            </div>
        </div>
        <ul class="menu">
            <li><a href="admin-index.php">Home</a></li>
            <li><a href="manage-user.php">User</a></li>
            <li><a href="manage-services.php">Service</a></li>
            <li><a href="manage-offers.php">Offers</a></li>
            <li><a href="manage-vendor.php">Vendor</a></li>
            <li><a href="bookings.php">Bookings</a></li>
            <li><a href="manage-invoice.php">Invoice</a></li>
            <li><a href="manage-question.php">Inquries</a></li>
            <li><a href="manage-dailyHacks.php">Daily Hacks</a></li>
        </ul>

        <a class="logout" id="logout-btn" href="admin-logout.php">Logout</a>
        <div class="menu-toggle" id="menu-toggle">☰</div>
    </nav>

    <!-- 
    <div class="sidebar" id="sidebar">
    <div class="close-btn" id="close-btn">&times;</div>
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">User</a></li>
        <li><a href="#">Service</a></li>
        <li><a href="#">Offers</a></li>
        <li><a href="#">Vendor</a></li>
        <li><a href="#">Bookings</a></li>
        <li><a href="#">Invoice</a></li>
        <li><a href="#">FAQ</a></li>
        <li><a href="#">Daily Hacks</a></li>
        <li><button class="logout" >Log Out</button></li> 
    </ul>
</div> -->