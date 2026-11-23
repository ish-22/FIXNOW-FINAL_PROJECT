<?php 
include_once('../config/constant.php');
$current_page = 'faq.php';
include './partials/header.php'; 
?>

<style>
.fn-faq-accordion {
    max-width: 860px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.fn-faq-item {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    transition: all 0.2s ease;
}

.fn-faq-item:hover {
    border-color: #bae6fd;
}

.fn-faq-question {
    width: 100%;
    padding: 20px 24px;
    background: #ffffff;
    border: none;
    outline: none;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-align: left;
    font-size: 16.5px;
    font-weight: 700;
    color: #0f172a;
    font-family: inherit;
    transition: background 0.2s;
}

.fn-faq-question:hover {
    background: #f8fafc;
    color: #0284c7;
}

.fn-faq-question i {
    color: #0284c7;
    font-size: 14px;
    transition: transform 0.3s ease;
}

.fn-faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.35s ease, padding 0.35s ease;
    padding: 0 24px;
    background: #f8fafc;
    color: #475569;
    font-size: 14.5px;
    line-height: 1.7;
    border-top: 1px solid transparent;
}

.fn-faq-item.active .fn-faq-answer {
    max-height: 300px;
    padding: 18px 24px 22px 24px;
    border-top: 1px solid #e2e8f0;
}

.fn-faq-item.active .fn-faq-question i {
    transform: rotate(180deg);
}
</style>

<!-- Hero Section -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 20px 30px 20px; text-align: center; border-bottom: 1px solid #e2e8f0;">
    <div style="max-width: 800px; margin: 0 auto;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px; background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-circle-question"></i> Specialist &amp; Client Knowledge Base
        </span>
        <h1 style="font-size: 34px; font-weight: 900; color: #0f172a; margin: 0 0 10px 0;">Frequently Asked Questions</h1>
        <p style="color: #64748b; font-size: 16px; margin: 0 auto; max-width: 600px; line-height: 1.6;">
            Instant answers regarding work order allocations, PayHere escrow payouts, customer dispute procedures, and trade verification.
        </p>
    </div>
</div>

<!-- FAQs Content -->
<section style="padding: 50px 20px 80px 20px; background: #ffffff;">
    <div style="max-width: 900px; margin: 0 auto;">
        
        <div class="fn-faq-accordion">
            
            <div class="fn-faq-item active">
                <button class="fn-faq-question" onclick="toggleFaq(this)">
                    <span>How does FixNow Escrow Protection work for service providers?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    When a client books your service, they prepay via PayHere into our escrow account. You are guaranteed that funds are locked before you arrive at the job site. Once the work is confirmed complete, the escrow amount is released directly to your nominated bank account within 24 business hours.
                </div>
            </div>

            <div class="fn-faq-item">
                <button class="fn-faq-question" onclick="toggleFaq(this)">
                    <span>What happens if a client cancels a booking?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Clients can cancel free of charge up to 2 hours prior to the scheduled appointment window. If a cancellation occurs after you have already been dispatched or arrived at the site, platform cancellation terms apply to compensate your travel costs.
                </div>
            </div>

            <div class="fn-faq-item">
                <button class="fn-faq-question" onclick="toggleFaq(this)">
                    <span>How is my specialist ranking score calculated?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Your platform ranking is dynamically calculated from your average customer star rating plus points for verified job completions. Specialists with higher ranking scores receive priority dispatch allocation when customers search within their town.
                </div>
            </div>

            <div class="fn-faq-item">
                <button class="fn-faq-question" onclick="toggleFaq(this)">
                    <span>What is the monthly partner subscription requirement?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Newly onboarded specialists pay a fixed monthly membership fee for their first 3 calendar months. After successfully completing 3 months, your account becomes permanently active with zero monthly subscription fees.
                </div>
            </div>

            <div class="fn-faq-item">
                <button class="fn-faq-question" onclick="toggleFaq(this)">
                    <span>How do I resolve a disagreement on site?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    If scope changes, extra spare parts are needed, or site conditions are hazardous, do not proceed with work outside the platform. Contact the FixNow Specialist Help Desk immediately at <strong>+94 777 456 789</strong> for immediate assistance.
                </div>
            </div>

        </div>

    </div>
</section>

<script>
function toggleFaq(btn) {
    var item = btn.parentElement;
    item.classList.toggle('active');
}
</script>

<?php 
include './partials/footer.php'; 
?>
