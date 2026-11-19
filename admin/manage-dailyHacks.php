<?php
include('partials/main.php');

$sql = "SELECT * FROM tbl_dailyhacks ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$hacks = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">DIY Maintenance Hacks &amp; Articles</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Publish home maintenance advice, safety tips, and DIY tutorials for customers.</p>
        </div>
        <a href="add-dailyHacks.php" class="btn-primary">
            <i class="fa-solid fa-plus"></i> Add New Hack
        </a>
    </div>

    <div class="daily-hacks-container">
        <?php if (!empty($hacks)) : ?>
            <?php foreach ($hacks as $hack) : 
                $img = !empty($hack['image']) ? $hack['image'] : '../images/default.jpg';
            ?>
                <div class="hack-card">
                    <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($hack['title']); ?>" class="hack-image" onerror="this.src='../assets/images/diy.jpg';">
                    <h3 class="hack-title"><?php echo htmlspecialchars($hack['title']); ?></h3>
                    <p style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin-bottom: 16px;">
                        <?php echo nl2br(htmlspecialchars(substr($hack['description'], 0, 150))) . (strlen($hack['description']) > 150 ? '...' : ''); ?>
                    </p>

                    <div class="hack-actions">
                        <a href="update-dailyHacks.php?id=<?php echo $hack['id']; ?>" class="btn-secondary" style="flex: 1; text-align: center;">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <a href="delete-dailyHacks.php?id=<?php echo $hack['id']; ?>" class="btn-danger" style="flex: 1; text-align: center;" onclick="return confirm('Delete this hack article?');">
                            <i class="fa-solid fa-trash-can"></i> Delete
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p style="padding: 30px; text-align: center; color: #94a3b8;">No daily hacks published yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php 
include('partials/footer.php'); 
?>
