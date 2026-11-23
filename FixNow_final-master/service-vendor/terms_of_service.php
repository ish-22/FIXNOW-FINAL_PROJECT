<?php 
include_once('../config/constant.php');
$current_page = 'terms_of_service.php';
include './partials/header.php'; 
?>

<!-- Hero Banner -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 20px 35px 20px; border-bottom: 1px solid #e2e8f0; text-align: center;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px; background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-scale-balanced"></i> Specialist &amp; Platform Agreement
        </span>
        <h1 style="margin-bottom: 12px; font-size: 34px; font-weight: 900; color: #0f172a;">Terms of Service</h1>
        <p style="max-width: 680px; margin: 0 auto; color: #64748b; font-size: 16px; line-height: 1.6;">
            These Terms govern your partnership with FIXNOW, job allocations, escrow payout rules, on-site safety, and community standards.
        </p>
        <div style="margin-top: 15px; font-size: 13.5px; color: #94a3b8;">
            <i class="fa-regular fa-calendar-check"></i> Effective Date: November 2026 &nbsp;|&nbsp; Version 2.2
        </div>
    </div>
</div>

<style>
.fn-legal-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 36px;
    margin: 40px auto 80px auto;
    max-width: 1100px;
    padding: 0 20px;
}
@media (max-width: 900px) {
    .fn-legal-layout {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    .fn-legal-nav {
        position: static !important;
        top: 0 !important;
    }
}
.fn-legal-nav {
    position: sticky;
    top: 90px;
    align-self: start;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.03);
}
.fn-legal-nav h4 {
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #94a3b8;
    margin-bottom: 14px;
    font-weight: 700;
}
.fn-legal-nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    color: #334155;
    text-decoration: none;
    font-size: 14.5px;
    font-weight: 600;
    transition: all 0.2s ease;
    margin-bottom: 4px;
}
.fn-legal-nav-link:hover, .fn-legal-nav-link.active {
    background: #f0f9ff;
    color: #0284c7;
    transform: translateX(4px);
}
.fn-legal-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 32px;
    margin-bottom: 24px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.02);
    scroll-margin-top: 100px;
}
.fn-legal-card h2 {
    font-size: 20px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f1f5f9;
}
.fn-legal-card h2 i {
    color: #0284c7;
    font-size: 18px;
}
.fn-legal-card p, .fn-legal-card li {
    font-size: 15px;
    color: #475569;
    line-height: 1.7;
}
.fn-legal-card ul {
    margin: 12px 0 16px 20px;
}
.fn-legal-card li {
    margin-bottom: 8px;
}
.fn-highlight-box {
    background: #f8fafc;
    border-left: 4px solid #0284c7;
    padding: 16px 20px;
    border-radius: 0 12px 12px 0;
    margin: 16px 0;
    font-size: 14.5px;
    color: #334155;
}
</style>

