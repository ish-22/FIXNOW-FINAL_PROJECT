<?php 
include './partials/header.php'; 
?>

<!-- Hero Banner -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 35px 0; border-bottom: 1px solid #e2e8f0;">
    <div class="fn-container" style="max-width: 1000px; text-align: center;">
        <span class="fn-hero-badge" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
            <i class="fa-solid fa-scale-balanced" style="color: #0284c7;"></i> FixNow Legal Agreement
        </span>
        <h1 class="fn-section-title" style="margin-bottom: 12px; font-size: 34px;">Terms of Service</h1>
        <p class="fn-section-subtitle" style="max-width: 680px; margin: 0 auto; color: #64748b; font-size: 16px;">
            These Terms govern your use of the FIXNOW on-demand marketplace, specialist bookings, escrow payments, and community safety obligations.
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
.fn-warning-box {
    background: #fffbeb;
    border-left: 4px solid #f59e0b;
    padding: 16px 20px;
    border-radius: 0 12px 12px 0;
    margin: 16px 0;
    font-size: 14.5px;
    color: #92400e;
}
</style>

<div class="fn-legal-layout">
    <!-- Sidebar Sticky Navigation -->
    <aside class="fn-legal-nav">
        <h4>Navigation</h4>
        <a href="#acceptance" class="fn-legal-nav-link active"><i class="fa-solid fa-handshake"></i> 1. Acceptance</a>
        <a href="#accounts" class="fn-legal-nav-link"><i class="fa-solid fa-user-shield"></i> 2. Accounts & Access</a>
        <a href="#escrow" class="fn-legal-nav-link"><i class="fa-solid fa-vault"></i> 3. Escrow & Payments</a>
        <a href="#scheduling" class="fn-legal-nav-link"><i class="fa-solid fa-clock"></i> 4. Time Slots & Dispatch</a>
        <a href="#cancellations" class="fn-legal-nav-link"><i class="fa-solid fa-ban"></i> 5. Cancellations</a>
        <a href="#conduct" class="fn-legal-nav-link"><i class="fa-solid fa-award"></i> 6. Specialist Standards</a>
        <a href="#liability" class="fn-legal-nav-link"><i class="fa-solid fa-triangle-exclamation"></i> 7. Liability & Disputes</a>
    </aside>

    <!-- Main Content -->
    <main>
        <!-- 1. Acceptance of Terms -->
        <div class="fn-legal-card" id="acceptance">
            <h2><i class="fa-solid fa-handshake"></i> 1. Acceptance of Terms</h2>
            <p>
                By accessing, browsing, registering on, or utilizing the services provided by <strong>FIXNOW</strong> ("the Platform", "we", "us"), you acknowledge that you have read, understood, and agreed to be legally bound by these Terms of Service and our Privacy Policy.
            </p>
            <p>
                If you are entering into these terms on behalf of a business, corporate entity, or service company, you represent that you have the full legal authority to bind such entity.
            </p>
        </div>

        <!-- 2. Accounts & User Eligibility -->
        <div class="fn-legal-card" id="accounts">
            <h2><i class="fa-solid fa-user-shield"></i> 2. Accounts & Eligibility</h2>
            <p>To register an account or book services on FIXNOW, you must:</p>
            <ul>
                <li>Be at least 18 years of age and legally competent to enter into binding contracts under Sri Lankan law.</li>
                <li>Provide genuine, accurate, and current contact details (name, email, verified mobile number, and address).</li>
                <li>Safeguard your authentication credentials. You are strictly responsible for all activities and transactions executed under your credentials.</li>
            </ul>
        </div>

        <!-- 3. Escrow Model & Transparent Pricing -->
        <div class="fn-legal-card" id="escrow">
            <h2><i class="fa-solid fa-vault"></i> 3. Booking Escrow & Payment Protection</h2>
            <p>
                FIXNOW operates under a protected <strong>Commission Escrow Model</strong> to ensure total financial security for both customers and verified technicians:
            </p>
            <ul>
                <li><strong>Transparent Upfront Pricing:</strong> Service booking prices are clearly itemized before booking, including the 10% platform protection fee.</li>
                <li><strong>Escrow Holding:</strong> When a booking is confirmed and paid, funds are held in platform escrow. The fee is never released until the task status is marked <em>Completed</em>.</li>
                <li><strong>Fair Specialist Payouts:</strong> Upon completion, 90% of the booking price is disbursed to the specialist, ensuring fair wages and reliable technician partnerships.</li>
            </ul>
            <div class="fn-highlight-box">
                <i class="fa-solid fa-shield-check" style="color:#0284c7; margin-right:6px;"></i>
                <strong>Zero Cash Concealment:</strong> All booking fees must be transacted through the FIXNOW platform. Direct off-platform solicitations breach these terms and void all warranty coverage.
            </div>
        </div>

        <!-- 4. Scheduling & Time-Slot Windows -->
        <div class="fn-legal-card" id="scheduling">
            <h2><i class="fa-solid fa-clock"></i> 4. Time Slots & Technician Dispatch</h2>
            <p>
                Customers select a scheduled date and specific arrival time-slot (Morning: 09:00 - 12:00, Afternoon: 13:00 - 16:00, or Evening: 16:00 - 19:00). 
            </p>
            <ul>
                <li>Specialists are expected to arrive within the designated 2 to 3-hour arrival window.</li>
                <li>If emergency delays occur due to transit or inclement weather, specialists will communicate directly via the FIXNOW in-app chat or phone.</li>
            </ul>
        </div>

        <!-- 5. Cancellations & Rescheduling -->
        <div class="fn-legal-card" id="cancellations">
            <h2><i class="fa-solid fa-ban"></i> 5. Cancellation & Refund Policy</h2>
            <p>We recognize plans change. Our fair cancellation guidelines ensure flexibility:</p>
            <ul>
                <li><strong>Free Cancellation:</strong> Cancellations made at least 12 hours prior to the scheduled arrival time slot receive a 100% full refund of the escrow balance.</li>
                <li><strong>Late Cancellation:</strong> Cancellations made within 2 hours of arrival may be subject to a nominal call-out fee to compensate the en-route technician.</li>
                <li><strong>Specialist Non-Arrival:</strong> If a specialist fails to arrive without notice, the customer receives an immediate 100% full refund and a discount credit for future bookings.</li>
            </ul>
        </div>

        <!-- 6. Service Provider Standards -->
        <div class="fn-legal-card" id="conduct">
            <h2><i class="fa-solid fa-award"></i> 6. Specialist Professional Standards</h2>
            <p>Every independent specialist on FIXNOW agrees to:</p>
            <ul>
                <li>Maintain rigorous standards of workmanship, workplace cleanliness, and customer courtesy.</li>
                <li>Undergo identity verification and certification verification during onboarding.</li>
                <li>Comply with all domestic occupational safety standards (electrical safety, plumbing codes, AC gas handling).</li>
            </ul>
        </div>

        <!-- 7. Liability & Dispute Resolution -->
        <div class="fn-legal-card" id="liability">
            <h2><i class="fa-solid fa-triangle-exclamation"></i> 7. Limitation of Liability & Disputes</h2>
            <p>
                FIXNOW operates as a trusted technology marketplace connecting homeowners with independent certified contractors. While we verify provider credentials and guarantee escrow protection:
            </p>
            <ul>
                <li>Contractors operate as independent professionals. FIXNOW’s cumulative financial liability for any service dispute is strictly limited to the booking amount transacted.</li>
                <li>In case of an unsatisfied job, the customer can flag a dispute within 48 hours of task completion to pause payout release while our arbitration team inspects the case.</li>
                <li>These terms are governed by the laws of Sri Lanka. Any disputes shall be subject to the exclusive jurisdiction of the courts of Colombo.</li>
            </ul>
        </div>
    </main>
</div>

<?php 
include './partials/footer.php'; 
?>
