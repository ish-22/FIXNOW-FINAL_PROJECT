<?php
include './partials/header.php'; 

// Fetch daily hacks securely
$sql = "SELECT id, title, image, created_at FROM tbl_dailyhacks ORDER BY id DESC";
$hacks = db_fetch_all($conn, $sql);
?>

<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 30px 0; text-align: center;">
    <div class="fn-container">
        <span class="fn-hero-badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">
            <i class="fa-solid fa-lightbulb"></i> Home Care & Maintenance
        </span>
        <h1 class="fn-section-title">Daily DIY & Maintenance Hacks</h1>
        <p class="fn-section-subtitle">
            Smart, practical tips and DIY guides from certified technicians to keep your appliances and home in top shape.
        </p>
    </div>
</div>

<section style="padding: 10px 0 80px 0;">
    <div class="fn-container">
        <?php if (!empty($hacks)): ?>
            <div class="fn-grid-3">
                <?php foreach ($hacks as $hack): 
                    $id = $hack['id'];
                    $title = htmlspecialchars($hack['title']);
                    $img = !empty($hack['image']) && file_exists('./images/hacks_images/' . basename($hack['image'])) 
                        ? "./images/hacks_images/" . htmlspecialchars(basename($hack['image'])) 
                        : "./assets/images/life hack.jpg";
                ?>
                <div class="fn-card" style="display: flex; flex-direction: column;">
                    <div style="overflow: hidden;">
                        <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>" style="width: 100%; height: 210px; object-fit: cover; transition: transform 0.3s;" />
                    </div>
                    <div style="padding: 22px; display: flex; flex-direction: column; flex-grow: 1;">
                        <span style="font-size: 12px; font-weight: 700; color: #0284c7; text-transform: uppercase; margin-bottom: 6px;">
                            <i class="fa-solid fa-wrench fa-xs"></i> Quick Tip
                        </span>
                        <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 12px; line-height: 1.3;">
                            <?php echo $title; ?>
                        </h3>
                        <div style="margin-top: auto; padding-top: 14px; border-top: 1px dashed #e2e8f0;">
                            <a href="dailyHackDetails.php?id=<?php echo $id; ?>" class="fn-btn fn-btn-outline" style="width: 100%; padding: 8px 16px; font-size: 14px; border-radius: 50px;">
                                Read Step-by-Step Guide <i class="fa-solid fa-arrow-right fa-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 70px 20px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <i class="fa-regular fa-lightbulb fa-3x" style="color: #94a3b8; margin-bottom: 16px;"></i>
                <h3 style="color: #1e293b; font-size: 1.5rem; margin-bottom: 8px;">No Daily Hacks Available</h3>
                <p style="color: #64748b; margin-bottom: 20px;">Check back soon for new maintenance hacks and troubleshooting articles.</p>
                <a href="index.php" class="fn-btn fn-btn-primary">Return to Home</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include './partials/footer.php'; ?>
