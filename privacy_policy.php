<?php 
include './partials/header.php'; 
?>

<!-- Hero Banner -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 35px 0; border-bottom: 1px solid #e2e8f0;">
    <div class="fn-container" style="max-width: 1000px; text-align: center;">
        <span class="fn-hero-badge" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
            <i class="fa-solid fa-shield-halved" style="color: #0284c7;"></i> FixNow Trust & Transparency
        </span>
        <h1 class="fn-section-title" style="margin-bottom: 12px; font-size: 34px;">Privacy Policy</h1>
        <p class="fn-section-subtitle" style="max-width: 680px; margin: 0 auto; color: #64748b; font-size: 16px;">
            Your privacy and digital trust are paramount. Learn how FIXNOW collects, protects, and handles your personal information across our home service platform.
        </p>
        <div style="margin-top: 15px; font-size: 13.5px; color: #94a3b8;">
            <i class="fa-regular fa-calendar-check"></i> Last Updated: November 2026 &nbsp;|&nbsp; Version 2.4
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
        <h4>Table of Contents</h4>
        <a href="#collect" class="fn-legal-nav-link active"><i class="fa-solid fa-database"></i> 1. Info We Collect</a>
        <a href="#usage" class="fn-legal-nav-link"><i class="fa-solid fa-sliders"></i> 2. How Data Is Used</a>
        <a href="#security" class="fn-legal-nav-link"><i class="fa-solid fa-lock"></i> 3. Security & Passwords</a>
        <a href="#sharing" class="fn-legal-nav-link"><i class="fa-solid fa-share-nodes"></i> 4. Sharing & Escrow</a>
        <a href="#cookies" class="fn-legal-nav-link"><i class="fa-solid fa-cookie-bite"></i> 5. Cookies & Tracking</a>
        <a href="#rights" class="fn-legal-nav-link"><i class="fa-solid fa-user-check"></i> 6. Your Rights</a>
        <a href="#contact" class="fn-legal-nav-link"><i class="fa-solid fa-envelope-open-text"></i> 7. Contact Officer</a>
    </aside>

    <!-- Main Content Stream -->
    <main>
        <!-- 1. Information We Collect -->
        <div class="fn-legal-card" id="collect">
            <h2><i class="fa-solid fa-database"></i> 1. Information We Collect</h2>
            <p>
                To provide seamless on-demand booking, verified technician dispatch, and escrow payment processing, FIXNOW collects specific essential data points:
            </p>
            <ul>
                <li><strong>Account Credentials:</strong> Full name, verified email address, contact phone number, and cryptographic password hashes.</li>
                <li><strong>Geographic Location Data:</strong> Town, district, and optional GPS coordinates (latitude/longitude) utilized for our radial proximity matching algorithm to dispatch the nearest certified specialist.</li>
                <li><strong>Service & Booking Records:</strong> Scheduled appointment dates, 2-hour arrival time-slots, specific service tasks requested, order notes, and chat communications with service providers.</li>
                <li><strong>Payment Metadata:</strong> Transaction identifiers, PayHere escrow payment status, platform fee records, and vendor disbursement receipts. (Note: Credit card numbers are tokenized by PayHere and never stored on FIXNOW servers).</li>
            </ul>
        </div>

        <!-- 2. How We Use Your Information -->
        <div class="fn-legal-card" id="usage">
            <h2><i class="fa-solid fa-sliders"></i> 2. How We Use Your Information</h2>
            <p>
                The information collected is used strictly for the fulfillment of household service appointments and continuous platform improvement:
            </p>
            <ul>
                <li>Facilitating direct appointments and dispatches between verified customers and qualified specialists.</li>
                <li>Managing our 10% Platform Protection Escrow engine to guarantee service satisfaction before releasing vendor payouts.</li>
                <li>Sending operational booking confirmation SMS/emails, time-slot arrival reminders, and invoice receipts.</li>
                <li>Preventing scheduling collisions and fraudulent specialist account creations.</li>
            </ul>
            <div class="fn-highlight-box">
                <i class="fa-solid fa-circle-check" style="color:#0284c7; margin-right:6px;"></i>
                <strong>Zero Selling Policy:</strong> FIXNOW never sells, rents, or monetizes your personal data or contact details to third-party advertising brokers under any circumstances.
            </div>
        </div>

        <!-- 3. Security & Passwords -->
        <div class="fn-legal-card" id="security">
            <h2><i class="fa-solid fa-lock"></i> 3. Security Architecture & Passwords</h2>
            <p>
                We employ industry-leading defense-in-depth measures to protect your account and transaction data:
            </p>
            <ul>
                <li><strong>Modern Bcrypt Hashing:</strong> All customer and vendor passwords are encrypted with high-cost Bcrypt algorithms. Raw passwords are never visible or retrievable.</li>
                <li><strong>100% Parameterized Statements:</strong> All platform endpoints utilize parameterized SQL prepared statements to eliminate SQL injection vulnerabilities.</li>
                <li><strong>Transport Layer Security:</strong> All web communication is encrypted in transit using standard 256-bit SSL/TLS protocol.</li>
            </ul>
        </div>

        <!-- 4. Sharing & Third-Party Escrow -->
        <div class="fn-legal-card" id="sharing">
            <h2><i class="fa-solid fa-share-nodes"></i> 4. Information Sharing & Integrations</h2>
            <p>
                We share data only with the designated parties required to fulfill your booked services:
            </p>
            <ul>
                <li><strong>Assigned Specialist:</strong> Your chosen certified technician receives your booking contact number, service town, and task notes to complete the scheduled service.</li>
                <li><strong>PayHere Payment Gateway (Sri Lanka):</strong> Financial transactions are processed via Central Bank of Sri Lanka (CBSL) approved gateway partners with encrypted cryptographic hash handshakes.</li>
                <li><strong>Legal Requirements:</strong> We may disclose information only if legally mandated by law enforcement or judicial summons.</li>
            </ul>
        </div>

        <!-- 5. Cookies & Local Storage -->
        <div class="fn-legal-card" id="cookies">
            <h2><i class="fa-solid fa-cookie-bite"></i> 5. Cookies & Local Storage</h2>
            <p>
                FIXNOW uses standard session cookies and local storage tokens to remember your login session, maintain your active booking cart, and retain UI preference selections. You can disable cookies in your browser settings, though doing so may prevent seamless appointment checkout.
            </p>
        </div>

        <!-- 6. Your Rights & Data Control -->
        <div class="fn-legal-card" id="rights">
            <h2><i class="fa-solid fa-user-check"></i> 6. Your Rights & Data Portability</h2>
            <p>You maintain full ownership and control over your personal data:</p>
            <ul>
                <li><strong>Access & Review:</strong> View all current bookings, profile addresses, and past work orders in your customer profile.</li>
                <li><strong>Rectification:</strong> Easily update your telephone number, password, or residential town via your account dashboard.</li>
                <li><strong>Account Deletion:</strong> Request full erasure of your account and personal identifiers by contacting our data team.</li>
            </ul>
        </div>

        <!-- 7. Contact Officer -->
        <div class="fn-legal-card" id="contact" style="border-color: #bae6fd; background: #f0f9ff;">
            <h2 style="border-bottom: 1px solid #bae6fd;"><i class="fa-solid fa-envelope-open-text"></i> 7. Data Protection Office</h2>
            <p>If you have any questions, inquiries, or privacy concerns regarding our data handling practices, please contact our Data Protection Officer:</p>
            <div style="margin-top: 14px; font-size: 14.5px; line-height: 1.8;">
                <strong>FIXNOW Lanka Technologies (Pvt) Ltd</strong><br>
                Data Privacy & Compliance Unit<br>
                Email: <a href="mailto:privacy@fixnow.lk" style="color: #0284c7; font-weight: 600;">privacy@fixnow.lk</a><br>
                Hotline: +94 11 234 5678 (Mon - Fri: 9:00 AM - 5:00 PM IST)
            </div>
        </div>
    </main>
</div>

<?php 
include './partials/footer.php'; 
?>