<div class="fn-legal-layout">
    <!-- Sidebar Sticky Navigation -->
    <aside class="fn-legal-nav">
        <h4>Navigation</h4>
        <a href="#acceptance" class="fn-legal-nav-link active"><i class="fa-solid fa-handshake"></i> 1. Acceptance</a>
        <a href="#accounts" class="fn-legal-nav-link"><i class="fa-solid fa-user-shield"></i> 2. Accounts &amp; Access</a>
        <a href="#escrow" class="fn-legal-nav-link"><i class="fa-solid fa-vault"></i> 3. Escrow &amp; Payouts</a>
        <a href="#scheduling" class="fn-legal-nav-link"><i class="fa-solid fa-clock"></i> 4. Time Slots &amp; Dispatch</a>
        <a href="#cancellations" class="fn-legal-nav-link"><i class="fa-solid fa-ban"></i> 5. Cancellations</a>
        <a href="#conduct" class="fn-legal-nav-link"><i class="fa-solid fa-award"></i> 6. Specialist Standards</a>
        <a href="#liability" class="fn-legal-nav-link"><i class="fa-solid fa-triangle-exclamation"></i> 7. Liability &amp; Disputes</a>
    </aside>

    <!-- Main Content -->
    <main>
        <!-- 1. Acceptance of Terms -->
        <div class="fn-legal-card" id="acceptance">
            <h2><i class="fa-solid fa-handshake"></i> 1. Acceptance of Terms</h2>
            <p>
                By accessing, browsing, registering on, or utilizing the services provided by <strong>FIXNOW</strong> ("the Platform", "we", "us"), you acknowledge that you have read, understood, and agreed to be legally bound by these Terms of Service and our Privacy Policy.
            </p>
        </div>

        <!-- 2. Accounts & User Eligibility -->
        <div class="fn-legal-card" id="accounts">
            <h2><i class="fa-solid fa-user-shield"></i> 2. Accounts &amp; Eligibility</h2>
            <p>To register an account or operate as a verified specialist on FIXNOW, you must:</p>
            <ul>
                <li>Be at least 18 years of age and legally authorized to enter binding contracts.</li>
                <li>Provide accurate, verifiable identification, contact numbers, and relevant trade certification.</li>
                <li>Maintain confidentiality of login credentials and immediately notify FIXNOW of unauthorized account access.</li>
            </ul>
        </div>

        <!-- 3. Escrow & Payment Guarantee -->
        <div class="fn-legal-card" id="escrow">
            <h2><i class="fa-solid fa-vault"></i> 3. Escrow &amp; Payout Protocol</h2>
            <p>
                All financial transactions on FIXNOW are secured via licensed PayHere payment escrow:
            </p>
            <div class="fn-highlight-box">
                <i class="fa-solid fa-shield-halved" style="color: #0284c7; margin-right: 6px;"></i>
                <strong>Escrow Guarantee:</strong> Once the job is completed and confirmed by the customer, payment disbursements are executed directly to your designated bank account.
            </div>
        </div>

        <!-- 4. Scheduling & Time Slot Integrity -->
        <div class="fn-legal-card" id="scheduling">
            <h2><i class="fa-solid fa-clock"></i> 4. Scheduling &amp; Arrival Slots</h2>
            <p>
                To eliminate waiting friction, appointments are assigned with confirmed arrival windows. Specialists agree to adhere to assigned time slots and promptly notify customers of road or transit delays.
            </p>
        </div>

        <!-- 5. Cancellations & Rescheduling -->
        <div class="fn-legal-card" id="cancellations">
            <h2><i class="fa-solid fa-ban"></i> 5. Cancellations &amp; Rescheduling</h2>
            <p>Fair guidelines ensure mutual accountability between clients and contractors:</p>
            <ul>
                <li><strong>Advance Cancellation:</strong> Cancellations made at least 12 hours prior to arrival are refunded 100% in full.</li>
                <li><strong>Late Cancellation:</strong> Late cancellations within 2 hours of arrival may incur a call-out compensation fee for the en-route specialist.</li>
            </ul>
        </div>

        <!-- 6. Service Provider Standards -->
        <div class="fn-legal-card" id="conduct">
            <h2><i class="fa-solid fa-award"></i> 6. Specialist Professional Standards</h2>
            <p>Every independent specialist on FIXNOW agrees to:</p>
            <ul>
                <li>Maintain rigorous standards of workmanship, safety equipment, and customer courtesy.</li>
                <li>Comply with domestic occupational safety standards (electrical safety, plumbing codes, AC gas handling).</li>
            </ul>
        </div>

        <!-- 7. Liability & Dispute Resolution -->
        <div class="fn-legal-card" id="liability">
            <h2><i class="fa-solid fa-triangle-exclamation"></i> 7. Limitation of Liability &amp; Disputes</h2>
            <p>
                These terms are governed by the laws of Sri Lanka. Any unresolved service disputes shall be arbitrated through the FIXNOW quality management team.
            </p>
        </div>
    </main>
</div>

<?php 
include './partials/footer.php'; 
?>
