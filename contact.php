<?php
include_once('./config/constant.php');

$success_msg = false;
$error_msg = '';
$ticket_id = '';

// Handle Contact Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_contact'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? 'General Inquiry');
    $message = trim($_POST['message'] ?? '');

    if (!empty($subject) && $subject !== 'General Inquiry') {
        $full_message = "[" . $subject . "] " . $message;
    } else {
        $full_message = $message;
    }

    if (!empty($name) && !empty($phone) && !empty($email) && !empty($message)) {
        $inserted = db_execute($conn, "INSERT INTO tbl_contact (name, phone, email, message) VALUES (?, ?, ?, ?)", [$name, $phone, $email, $full_message], "ssss");
        if ($inserted) {
            $success_msg = true;
            $ticket_id = 'FN-' . rand(10000, 99999);
        } else {
            $error_msg = "We were unable to save your request due to a temporary database issue. Please call our 24/7 hotline directly.";
        }
    } else {
        $error_msg = "Please fill in all required fields marked with an asterisk (*).";
    }
}

// Check redirect status
if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $success_msg = true;
    $ticket_id = 'FN-' . rand(10000, 99999);
}

// Pre-fill user information if logged in
$prefill_name = '';
$prefill_email = '';
$prefill_phone = '';

if (isset($_SESSION['customer_id'])) {
    $cus = db_fetch_one($conn, "SELECT cus_name, cus_email, cus_phone FROM tbl_customer WHERE id = ?", [$_SESSION['customer_id']], "i");
    if ($cus) {
        $prefill_name = $cus['cus_name'];
        $prefill_email = $cus['cus_email'];
        $prefill_phone = $cus['cus_phone'];
    }
} elseif (isset($_SESSION['vendor_id'])) {
    $vnd = db_fetch_one($conn, "SELECT full_name, email, contact_no FROM tbl_service_providers WHERE id = ?", [$_SESSION['vendor_id']], "i");
    if ($vnd) {
        $prefill_name = $vnd['full_name'];
        $prefill_email = $vnd['email'];
        $prefill_phone = $vnd['contact_no'];
    }
}

$current_page = 'contact.php';
include('./partials/header.php');
?>

<!-- Hero Header Section -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 35px 0; border-bottom: 1px solid #e2e8f0; text-align: center;">
    <div class="fn-container" style="max-width: 840px;">
        <span class="fn-hero-badge" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
            <i class="fa-solid fa-headset" style="color: #0284c7;"></i> 24/7 Priority Customer Support
        </span>
        <h1 class="fn-section-title" style="margin-bottom: 12px; font-size: 36px; font-weight: 900; color: #0f172a;">
            How Can We Help You Today?
        </h1>
        <p class="fn-section-subtitle" style="color: #64748b; font-size: 16px; margin: 0 auto; max-width: 640px; line-height: 1.6;">
            Have questions about your booking, payment escrow, specialist verification, or need emergency assistance? Our Colombo support desk is available around the clock.
        </p>
    </div>
</div>

<!-- Quick Contact Channels (3-Cards Row) -->
<section style="padding: 35px 0 20px 0;">
    <div class="fn-container" style="max-width: 1140px; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
        
        <!-- Phone Card -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 26px; display: flex; align-items: center; gap: 18px; box-shadow: 0 4px 16px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 16px rgba(0,0,0,0.02)'">
            <div style="width: 52px; height: 52px; border-radius: 14px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                <i class="fa-solid fa-phone-volume"></i>
            </div>
            <div>
                <span style="font-size: 12px; font-weight: 800; color: #0284c7; text-transform: uppercase; letter-spacing: 0.5px;">24/7 Hotline</span>
                <h4 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 2px 0 4px 0;">
                    <a href="tel:+94777456789" style="color: inherit; text-decoration: none;">+94 777 456 789</a>
                </h4>
                <p style="font-size: 13px; color: #64748b; margin: 0;">Landline: +94 11 234 5678</p>
            </div>
        </div>

        <!-- Email Card -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 26px; display: flex; align-items: center; gap: 18px; box-shadow: 0 4px 16px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 16px rgba(0,0,0,0.02)'">
            <div style="width: 52px; height: 52px; border-radius: 14px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div>
                <span style="font-size: 12px; font-weight: 800; color: #10b981; text-transform: uppercase; letter-spacing: 0.5px;">Email Support</span>
                <h4 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 2px 0 4px 0;">
                    <a href="mailto:support@fixnow.lk" style="color: inherit; text-decoration: none;">support@fixnow.lk</a>
                </h4>
                <p style="font-size: 13px; color: #64748b; margin: 0;">Average response: &lt; 2 hours</p>
            </div>
        </div>

        <!-- Office Card -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 26px; display: flex; align-items: center; gap: 18px; box-shadow: 0 4px 16px rgba(0,0,0,0.02); transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 24px rgba(0,0,0,0.06)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 16px rgba(0,0,0,0.02)'">
            <div style="width: 52px; height: 52px; border-radius: 14px; background: #fef3c7; color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <div>
                <span style="font-size: 12px; font-weight: 800; color: #f59e0b; text-transform: uppercase; letter-spacing: 0.5px;">Head Office</span>
                <h4 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 2px 0 4px 0;">Colombo 03</h4>
                <p style="font-size: 13px; color: #64748b; margin: 0;">No. 75, Galle Road, Colombo</p>
            </div>
        </div>

    </div>
