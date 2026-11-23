<?php
include_once('../config/constant.php');

$success_msg = false;
$error_msg = '';

// Handle direct form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_user_feedback'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $category = trim($_POST['feedback_category'] ?? '');

    if (!empty($category) && $category !== 'General') {
        $message = "[" . $category . "] " . $message;
    }

    if (!empty($name) && !empty($email) && !empty($message)) {
        $inserted = db_execute($conn, "INSERT INTO tbl_user_feedback (name, email, message) VALUES (?, ?, ?)", [$name, $email, $message], "sss");
        if ($inserted) {
            $success_msg = true;
        } else {
            $error_msg = "Database error saving your feedback. Please try again.";
        }
    } else {
        $error_msg = "Please fill in all required fields.";
    }
}

// Check for status redirect from submit_feedback.php
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $success_msg = true;
}

// Prefill data for vendor
$prefill_name = '';
$prefill_email = '';
if (isset($_SESSION['vendor_id'])) {
    $vnd = db_fetch_one($conn, "SELECT full_name, email FROM tbl_service_providers WHERE id = ?", [$_SESSION['vendor_id']], "i");
    if ($vnd) {
        $prefill_name = $vnd['full_name'];
        $prefill_email = $vnd['email'];
    }
} elseif (isset($_SESSION['customer_id'])) {
    $cus = db_fetch_one($conn, "SELECT cus_name, cus_email FROM tbl_customer WHERE id = ?", [$_SESSION['customer_id']], "i");
    if ($cus) {
        $prefill_name = $cus['cus_name'];
        $prefill_email = $cus['cus_email'];
    }
}

// Fetch recent community feedback
$recent_feedback = db_fetch_all($conn, "SELECT name, message, submitted_at FROM tbl_user_feedback ORDER BY id DESC LIMIT 3");

include('./partials/header.php'); 
?>

<!-- Hero Section -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 35px 0; border-bottom: 1px solid #e2e8f0; text-align: center;">
    <div style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px; background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-screwdriver-wrench"></i> Specialist Portal Feedback
        </span>
        <h1 style="margin-bottom: 12px; font-size: 32px; font-weight: 800; color: #0f172a;">Specialist &amp; Partner Feedback</h1>
        <p style="color: #64748b; font-size: 15.5px; margin: 0 auto; max-width: 620px; line-height: 1.6;">
            Your experience as an onboarded service provider shapes how our platform assigns bookings, manages payout disbursements, and safeguards verified trade quality.
        </p>
    </div>
</div>

