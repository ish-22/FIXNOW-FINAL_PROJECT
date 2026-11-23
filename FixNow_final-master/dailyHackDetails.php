<?php
include_once('./config/constant.php');

// Securely validate and retrieve hack ID
$hack_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$hack = null;
if ($hack_id > 0) {
    $hack = db_fetch_one($conn, "SELECT * FROM tbl_dailyhacks WHERE id = ?", [$hack_id], "i");
}

// Fetch related hacks for the sidebar (excluding current)
$related_hacks = [];
if ($hack) {
    $related_hacks = db_fetch_all($conn, "SELECT id, title, image, created_at FROM tbl_dailyhacks WHERE id != ? ORDER BY id DESC LIMIT 3", [$hack_id], "i");
}

// Set active page for header
$current_page = 'dailyHacks.php';
include('./partials/header.php');
?>

<!-- Breadcrumb Navigation Bar -->
<div style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; padding: 14px 0;">
    <div class="fn-container" style="max-width: 1200px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
        <nav style="display: flex; align-items: center; gap: 8px; font-size: 13.5px; color: #64748b;">
            <a href="index.php" style="color: #64748b; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#0284c7'" onmouseout="this.style.color='#64748b'">
                <i class="fa-solid fa-house"></i> Home
            </a>
            <i class="fa-solid fa-chevron-right fa-xs" style="color: #cbd5e1;"></i>
            <a href="dailyHacks.php" style="color: #64748b; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#0284c7'" onmouseout="this.style.color='#64748b'">
                Daily DIY Hacks
            </a>
            <i class="fa-solid fa-chevron-right fa-xs" style="color: #cbd5e1;"></i>
            <span style="color: #0f172a; font-weight: 600; max-width: 380px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <?php echo $hack ? htmlspecialchars($hack['title']) : 'Hack Details'; ?>
            </span>
        </nav>

        <a href="dailyHacks.php" style="display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 700; color: #0284c7; text-decoration: none;">
            <i class="fa-solid fa-arrow-left"></i> All DIY Hacks
        </a>
    </div>
</div>

<?php if (!$hack): ?>
    <!-- 404 / Not Found State -->
    <div class="fn-container" style="max-width: 700px; padding: 80px 20px; text-align: center;">
        <div style="width: 72px; height: 72px; border-radius: 50%; background: #fee2e2; color: #ef4444; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 28px;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <h2 style="font-size: 26px; font-weight: 800; color: #0f172a; margin-bottom: 10px;">Maintenance Guide Not Found</h2>
        <p style="color: #64748b; font-size: 15px; margin-bottom: 25px; line-height: 1.6;">
            The DIY maintenance hack or article you are looking for may have been updated, relocated, or temporarily unlisted.
        </p>
        <a href="dailyHacks.php" class="fn-btn fn-btn-primary" style="padding: 12px 28px; font-size: 14.5px; border-radius: 50px;">
            <i class="fa-solid fa-book-open" style="margin-right: 6px;"></i> Browse All Daily Hacks
        </a>
    </div>
<?php else: 
    // Image resolution
    $img_src = "./assets/images/life hack.jpg";
    if (!empty($hack['image'])) {
        $img_name = basename($hack['image']);
        if (file_exists("./images/hacks_images/" . $img_name)) {
            $img_src = "./images/hacks_images/" . $img_name;
        } elseif (file_exists("./assets/images/" . $img_name)) {
            $img_src = "./assets/images/" . $img_name;
        }
    }
    $created_date = !empty($hack['created_at']) ? date('F j, Y', strtotime($hack['created_at'])) : date('F j, Y');
?>

