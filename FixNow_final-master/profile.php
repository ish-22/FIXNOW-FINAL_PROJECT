<?php include './partials/headerwithoutcss.php'; ?>
<link rel="stylesheet" href="./assets/css/custom.css">
<link rel="stylesheet" href="./assets/css/customerProfile.css">

<?php 

// Ensure the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php"); 
    exit();
}

$customer_id = $_SESSION['customer_id']; 

// Fetch customer details
$query = "SELECT c.*, d.district_name, t.town_name 
          FROM tbl_customer c
          LEFT JOIN tbl_districts d ON c.district_id = d.id
          LEFT JOIN tbl_towns t ON c.town_id = t.id
          WHERE c.id = '$customer_id'";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);

if (!$customer) {
    die("Error fetching customer details: " . mysqli_error($conn));
}
?>

<section class="profile-container">
    <div class="profile-card">
        <div class="profile-header">
            <img src="./images/cus_images/<?php echo isset($customer['cus_image']) && file_exists('./images/cus_images/' . $customer['cus_image']) ? $customer['cus_image'] : 'default.jpg'; ?>" 
                 alt="Profile Picture" class="profile-img">
            <h2><?php echo htmlspecialchars($customer['cus_name']); ?></h2>
            <p class="email"><?php echo htmlspecialchars($customer['cus_email']); ?></p>
        </div>

        <div class="profile-details">
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($customer['cus_phone']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($customer['cus_address']); ?></p>
            <p><strong>District:</strong> <?php echo htmlspecialchars($customer['district_name']); ?></p>
            <p><strong>Town:</strong> <?php echo htmlspecialchars($customer['town_name']); ?></p>
            <p><strong>Joined:</strong> <?php echo date('F j, Y', strtotime($customer['registration_date'])); ?></p>
        </div>

        <div class="profile-buttons">
            <a href="profileupdate.php" class="btnbtnprofile btn-update">Update Profile</a>
            <a href="bookingHistory.php" class="btnbtnprofile btn-history">Booking History</a>
        </div>
    </div>
</section>

<?php include './partials/footer.php'; ?>