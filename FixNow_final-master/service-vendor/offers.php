<?php 
include_once('../config/constant.php');
$current_page = 'offers.php';
include './partials/header.php'; 

// Fetch offers
$offer_query = "SELECT * FROM tbl_offer WHERE status = 'Active' OR status = 'confirmed' OR status IS NOT NULL ORDER BY id DESC";
$offers = db_fetch_all($conn, $offer_query);
?>

<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 20px 30px 20px; text-align: center; border-bottom: 1px solid #e2e8f0;">
    <div style="max-width: 800px; margin: 0 auto;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px; background: #fef3c7; color: #b45309; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-fire"></i> Promotional Campaigns
        </span>
        <h1 style="font-size: 34px; font-weight: 900; color: #0f172a; margin: 0 0 10px 0;">Active Platform Deals &amp; Offers</h1>
        <p style="color: #64748b; font-size: 16px; margin: 0 auto; max-width: 600px; line-height: 1.6;">
            Featured customer promotional discounts supported across the FixNow technician network.
        </p>
    </div>
</div>

<section style="padding: 40px 20px 80px 20px; max-width: 1200px; margin: 0 auto;">
    <?php if (!empty($offers)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 28px;">
            <?php foreach ($offers as $off): 
                $title = htmlspecialchars($off['title']);
                $desc = htmlspecialchars($off['description']);
                $orig_price = floatval($off['current_price']);
                $disc_price = floatval($off['discounted_price']);
                $rate = intval($off['discount_rate']);
                $img = !empty($off['offer_image']) && file_exists('../images/offer_images/' . $off['offer_image']) 
                    ? '../images/offer_images/' . htmlspecialchars($off['offer_image']) 
                    : '../assets/images/best offer.jpg';
            ?>
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.03); display: flex; flex-direction: column; position: relative;">
                <?php if ($rate > 0): ?>
                    <div style="position: absolute; top: 16px; right: 16px; background: #ef4444; color: #ffffff; font-weight: 800; font-size: 13px; padding: 4px 10px; border-radius: 50px; z-index: 2; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4);">
                        <?php echo $rate; ?>% OFF
                    </div>
                <?php endif; ?>

                <div style="overflow: hidden; height: 200px; background: #0f172a;">
                    <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>" style="width: 100%; height: 100%; object-fit: cover;" />
                </div>

                <div style="padding: 24px; display: flex; flex-direction: column; flex-grow: 1;">
                    <h3 style="font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
                        <?php echo $title; ?>
                    </h3>
                    <p style="font-size: 14px; color: #64748b; line-height: 1.6; margin-bottom: 20px; flex-grow: 1;">
                        <?php echo $desc; ?>
                    </p>

                    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 16px;">
                        <div>
                            <span style="font-size: 13px; color: #94a3b8; text-decoration: line-through; margin-right: 6px;">
                                LKR <?php echo number_format($orig_price, 2); ?>
                            </span>
                            <span style="font-size: 20px; font-weight: 800; color: #0284c7;">
                                LKR <?php echo number_format($disc_price, 2); ?>
                            </span>
                        </div>
                    </div>

                    <a href="vendors.php" style="display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%; padding: 10px 16px; font-size: 14px; font-weight: 700; color: #0284c7; border: 1.5px solid #0284c7; border-radius: 50px; text-decoration: none; box-sizing: border-box;">
                        Explore Qualified Specialists <i class="fa-solid fa-arrow-right fa-xs"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 70px 20px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
            <i class="fa-solid fa-tag fa-3x" style="color: #94a3b8; margin-bottom: 16px;"></i>
            <h3 style="color: #1e293b; font-size: 1.5rem; margin-bottom: 8px;">No Active Offers</h3>
            <p style="color: #64748b; margin-bottom: 20px;">Promotional campaigns will be displayed here once active.</p>
            <a href="index.php" style="background: #0284c7; color: #ffffff; padding: 10px 22px; border-radius: 50px; text-decoration: none;">Return to Portal</a>
        </div>
    <?php endif; ?>
</section>

<?php 
include './partials/footer.php'; 
?>