<!-- Main Article View -->
<div style="padding: 40px 0 80px 0; background: #ffffff;">
    <div class="fn-container" style="max-width: 1200px; display: grid; grid-template-columns: 1fr 380px; gap: 40px; align-items: start;">
        
        <!-- Left: Primary Article Column -->
        <article>
            <!-- Meta Badges -->
            <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 16px;">
                <span style="display: inline-flex; align-items: center; gap: 6px; background: #e0f2fe; color: #0284c7; padding: 5px 12px; border-radius: 50px; font-size: 12.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i class="fa-solid fa-wrench"></i> DIY Maintenance
                </span>
                <span style="display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #059669; padding: 5px 12px; border-radius: 50px; font-size: 12.5px; font-weight: 700;">
                    <i class="fa-solid fa-circle-check"></i> FixNow Verified Pro Tip
                </span>
                <span style="color: #94a3b8; font-size: 13.5px; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fa-regular fa-clock"></i> 3 Min Read
                </span>
                <span style="color: #94a3b8; font-size: 13.5px; display: inline-flex; align-items: center; gap: 5px;">
                    <i class="fa-regular fa-calendar"></i> <?php echo $created_date; ?>
                </span>
            </div>

            <!-- Title -->
            <h1 style="font-size: 32px; font-weight: 900; color: #0f172a; line-height: 1.25; margin: 0 0 24px 0;">
                <?php echo htmlspecialchars($hack['title']); ?>
            </h1>

            <!-- Featured Image Frame -->
            <div style="position: relative; border-radius: 18px; overflow: hidden; background: #0f172a; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); border: 1px solid #e2e8f0;">
                <img src="<?php echo htmlspecialchars($img_src); ?>" 
                     alt="<?php echo htmlspecialchars($hack['title']); ?>" 
                     style="width: 100%; max-height: 440px; object-fit: cover; display: block;" />
                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(180deg, transparent 0%, rgba(15,23,42,0.85) 100%); padding: 20px 24px; color: #ffffff; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <span style="font-size: 13px; font-weight: 600; color: #e2e8f0; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-camera"></i> FixNow Home Care Technical Series
                    </span>
                    <span style="background: rgba(255,255,255,0.2); backdrop-filter: blur(8px); padding: 4px 10px; border-radius: 6px; font-size: 11.5px; font-weight: 700; letter-spacing: 0.5px;">
                        EXPERT CERTIFIED
                    </span>
                </div>
            </div>

            <!-- Executive Summary Callout -->
            <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-left: 5px solid #0284c7; border-radius: 12px; padding: 20px 24px; margin-bottom: 32px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #0369a1; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-lightbulb"></i> Why Routine Maintenance Matters
                </h3>
                <p style="font-size: 15px; color: #334155; line-height: 1.6; margin: 0;">
                    Preventative upkeep prevents minor mechanical friction or residue buildup from escalating into costly compressor failure, motor burnout, or water leak emergencies.
                </p>
            </div>

            <!-- Core Hack Explanation -->
            <div style="margin-bottom: 36px;">
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 14px;">
                    Overview &amp; Action Plan
                </h2>
                <div style="font-size: 16px; color: #334155; line-height: 1.8;">
                    <p style="margin-bottom: 16px; white-space: pre-line;">
                        <?php echo htmlspecialchars($hack['description']); ?>
                    </p>
                </div>
            </div>

            <!-- Required Tools & Materials Checklist -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px 28px; margin-bottom: 36px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-toolbox" style="color: #0284c7;"></i> Recommended Tools &amp; Materials
                </h3>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <span style="display: inline-flex; align-items: center; gap: 7px; background: #ffffff; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 600; color: #334155;">
                        <i class="fa-solid fa-faucet-drip" style="color: #0284c7;"></i> Lukewarm Running Water
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 7px; background: #ffffff; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 600; color: #334155;">
                        <i class="fa-solid fa-soap" style="color: #0284c7;"></i> Mild pH-Neutral Soap
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 7px; background: #ffffff; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 600; color: #334155;">
                        <i class="fa-solid fa-hand" style="color: #0284c7;"></i> Soft Microfiber Towel
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 7px; background: #ffffff; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 600; color: #334155;">
                        <i class="fa-solid fa-screwdriver" style="color: #0284c7;"></i> Standard Screwdriver Set
                    </span>
                    <span style="display: inline-flex; align-items: center; gap: 7px; background: #ffffff; border: 1px solid #cbd5e1; padding: 8px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 600; color: #334155;">
                        <i class="fa-solid fa-shield-halved" style="color: #0284c7;"></i> Protective Work Gloves
                    </span>
                </div>
            </div>

            <!-- Step-by-Step Procedure Cards -->
            <div style="margin-bottom: 36px;">
                <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 18px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-list-ol" style="color: #0284c7;"></i> Step-by-Step Execution Guide
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <!-- Step 1 -->
                    <div style="display: flex; gap: 16px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#0284c7'" onmouseout="this.style.borderColor='#e2e8f0'">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #e0f2fe; color: #0284c7; font-size: 16px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            1
                        </div>
                        <div>
                            <h4 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Isolate Power &amp; Ensure Safety</h4>
                            <p style="font-size: 14.5px; color: #475569; margin: 0; line-height: 1.6;">
                                Turn off the main circuit breaker or unplug the appliance from the wall receptacle before opening front panels or working near moving blowers.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div style="display: flex; gap: 16px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#0284c7'" onmouseout="this.style.borderColor='#e2e8f0'">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #e0f2fe; color: #0284c7; font-size: 16px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            2
                        </div>
                        <div>
                            <h4 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Disassemble &amp; Inspect Components</h4>
                            <p style="font-size: 14.5px; color: #475569; margin: 0; line-height: 1.6;">
                                Gently lift the access tabs to slide out filter meshes or intake covers. Check for small tears, brittle plastic clips, or mildew accumulations along the drainage tray.
                            </p>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div style="display: flex; gap: 16px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#0284c7'" onmouseout="this.style.borderColor='#e2e8f0'">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #e0f2fe; color: #0284c7; font-size: 16px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            3
                        </div>
                        <div>
                            <h4 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Gentle Cleansing with Lukewarm Water</h4>
                            <p style="font-size: 14.5px; color: #475569; margin: 0; line-height: 1.6;">
                                Direct water pressure from the clean side through to the dirty side to dislodge dust particles naturally. Avoid abrasive scrubbers or acidic cleaners that strip protective coatings.
                            </p>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div style="display: flex; gap: 16px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#0284c7'" onmouseout="this.style.borderColor='#e2e8f0'">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: #e0f2fe; color: #0284c7; font-size: 16px; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                            4
                        </div>
                        <div>
                            <h4 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Complete Air-Drying &amp; Reassembly</h4>
                            <p style="font-size: 14.5px; color: #475569; margin: 0; line-height: 1.6;">
                                Rest components in a shaded, well-ventilated space until bone dry. Reinsert firmly until the retention clips click into place, then restore electrical supply and test airflow.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Safety Notice Alert -->
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 14px; padding: 20px 24px; display: flex; gap: 16px; align-items: start; margin-bottom: 36px;">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 18px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h4 style="font-size: 15.5px; font-weight: 800; color: #92400e; margin-bottom: 4px;">Important Safety Notice</h4>
                    <p style="font-size: 14px; color: #78350f; margin: 0; line-height: 1.6;">
                        Never attempt to service enclosed capacitor terminals, copper coil tubing, or refrigerant connections without HVAC certification. If you observe burning smells or ungrounded vibrations, power down immediately.
                    </p>
                </div>
            </div>

            <!-- Was This Helpful? & Social Share Bar -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 22px 26px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px; margin-bottom: 40px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 14.5px; font-weight: 700; color: #0f172a;">Was this guide helpful?</span>
                    <button type="button" onclick="recordHelpful(true, this)" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 50px; padding: 7px 16px; font-size: 13.5px; font-weight: 600; color: #334155; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                        <i class="fa-solid fa-thumbs-up" style="color: #0284c7;"></i> Yes
                    </button>
                    <button type="button" onclick="recordHelpful(false, this)" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 50px; padding: 7px 16px; font-size: 13.5px; font-weight: 600; color: #334155; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
                        <i class="fa-solid fa-thumbs-down" style="color: #94a3b8;"></i> Not quite
                    </button>
                </div>

                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="font-size: 13.5px; font-weight: 600; color: #64748b;">Share Guide:</span>
                    <button type="button" onclick="copyArticleLink()" title="Copy Link" style="width: 36px; height: 36px; border-radius: 50%; background: #ffffff; border: 1px solid #cbd5e1; color: #475569; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                        <i class="fa-solid fa-link fa-sm"></i>
                    </button>
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($hack['title'] . ' - FixNow DIY: ' . SITEURL . 'dailyHackDetails.php?id=' . $hack['id']); ?>" target="_blank" title="Share via WhatsApp" style="width: 36px; height: 36px; border-radius: 50%; background: #ffffff; border: 1px solid #cbd5e1; color: #16a34a; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                        <i class="fa-brands fa-whatsapp fa-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Book a Specialist Inline Callout -->
            <div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); border-radius: 18px; padding: 32px; color: #ffffff; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; box-shadow: 0 10px 25px rgba(2,132,199,0.25);">
                <div style="max-width: 480px;">
                    <span style="background: rgba(255,255,255,0.2); padding: 4px 10px; border-radius: 50px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; margin-bottom: 8px;">
                        Need Professional Assistance?
                    </span>
                    <h3 style="font-size: 22px; font-weight: 800; margin: 0 0 6px 0;">Don't Have the Tools or Time?</h3>
                    <p style="font-size: 14.5px; color: #e0f2fe; margin: 0; line-height: 1.5;">
                        Connect with top-rated, certified technicians across Sri Lanka. Instant booking, transparent pricing, and PayHere Escrow protection.
                    </p>
                </div>
                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="vendors.php" style="background: #ffffff; color: #0284c7; padding: 12px 24px; border-radius: 50px; font-size: 14px; font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <i class="fa-solid fa-user-gear"></i> Find Specialists
                    </a>
                    <a href="services.php" style="background: rgba(255,255,255,0.15); color: #ffffff; border: 1px solid rgba(255,255,255,0.3); padding: 12px 20px; border-radius: 50px; font-size: 14px; font-weight: 700; text-decoration: none;">
                        All Services
                    </a>
                </div>
            </div>

        </article>

        <!-- Right: Modern Sidebar -->
        <aside style="display: flex; flex-direction: column; gap: 26px;">
            
            <!-- Card 1: Fast Booking Widget -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 26px; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 14px;">
                    <div style="width: 36px; height: 36px; border-radius: 10px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0;">FixNow Pro Assurance</h3>
                        <span style="font-size: 12px; color: #64748b;">Guaranteed Peace of Mind</span>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 12px; margin-bottom: 20px; font-size: 13.5px; color: #475569;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-check" style="color: #10b981;"></i>
                        <span>100% Background-checked specialists</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-check" style="color: #10b981;"></i>
                        <span>PayHere Escrow payment guarantee</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-check" style="color: #10b981;"></i>
                        <span>Direct in-app messaging &amp; scheduling</span>
                    </div>
                </div>

                <a href="vendors.php" class="fn-btn fn-btn-primary" style="width: 100%; text-align: center; justify-content: center; padding: 11px 18px; font-size: 14px; border-radius: 50px;">
                    <i class="fa-solid fa-magnifying-glass" style="margin-right: 6px;"></i> Browse Specialists
                </a>
            </div>

            <!-- Card 2: Related Maintenance Hacks -->
            <?php if (!empty($related_hacks)): ?>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.03);">
                    <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-bolt" style="color: #f59e0b;"></i> Related DIY Hacks
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <?php foreach ($related_hacks as $rh): 
                            $rh_img = "./assets/images/life hack.jpg";
                            if (!empty($rh['image'])) {
                                $rh_name = basename($rh['image']);
                                if (file_exists("./images/hacks_images/" . $rh_name)) {
                                    $rh_img = "./images/hacks_images/" . $rh_name;
                                } elseif (file_exists("./assets/images/" . $rh_name)) {
                                    $rh_img = "./assets/images/" . $rh_name;
                                }
                            }
                        ?>
                            <a href="dailyHackDetails.php?id=<?php echo $rh['id']; ?>" style="display: flex; gap: 12px; text-decoration: none; color: inherit; padding: 8px; border-radius: 10px; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <img src="<?php echo htmlspecialchars($rh_img); ?>" alt="<?php echo htmlspecialchars($rh['title']); ?>" style="width: 68px; height: 68px; border-radius: 10px; object-fit: cover; flex-shrink: 0;" />
                                <div>
                                    <h4 style="font-size: 14px; font-weight: 700; color: #0f172a; line-height: 1.35; margin: 0 0 6px 0;">
                                        <?php echo htmlspecialchars(substr($rh['title'], 0, 55)) . (strlen($rh['title']) > 55 ? '...' : ''); ?>
                                    </h4>
                                    <span style="font-size: 12px; color: #0284c7; font-weight: 600;">
                                        Read Guide <i class="fa-solid fa-arrow-right fa-xs"></i>
                                    </span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Card 3: 24/7 Help Desk Banner -->
            <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 18px; padding: 22px; text-align: center;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px auto; font-size: 18px;">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <h4 style="font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Emergency Repair Needed?</h4>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 14px; line-height: 1.5;">
                    Water leak burst or power outage? Our customer dispatch is ready 24 hours a day.
                </p>
                <a href="tel:+94777456789" style="font-size: 15px; font-weight: 800; color: #0284c7; text-decoration: none; display: block; margin-bottom: 10px;">
                    +94 777 456 789
                </a>
                <a href="contact.php" style="color: #64748b; font-size: 12.5px; text-decoration: underline;">
                    Send Support Message
                </a>
            </div>

        </aside>

    </div>
</div>

<script>
function recordHelpful(isHelpful, btn) {
    var parent = btn.parentElement;
    parent.innerHTML = '<span style="color: #059669; font-weight: 700; font-size: 14px;"><i class="fa-solid fa-circle-check"></i> Thank you for your feedback!</span>';
}

function copyArticleLink() {
    navigator.clipboard.writeText(window.location.href).then(function() {
        alert('Article link copied to clipboard!');
    }).catch(function() {
        prompt('Copy this link:', window.location.href);
    });
}
</script>

<?php endif; ?>

<?php
include('./partials/footer.php');
?>