<!-- Main Content Area (2 Columns) -->
<section style="padding: 40px 20px 80px 20px; max-width: 1100px; margin: 0 auto;">
    <div style="display: grid; grid-template-columns: 1fr 380px; gap: 36px; align-items: start;">
        
        <!-- Left Column: Modern Feedback Form Card -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 36px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            
            <div style="margin-bottom: 24px;">
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                    Submit Your Feedback
                </h2>
                <p style="color: #64748b; font-size: 14.5px; margin: 0;">
                    Tell us about job scheduling, payment escrow, or improvements for the specialist mobile console.
                </p>
            </div>

            <?php if ($success_msg): ?>
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 18px 20px; color: #065f46; display: flex; align-items: center; gap: 14px; margin-bottom: 24px;">
                    <i class="fa-solid fa-circle-check" style="font-size: 22px; color: #10b981;"></i>
                    <div>
                        <strong style="display: block; font-size: 15px;">Thank You for Your Feedback!</strong>
                        <span style="font-size: 13.5px;">Your review has been saved and forwarded to the FixNow Operations Management team.</span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px 20px; color: #991b1b; display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px; color: #ef4444;"></i>
                    <span style="font-size: 14px; font-weight: 600;"><?php echo htmlspecialchars($error_msg); ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                        <i class="fa-solid fa-user" style="color: #0284c7; margin-right: 6px;"></i> Full Name
                    </label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($prefill_name); ?>" placeholder="e.g. Ruwan Silva" required
                           style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='#0284c7';" onblur="this.style.borderColor='#cbd5e1';">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                        <i class="fa-solid fa-envelope" style="color: #0284c7; margin-right: 6px;"></i> Registered Email
                    </label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($prefill_email); ?>" placeholder="e.g. ruwan@specialist.fixnow.lk" required
                           style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='#0284c7';" onblur="this.style.borderColor='#cbd5e1';">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                        <i class="fa-solid fa-layer-group" style="color: #0284c7; margin-right: 6px;"></i> Feedback Category
                    </label>
                    <select name="feedback_category" style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; background: #ffffff;">
                        <option value="General Operations">General Portal Operations</option>
                        <option value="Dispatch & Booking">Work Order Dispatch &amp; Arrival Slots</option>
                        <option value="Escrow & Payouts">PayHere Escrow &amp; Bank Payout Timelines</option>
                        <option value="Customer Relations">Customer Interactions &amp; On-Site Safety</option>
                        <option value="App & Console Features">Feature Request / Specialist Console UI</option>
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 8px;">
                        <i class="fa-solid fa-comment-dots" style="color: #0284c7; margin-right: 6px;"></i> Detailed Comments &amp; Recommendations
                    </label>
                    <textarea name="message" placeholder="Provide details regarding your recent job experiences or technical suggestions for the platform..." required rows="5"
                              style="width: 100%; box-sizing: border-box; padding: 14px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; line-height: 1.6; resize: vertical; transition: border-color 0.2s;"
                              onfocus="this.style.borderColor='#0284c7';" onblur="this.style.borderColor='#cbd5e1';"></textarea>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <button type="submit" name="submit_user_feedback" style="background: #0284c7; color: #ffffff; border: none; padding: 13px 28px; font-size: 15px; font-weight: 700; border-radius: 50px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: background 0.2s;"
                            onmouseover="this.style.background='#0369a1';" onmouseout="this.style.background='#0284c7';">
                        <i class="fa-solid fa-paper-plane"></i> Submit Feedback
                    </button>
                    <a href="index.php" style="color: #64748b; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-arrow-left"></i> Return to Portal
                    </a>
                </div>
            </form>
        </div>

        <!-- Right Column: Support & Guidelines -->
        <div>
            <!-- Card 1: Specialist Benefits -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 26px; box-shadow: 0 4px 14px rgba(0,0,0,0.02); margin-bottom: 24px;">
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-certificate" style="color: #0284c7;"></i> Specialist Partnership
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 16px; font-size: 14px; color: #475569; line-height: 1.6;">
                    <div style="display: flex; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                        </div>
                        <div>
                            <strong style="color: #0f172a; display: block; margin-bottom: 2px;">Direct Escrow Settlements</strong>
                            Completed and verified work orders are disbursed directly to your designated bank account.
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div>
                            <strong style="color: #0f172a; display: block; margin-bottom: 2px;">Verified Reputation</strong>
                            High customer ratings increase your profile visibility across Colombo, Gampaha, and Kandy regions.
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px;">
                        <div style="width: 32px; height: 32px; border-radius: 8px; background: #fef3c7; color: #f59e0b; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 14px;">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <div>
                            <strong style="color: #0f172a; display: block; margin-bottom: 2px;">Partner Protection</strong>
                            Transparent dispute arbitration for cancelled bookings or irregular site conditions.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Recent Reviews -->
            <?php if (!empty($recent_feedback)): ?>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 24px; box-shadow: 0 4px 14px rgba(0,0,0,0.02); margin-bottom: 24px;">
                    <h3 style="font-size: 15.5px; font-weight: 700; color: #0f172a; margin-bottom: 14px;">
                        <i class="fa-solid fa-bullhorn" style="color: #0284c7; margin-right: 6px;"></i> Recent Community Input
                    </h3>
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <?php foreach ($recent_feedback as $rf): ?>
                            <div style="background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 10px; padding: 12px 14px; font-size: 13px;">
                                <p style="color: #475569; margin: 0 0 6px 0; font-style: italic; line-height: 1.5;">
                                    "<?php echo htmlspecialchars(substr($rf['message'], 0, 100)) . (strlen($rf['message']) > 100 ? '...' : ''); ?>"
                                </p>
                                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 11.5px; color: #94a3b8;">
                                    <strong style="color: #0f172a;"><?php echo htmlspecialchars($rf['name']); ?></strong>
                                    <span><?php echo date('M d, Y', strtotime($rf['submitted_at'])); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Need Vendor Help Card -->
            <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 18px; padding: 22px; text-align: center;">
                <i class="fa-solid fa-headset" style="font-size: 26px; color: #0284c7; margin-bottom: 8px;"></i>
                <h4 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Specialist Help Desk</h4>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 14px; line-height: 1.5;">
                    Need assistance with payout reconciliation, account credentials, or trade credentials?
                </p>
                <a href="contact.php" style="background: #0284c7; color: #ffffff; text-decoration: none; padding: 8px 18px; font-size: 13px; font-weight: 700; border-radius: 50px; display: inline-block;">
                    Contact Specialist Support
                </a>
            </div>

        </div>

    </div>
</section>

<?php 
include('./partials/footer.php'); 
?>
