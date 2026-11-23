<?php
include_once('../config/constant.php');
$current_page = 'dailyHacks.php';
include './partials/header.php'; 

// Fetch daily hacks securely
$sql = "SELECT id, title, image, created_at FROM tbl_dailyhacks ORDER BY id DESC";
$hacks = db_fetch_all($conn, $sql);
?>

<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 20px 30px 20px; text-align: center; border-bottom: 1px solid #e2e8f0;">
    <div style="max-width: 800px; margin: 0 auto;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px; background: #fef3c7; color: #b45309; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-lightbulb"></i> Trade Field Guides &amp; Tips
        </span>
        <h1 style="font-size: 34px; font-weight: 900; color: #0f172a; margin: 0 0 10px 0;">Specialist Technical &amp; DIY Hacks</h1>
        <p style="color: #64748b; font-size: 16px; margin: 0 auto; max-width: 600px; line-height: 1.6;">
            Standardized trade best practices, maintenance checklists, and customer educational guides.
        </p>
    </div>
</div>

<section style="padding: 40px 20px 80px 20px; max-width: 1200px; margin: 0 auto;">
    <?php if (!empty($hacks)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 28px;">
            <?php foreach ($hacks as $hack): 
                $id = $hack['id'];
                $title = htmlspecialchars($hack['title']);
                $img_name = basename($hack['image'] ?? '');
                $img = (!empty($hack['image']) && file_exists('../images/hacks_images/' . $img_name)) 
                    ? "../images/hacks_images/" . htmlspecialchars($img_name) 
                    : "../assets/images/life hack.jpg";
            ?>
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.03); display: flex; flex-direction: column;">
                <div style="overflow: hidden; height: 210px; background: #0f172a;">
                    <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>" style="width: 100%; height: 100%; object-fit: cover;" />
                </div>
                <div style="padding: 22px; display: flex; flex-direction: column; flex-grow: 1;">
                    <span style="font-size: 12px; font-weight: 700; color: #0284c7; text-transform: uppercase; margin-bottom: 6px;">
                        <i class="fa-solid fa-wrench fa-xs"></i> Technical Advisory
                    </span>
                    <h3 style="font-size: 1.25rem; font-weight: 800; color: #0f172a; margin-bottom: 12px; line-height: 1.35;">
                        <?php echo $title; ?>
                    </h3>
                    <div style="margin-top: auto; padding-top: 14px; border-top: 1px dashed #e2e8f0;">
                        <a href="dailyHackDetails.php?id=<?php echo $id; ?>" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px 16px; font-size: 14px; font-weight: 700; color: #0284c7; border: 1.5px solid #0284c7; border-radius: 50px; text-decoration: none; box-sizing: border-box;">
                            Read Technical Guide <i class="fa-solid fa-arrow-right fa-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 70px 20px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
            <i class="fa-regular fa-lightbulb fa-3x" style="color: #94a3b8; margin-bottom: 16px;"></i>
            <h3 style="color: #1e293b; font-size: 1.5rem; margin-bottom: 8px;">No Guides Available</h3>
            <p style="color: #64748b; margin-bottom: 20px;">Check back soon for new trade procedures.</p>
            <a href="index.php" style="background: #0284c7; color: #ffffff; padding: 10px 22px; border-radius: 50px; text-decoration: none;">Return to Portal</a>
        </div>
    <?php endif; ?>
</section>

<?php 
include './partials/footer.php'; 
?>