</section>

<!-- Main Form & Map Section (2 Columns) -->
<section style="padding: 20px 0 80px 0;">
    <div class="fn-container" style="max-width: 1140px; display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 36px; align-items: start;">
        
        <!-- Left Column: Modern Support Inquiry Form -->
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 22px; padding: 36px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            
            <div style="margin-bottom: 24px;">
                <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">
                    Send Us a Message
                </h2>
                <p style="color: #64748b; font-size: 14.5px; margin: 0; line-height: 1.5;">
                    Please share details regarding your inquiry. Our support team will review and reply directly via email or phone.
                </p>
            </div>

            <?php if ($success_msg): ?>
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 14px; padding: 20px 22px; color: #065f46; margin-bottom: 26px; display: flex; gap: 14px; align-items: start;">
                    <i class="fa-solid fa-circle-check" style="font-size: 24px; color: #10b981; flex-shrink: 0; margin-top: 2px;"></i>
                    <div>
                        <strong style="display: block; font-size: 16px; margin-bottom: 3px;">Thank You! Your Inquiry Has Been Received.</strong>
                        <p style="font-size: 14px; margin: 0 0 6px 0; color: #047857; line-height: 1.5;">
                            Support Ticket <strong style="background: #d1fae5; padding: 2px 8px; border-radius: 6px;"><?php echo htmlspecialchars($ticket_id); ?></strong> has been generated. A customer support specialist will review your request and respond within 2 business hours.
                        </p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($error_msg)): ?>
                <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 14px; padding: 18px 20px; color: #991b1b; display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 20px; color: #ef4444; flex-shrink: 0;"></i>
                    <span style="font-size: 14px; font-weight: 600;"><?php echo htmlspecialchars($error_msg); ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                    <div>
                        <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                            <i class="fa-solid fa-user" style="color: #0284c7; margin-right: 5px;"></i> Full Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($prefill_name); ?>" placeholder="e.g. Kasun Perera" required
                               style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; transition: border-color 0.2s;"
                               onfocus="this.style.borderColor='#0284c7';" onblur="this.style.borderColor='#cbd5e1';">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                            <i class="fa-solid fa-phone" style="color: #0284c7; margin-right: 5px;"></i> Phone Number <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="tel" name="phone" value="<?php echo htmlspecialchars($prefill_phone); ?>" placeholder="e.g. +94 77 123 4567" required
                               style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; transition: border-color 0.2s;"
                               onfocus="this.style.borderColor='#0284c7';" onblur="this.style.borderColor='#cbd5e1';">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        <i class="fa-solid fa-envelope" style="color: #0284c7; margin-right: 5px;"></i> Email Address <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($prefill_email); ?>" placeholder="e.g. kasun@example.com" required
                           style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; transition: border-color 0.2s;"
                           onfocus="this.style.borderColor='#0284c7';" onblur="this.style.borderColor='#cbd5e1';">
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        <i class="fa-solid fa-tag" style="color: #0284c7; margin-right: 5px;"></i> Inquiry Department
                    </label>
                    <select name="subject" style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; background: #ffffff;">
                        <option value="General Inquiry">General Platform Inquiry</option>
                        <option value="Booking & Scheduling">Booking &amp; Technician Arrival Slot</option>
                        <option value="PayHere Escrow & Payments">Payment, Escrow Holding &amp; Refunds</option>
                        <option value="Specialist Quality & Complaint">Specialist Quality or Service Dispute</option>
                        <option value="Specialist Registration">Becoming a Verified Service Partner</option>
                        <option value="Emergency Priority Assistance">Emergency Technical Issue (Urgent)</option>
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        <i class="fa-solid fa-message" style="color: #0284c7; margin-right: 5px;"></i> Message / Inquiry Details <span style="color: #ef4444;">*</span>
                    </label>
                    <textarea name="message" placeholder="Please describe what you need assistance with, including any booking reference codes or specialist names if applicable..." required rows="5"
                              style="width: 100%; box-sizing: border-box; padding: 14px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; line-height: 1.6; resize: vertical; transition: border-color 0.2s;"
                              onfocus="this.style.borderColor='#0284c7';" onblur="this.style.borderColor='#cbd5e1';"></textarea>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <button type="submit" name="submit_contact" class="fn-btn fn-btn-primary" style="padding: 13px 30px; font-size: 15px; font-weight: 700; border-radius: 50px; cursor: pointer;">
                        <i class="fa-solid fa-paper-plane" style="margin-right: 8px;"></i> Send Inquiry
                    </button>
                    <a href="faq.php" style="color: #64748b; font-size: 13.5px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-regular fa-circle-question"></i> Browse FAQs First
                    </a>
                </div>
            </form>
        </div>

        <!-- Right Column: Support SLA, Office Location & Map -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Service SLA Card -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 26px; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-shield-halved" style="color: #0284c7;"></i> FixNow Customer Commitment
                </h3>

                <div style="display: flex; flex-direction: column; gap: 14px; font-size: 13.5px; color: #475569;">
                    <div style="display: flex; gap: 12px; align-items: start;">
                        <i class="fa-solid fa-clock-rotate-left" style="color: #0284c7; margin-top: 3px;"></i>
                        <div>
                            <strong style="color: #0f172a; display: block;">2-Hour Response Time</strong>
                            Support tickets are acknowledged and answered by dedicated representatives within 2 business hours.
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: start;">
                        <i class="fa-solid fa-vault" style="color: #10b981; margin-top: 3px;"></i>
                        <div>
                            <strong style="color: #0f172a; display: block;">Escrow Protection Assistance</strong>
                            Funds remain secured in PayHere escrow until you approve technician work quality.
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; align-items: start;">
                        <i class="fa-solid fa-user-check" style="color: #f59e0b; margin-top: 3px;"></i>
                        <div>
                            <strong style="color: #0f172a; display: block;">Specialist Arbitration</strong>
                            Fair and objective dispute resolution for any job disagreements or cancellations.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Head Office Location & Interactive Map -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 22px; box-shadow: 0 4px 16px rgba(0,0,0,0.02); overflow: hidden;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-map-location-dot" style="color: #0284c7;"></i> Colombo Head Office
                    </h3>
                    <a href="https://maps.google.com/?q=No.+75,+Galle+Road,+Colombo+03,+Sri+Lanka" target="_blank" style="font-size: 12.5px; color: #0284c7; font-weight: 700; text-decoration: none;">
                        Open Map <i class="fa-solid fa-arrow-up-right-from-square fa-xs"></i>
                    </a>
                </div>

                <div style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; height: 220px; margin-bottom: 14px;">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d12611.355859295573!2d79.95823864697266!3d6.933054749999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2592836fca451%3A0x31b9a5cb6251b545!2sGalle%20Road%2C%20Colombo%2003%2C%20Sri%20Lanka!5e0!3m2!1sen!2slk!4v1711214006526!5m2!1sen!2slk" 
                            width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>

                <p style="font-size: 13px; color: #64748b; margin: 0; line-height: 1.5;">
                    <i class="fa-solid fa-building" style="color: #94a3b8; margin-right: 4px;"></i> 
                    No. 75, Galle Road, Colombo 03, Western Province, Sri Lanka.
                </p>
            </div>

            <!-- Instant FAQ Callout -->
            <div style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 18px; padding: 20px; text-align: center;">
                <h4 style="font-size: 15px; font-weight: 800; color: #0f172a; margin-bottom: 4px;">Looking for Fast Answers?</h4>
                <p style="font-size: 13px; color: #64748b; margin-bottom: 14px; line-height: 1.5;">
                    Most questions regarding specialist rates, cancellation policies, and escrow payouts are answered in our Help Center.
                </p>
                <a href="faq.php" class="fn-btn fn-btn-primary fn-btn-pill" style="padding: 8px 20px; font-size: 13px;">
                    Visit FAQ Center
                </a>
            </div>

        </div>

    </div>
</section>

<?php
include('./partials/footer.php');
?>
