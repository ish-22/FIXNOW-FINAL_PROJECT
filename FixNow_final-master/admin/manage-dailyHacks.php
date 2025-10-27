<?php
include('partials/main.php');

$sql = "SELECT * FROM tbl_dailyhacks";
$result = mysqli_query($conn, $sql);

// Check if there are any hacks in the database
if (mysqli_num_rows($result) > 0) {
    $hacks = mysqli_fetch_all($result, MYSQLI_ASSOC);
} else {
    $hacks = [];
}

?>

<div class="main-content">
    <div class="wrapper">
        <h1  class="main-welcome">Manage Daily Hacks</h1>
  
        <a href="add-dailyHacks.php" class="btn-primary">Add Hacks</a>
        <br /><br /><br />

        <div class="daily-hacks-container">
            <?php if (!empty($hacks)) : ?>
                <?php foreach ($hacks as $hack) : ?>
                    <div class="hack-card">
                        <!-- Display hack image if it exists -->
                        <img src="<?php echo isset($hack['image']) ? $hack['image'] : '../images/default.jpg'; ?>" alt="<?php echo $hack['title']; ?>" class="hack-image">
                        <h3 class="hack-title"><?php echo $hack['title']; ?></h3>
                        <pre class="hack-example">
<?php echo nl2br($hack['description']); ?>
                        </pre>

                        <div class="hack-actions">
                            <a href="update-dailyHacks.php?id=<?php echo $hack['id']; ?>" class="btn-secondary">Update Hack</a>
                            <a href="delete-dailyHacks.php?id=<?php echo $hack['id']; ?>" class="btn-danger">Delete Hack</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>No daily hacks available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include('partials/footer.php'); ?>