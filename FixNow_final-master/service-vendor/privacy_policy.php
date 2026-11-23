<?php 
include_once('../config/constant.php');
$current_page = 'privacy_policy.php';
include './partials/header.php'; 
?>

<!-- Hero Banner -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 20px 35px 20px; border-bottom: 1px solid #e2e8f0; text-align: center;">
    <div style="max-width: 1000px; margin: 0 auto;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px; background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-user-shield"></i> FixNow Data Privacy
        </span>
        <h1 style="margin-bottom: 12px; font-size: 34px; font-weight: 900; color: #0f172a;">Privacy Policy</h1>
        <p style="max-width: 680px; margin: 0 auto; color: #64748b; font-size: 16px; line-height: 1.6;">
            How FIXNOW collects, uses, protects, and handles personal credentials, location coordinates, and payment records for partner specialists and homeowners.
        </p>
        <div style="margin-top: 15px; font-size: 13.5px; color: #94a3b8;">
            <i class="fa-regular fa-calendar-check"></i> Last Updated: November 2026 &nbsp;|&nbsp; Version 2.2
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
</style>

<div class="fn-legal-layout">
    <aside class="fn-legal-nav">
        <h4>Sections</h4>
        <a href="#collection" class="fn-legal-nav-link active"><i class="fa-solid fa-database"></i> 1. Information Collected</a>
        <a href="#usage" class="fn-legal-nav-link"><i class="fa-solid fa-gears"></i> 2. How Data is Used</a>
        <a href="#payments" class="fn-legal-nav-link"><i class="fa-solid fa-lock"></i> 3. Escrow &amp; Payments</a>
        <a href="#sharing" class="fn-legal-nav-link"><i class="fa-solid fa-share-nodes"></i> 4. Sharing &amp; Disclosure</a>
        <a href="#security" class="fn-legal-nav-link"><i class="fa-solid fa-shield-virus"></i> 5. Data Security</a>
    </aside>

    <main>
        <div class="fn-legal-card" id="collection">
            <h2><i class="fa-solid fa-database"></i> 1. Information We Collect</h2>
            <p>To operate a secure and verified service marketplace, we collect:</p>
            <ul>
                <li><strong>Identity &amp; Profile:</strong> Full name, verified mobile number, email, and national identity credentials for specialists.</li>
                <li><strong>Geographic Location:</strong> District, town, and GPS dispatch coordinates to match specialists with nearby customers.</li>
                <li><strong>Service Records:</strong> Work order notes, booking dates, arrival time slots, and customer feedback reviews.</li>
            </ul>
        </div>

        <div class="fn-legal-card" id="usage">
            <h2><i class="fa-solid fa-gears"></i> 2. How We Use Your Data</h2>
            <p>Your information is used strictly to:</p>
            <ul>
                <li>Route work orders and calculate accurate travel distance for service providers.</li>
                <li>Process transparent escrow disbursements via PayHere payment gateways.</li>
                <li>Maintain the specialist ranking score and verify quality standards.</li>
            </ul>
        </div>

        <div class="fn-legal-card" id="payments">
            <h2><i class="fa-solid fa-lock"></i> 3. Payment &amp; Escrow Security</h2>
            <p>
                FIXNOW never stores full credit or debit card numbers on local servers. All payment transactions are tokenized and processed through PayHere, certified with PCI-DSS compliance and bank-grade TLS encryption.
            </p>
        </div>

        <div class="fn-legal-card" id="sharing">
            <h2><i class="fa-solid fa-share-nodes"></i> 4. Information Sharing &amp; Privacy</h2>
            <p>
                We do not sell, rent, or monetize your personal information to third-party data brokers. Contact information is only shared between a homeowner and the assigned specialist when a booking is confirmed.
            </p>
        </div>

        <div class="fn-legal-card" id="security">
            <h2><i class="fa-solid fa-shield-virus"></i> 5. Data Protection Standards</h2>
            <p>
                Passwords are encrypted using modern cryptographic hash functions (`bcrypt`). Session tokens and prepared SQL statements prevent unauthorized parameter tampering.
            </p>
        </div>
    </main>
</div>

<?php 
include './partials/footer.php'; 
?>
