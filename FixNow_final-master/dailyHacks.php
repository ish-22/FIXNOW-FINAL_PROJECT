
<?php
include './partials/headerwithoutcss.php'; ?>
<link rel="stylesheet" href="./assets/css/custom.css">
<?php

// Fetch daily hacks from database
$sql = "SELECT id, title, image FROM tbl_dailyhacks ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="text_center">Daily Hacks</h1>
        
        <div class="daily-hacks-container">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['id'];
                    $title = $row['title'];
                    $image = !empty($row['image']) ? "images/hacks_images/" . basename($row['image']) : "uploads/dailyhacks/default.jpg";

            ?>
                    <div class="hack-card">
                        <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" class="hack-image">
                        <h2 class="hack-title"><?php echo $title; ?></h2>
                        <a href="dailyHackDetails.php?id=<?php echo $id; ?>" class="btn-hack">See More</a>
                    </div>
            <?php
                }
            } else {
                echo "<p class='no-hacks'>No Daily Hacks Available.</p>";
            }
            ?>
        </div>
    </div>
</div>

<?php include './partials/footer.php'; ?>