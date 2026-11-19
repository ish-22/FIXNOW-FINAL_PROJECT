<?php 
include './partials/header.php'; 
?>

<!-- Hero Banner -->
<div style="background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 60px 0 45px 0; border-bottom: 1px solid #334155;">
    <div class="fn-container" style="max-width: 900px; text-align: center;">
        <span class="fn-hero-badge" style="background: rgba(2, 132, 199, 0.2); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px;">
            <i class="fa-solid fa-shield-halved"></i> Bank-Grade Platform Protection
        </span>
        <h1 class="fn-section-title" style="color: #ffffff; margin-bottom: 14px; font-size: 36px;">FIXNOW Security Center</h1>
        <p class="fn-section-subtitle" style="color: #94a3b8; max-width: 650px; margin: 0 auto; font-size: 16px;">
            How FIXNOW secures your personal identity, transactions, home visits, and scheduling through multi-layered cryptographic and verification safeguards.
        </p>
    </div>
</div>

<style>
.fn-sec-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
    margin: 40px auto;
}
.fn-sec-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.03);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    overflow: hidden;
}
.fn-sec-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(2, 132, 199, 0.08);
    border-color: #bae6fd;
}
.fn-sec-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    background: #f0f9ff;
    color: #0284c7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 20px;
}
.fn-sec-card h3 {
    font-size: 19px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 12px;
}
.fn-sec-card p {
    font-size: 14.5px;
    color: #64748b;
    line-height: 1.65;
    margin-bottom: 16px;
}
.fn-sec-card ul {
    margin: 0;
    padding-left: 20px;
    font-size: 13.5px;
    color: #475569;
    line-height: 1.6;
}
.fn-sec-card li {
    margin-bottom: 6px;
}
.fn-sec-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 4px 10px;
    border-radius: 50px;
    background: #ecfdf5;
    color: #059669;
    letter-spacing: 0.05em;
}
</style>

<section style="padding: 20px 0 60px 0;">
    <div class="fn-container">
        
        <div style="text-align: center; margin-top: 30px; margin-bottom: 10px;">
            <span class="fn-hero-badge"><i class="fa-solid fa-lock"></i> Architecture</span>
            <h2 class="fn-section-title">The 6 Pillars of FixNow Protection</h2>
            <p class="fn-section-subtitle">Engineered to keep customer data confidential and service appointments dependable.</p>
        </div>

        <div class="fn-sec-grid">
            <!-- 1. Password & Encryption -->
            <div class="fn-sec-card">
                <span class="fn-sec-badge"><i class="fa-solid fa-check"></i> Active</span>
                <div class="fn-sec-icon">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h3>1. Cryptographic Password Hashing</h3>
                <p>
                    All passwords across Customers, Service Providers, and Administrators are secured using industry-standard <strong>Bcrypt algorithms</strong> with cryptographically strong salt generation.
                </p>
                <ul>
                    <li>Zero plaintext storage of customer or specialist credentials.</li>
                    <li>Automatic backward-compatible transparent hash upgrade on authentication.</li>
                    <li>Resistant to brute-force and dictionary attacks.</li>
                </ul>
            </div>

            <!-- 2. Parameterized SQL Injection Defense -->
            <div class="fn-sec-card">
                <span class="fn-sec-badge"><i class="fa-solid fa-check"></i> Active</span>
                <div class="fn-sec-icon">
                    <i class="fa-solid fa-database"></i>
                </div>
                <h3>2. SQL Injection Prevention</h3>
                <p>
                    100% of our database interactions utilize parameterized prepared statements via PHP MySQLi and PDO engines, eliminating database injection vectors.
                </p>
                <ul>
                    <li>Complete separation between SQL commands and user inputs.</li>
                    <li>Strict input sanitization across search queries, town selection, and chat messaging.</li>
                    <li>No string concatenation or raw interpolation.</li>
                </ul>
            </div>

            <!-- 3. Commission Escrow Safeguard -->
            <div class="fn-sec-card">
                <span class="fn-sec-badge"><i class="fa-solid fa-check"></i> Active</span>
                <div class="fn-sec-icon">
                    <i class="fa-solid fa-vault"></i>
                </div>
                <h3>3. Commission Escrow Model</h3>
                <p>
                    Your money stays safe until work is done. FixNow holds customer funds in platform escrow until the work order is marked completed to satisfaction.
                </p>
                <ul>
                    <li>10% platform protection fee covers arbitration and guarantee.</li>
                    <li>90% net specialist payout released upon successful task resolution.</li>
                    <li>Instant refunds if a technician fails to arrive.</li>
                </ul>
            </div>

            <!-- 4. PayHere Payment Gateway -->
            <div class="fn-sec-card">
                <span class="fn-sec-badge"><i class="fa-solid fa-check"></i> Active</span>
                <div class="fn-sec-icon">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <h3>4. Certified PayHere Gateway</h3>
                <p>
                    Financial transactions are handled through PayHere Sri Lanka, compliant with Central Bank regulations and international PCI-DSS security standards.
                </p>
                <ul>
                    <li>End-to-end 256-bit SSL encryption.</li>
                    <li>HMAC-SHA256 signature verification on payment notifications.</li>
                    <li>No credit/debit card numbers are ever stored on FIXNOW servers.</li>
                </ul>
            </div>

            <!-- 5. 4-Stage Specialist Vetting -->
            <div class="fn-sec-card">
                <span class="fn-sec-badge"><i class="fa-solid fa-check"></i> Active</span>
                <div class="fn-sec-icon">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h3>5. 4-Stage Specialist Vetting</h3>
                <p>
                    We never allow unverified workers into your home. Each specialist undergoes rigorous screening before receiving service dispatch privileges.
                </p>
                <ul>
                    <li>National Identity (NIC) verification and background checks.</li>
                    <li>Technical trade qualifications and skill assessment.</li>
                    <li>Onboarding registration fee & manual administrative approval.</li>
                    <li>Customer rating accountability—poor performers are deactivated.</li>
                </ul>
            </div>

            <!-- 6. Collision Prevention & Privacy -->
            <div class="fn-sec-card">
                <span class="fn-sec-badge"><i class="fa-solid fa-check"></i> Active</span>
                <div class="fn-sec-icon">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h3>6. Scheduling Collision Prevention</h3>
                <p>
                    Our smart booking engine automatically prevents double-booking and overlaps across specialist arrival windows.
                </p>
                <ul>
                    <li>Specific time-slots: 09:00 - 12:00, 13:00 - 16:00, 16:00 - 19:00.</li>
                    <li>Real-time availability validation prevents conflicting appointments.</li>
                    <li>Radial GPS distance calculation connects you to the closest specialist.</li>
                </ul>
            </div>
        </div>

        <!-- Vulnerability & Security Reporting Card -->
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 40px; margin-top: 40px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 24px;">
            <div style="max-width: 600px;">
                <span style="color: #0284c7; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em;">Responsible Disclosure</span>
                <h3 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-top: 6px; margin-bottom: 10px;">Security Researchers & Bug Reports</h3>
                <p style="color: #64748b; font-size: 15px; margin: 0; line-height: 1.6;">
                    If you believe you have found a security vulnerability or bug in FIXNOW services, please practice responsible disclosure and notify our dedicated security response team.
                </p>
            </div>
            <div>
                <a href="mailto:security@fixnow.lk" class="fn-btn fn-btn-primary" style="padding: 14px 28px; font-size: 15px;">
                    <i class="fa-solid fa-envelope"></i> Contact Security Team
                </a>
            </div>
        </div>

    </div>
</section>

<?php 
include './partials/footer.php'; 
?>
