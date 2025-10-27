<?php
include './partials/headerwithoutcss.php'; ?>// Include database connection
<link rel="stylesheet" href="./assets/css/custom.css">
<?php
// Get the ID of the hack
$hack_id = isset($_GET['id']) ? $_GET['id'] : '';

// Fetch hack details from database
$sql = "SELECT * FROM tbl_dailyhacks WHERE id = '$hack_id'";
$result = mysqli_query($conn, $sql);

// Check if hack exists
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $title = $row['title'];
    $description = $row['description'];
    $image = !empty($row['image']) ? "images/hacks_images/" . basename($row['image']) : "uploads/dailyhacks/default.jpg";
} else {
    echo "<p class='error-msg'>Daily Hack not found.</p>";
    exit;
}
?>

<div class="mainHack-content">
    <div class="wrapper">
        <h1 class="textHack_center"><?php echo $title; ?></h1>

        <div class="hack-details">
            <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" class="hack-detail-image">
            <p class="hack-description"><?php echo nl2br($description); ?></p>
        </div>

       
    </div>
</div>

<?php include './partials/footer.php'; ?>  