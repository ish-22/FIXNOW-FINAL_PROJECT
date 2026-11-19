<?php 
include './partials/header.php'; 

// Fetch offers
$offer_query = "SELECT * FROM tbl_offer WHERE status = 'Active' OR status = 'confirmed' OR status IS NOT NULL ORDER BY id DESC";
$offers = db_fetch_all($conn, $offer_query);
?>

<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 30px 0; text-align: center;">
    <div class="fn-container">
        <span class="fn-hero-badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">
            <i class="fa-solid fa-fire"></i> Limited-Time Savings
        </span>
        <h1 class="fn-section-title">Exclusive Service Deals & Offers</h1>
        <p class="fn-section-subtitle">
            Save on home electrical checks, AC maintenance, plumbing upgrades, and deep cleaning packages.
        </p>
    </div>
</div>

<section style="padding: 10px 0 80px 0;">
    <div class="fn-container">
        <?php if (!empty($offers)): ?>
            <div class="fn-grid-3">
                <?php foreach ($offers as $off): 
                    $title = htmlspecialchars($off['title']);
                    $desc = htmlspecialchars($off['description']);
                    $orig_price = floatval($off['current_price']);
                    $disc_price = floatval($off['discounted_price']);
                    $rate = intval($off['discount_rate']);
                    $img = !empty($off['offer_image']) && file_exists('./images/offer_images/' . $off['offer_image']) 
                        ? './images/offer_images/' . htmlspecialchars($off['offer_image']) 
                        : './assets/images/best offer.jpg';
                ?>
                <div class="fn-card" style="display: flex; flex-direction: column; position: relative;">
                    <?php if ($rate > 0): ?>
                        <div style="position: absolute; top: 16px; right: 16px; background: #ef4444; color: #ffffff; font-weight: 800; font-size: 13px; padding: 4px 10px; border-radius: 50px; z-index: 2; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4);">
                            <?php echo $rate; ?>% OFF
                        </div>
                    <?php endif; ?>

                    <div style="overflow: hidden;">
                        <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>" style="width: 100%; height: 200px; object-fit: cover; transition: transform 0.3s;" />
                    </div>

                    <div style="padding: 24px; display: flex; flex-direction: column; flex-grow: 1;">
                        <h3 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 8px;">
                            <?php echo $title; ?>
                        </h3>
                        <p style="font-size: 14.5px; color: #64748b; line-height: 1.6; margin-bottom: 20px; flex-grow: 1;">
                            <?php echo $desc; ?>
                        </p>

                        <div style="display: flex; align-items: baseline; gap: 10px; margin-bottom: 16px; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
                            <?php if ($disc_price > 0): ?>
                                <span style="font-size: 22px; font-weight: 800; color: #0284c7;">
                                    Rs. <?php echo number_format($disc_price, 2); ?>
                                </span>
                                <?php if ($orig_price > $disc_price): ?>
                                    <span style="font-size: 15px; color: #94a3b8; text-decoration: line-through;">
                                        Rs. <?php echo number_format($orig_price, 2); ?>
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="font-size: 22px; font-weight: 800; color: #0284c7;">
                                    Rs. <?php echo number_format($orig_price, 2); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <a href="vendors.php" class="fn-btn fn-btn-primary" style="width: 100%; border-radius: 50px;">
                            <i class="fa-solid fa-tag"></i> Claim This Offer
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 70px 20px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <i class="fa-solid fa-tags fa-3x" style="color: #94a3b8; margin-bottom: 16px;"></i>
                <h3 style="color: #1e293b; font-size: 1.5rem; margin-bottom: 8px;">No Current Promotions</h3>
                <p style="color: #64748b; margin-bottom: 20px;">Check back soon for upcoming holiday promotions and seasonal package discounts.</p>
                <a href="services.php" class="fn-btn fn-btn-primary">Browse All Services</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include './partials/footer.php'; ?>
