<?php 
include './partials/header.php'; 

// Fetch services and towns for hero instant search
$hero_services = db_fetch_all($conn, "SELECT id, service_type FROM tbl_service ORDER BY service_type ASC");
$hero_towns = db_fetch_all($conn, "SELECT id, town_name FROM tbl_towns ORDER BY town_name ASC");
?>

<style>
/* Modern Homepage Specific Styles */
.fn-hero {
    padding: 60px 0 80px 0;
    background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%);
    position: relative;
    overflow: hidden;
}

.fn-hero-grid {
    display: grid;
    grid-template-columns: 1.15fr 0.85fr;
    align-items: center;
    gap: 40px;
}

.fn-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #e0f2fe;
    color: #0369a1;
    font-size: 13.5px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 50px;
    margin-bottom: 18px;
}

.fn-hero-title {
    font-size: 3.2rem;
    font-weight: 900;
    line-height: 1.15;
    color: #0f172a;
    margin-bottom: 18px;
}

.fn-hero-title span {
    color: #0284c7;
    background: linear-gradient(135deg, #0284c7, #2563eb);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.fn-hero-desc {
    font-size: 1.15rem;
    color: #475569;
    line-height: 1.7;
    margin-bottom: 28px;
}

.fn-hero-trust {
    display: flex;
    align-items: center;
    gap: 24px;
    margin-top: 30px;
    flex-wrap: wrap;
}

.fn-trust-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #334155;
}

.fn-trust-item i {
    color: #10b981;
    font-size: 16px;
}

.fn-hero-img-wrap {
    text-align: center;
    position: relative;
}

.fn-hero-img-wrap img {
    max-width: 100%;
    height: auto;
    border-radius: 16px;
    animation: fnFloat 4s ease-in-out infinite;
}

@keyframes fnFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

/* Feature Solution Cards */
.fn-solution-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 30px;
    margin-top: 40px;
}

.fn-solution-card {
    background: #ffffff;
    border-radius: 16px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
}

.fn-solution-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 30px rgba(2, 132, 199, 0.12);
    border-color: #bae6fd;
}

.fn-solution-img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

.fn-solution-body {
    padding: 26px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.fn-solution-body h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 10px;
}

.fn-solution-body p {
    font-size: 15px;
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 20px;
    flex-grow: 1;
}

.fn-link-arrow {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 700;
    font-size: 15px;
    color: #0284c7;
    text-decoration: none;
    transition: gap 0.2s ease;
}

.fn-link-arrow:hover {
    gap: 12px;
    color: #0369a1;
}

/* Specialist Banner CTA */
.fn-cta-section {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #ffffff;
    padding: 70px 0;
    border-radius: 24px;
    margin: 60px auto;
    position: relative;
    overflow: hidden;
}

.fn-cta-grid {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    align-items: center;
    gap: 40px;
}

.fn-cta-content h2 {
    font-size: 2.6rem;
    font-weight: 800;
    margin-bottom: 16px;
    line-height: 1.2;
}

.fn-cta-content p {
    font-size: 1.1rem;
    color: #94a3b8;
    line-height: 1.7;
    margin-bottom: 28px;
}

.fn-cta-actions {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

/* Value Props Grid */
.fn-props-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 25px;
    margin-top: 40px;
}

.fn-prop-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 28px 24px;
    text-align: center;
    transition: all 0.3s ease;
}

.fn-prop-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.06);
    border-color: #0284c7;
}

.fn-prop-icon {
    width: 65px;
    height: 65px;
    background: #e0f2fe;
    color: #0284c7;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    margin: 0 auto 18px auto;
}

.fn-prop-box h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 10px;
}

.fn-prop-box p {
    font-size: 14.5px;
    color: #64748b;
    line-height: 1.55;
    margin: 0;
}

/* Reviews Section */
.fn-reviews-section {
    padding: 70px 0;
    background: #f8fafc;
}

.fn-review-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 26px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 250px;
}

.fn-review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.fn-reviewer-name {
    font-weight: 700;
    font-size: 16px;
    color: #0f172a;
}

.fn-review-date {
    font-size: 12px;
    color: #94a3b8;
}

.fn-review-stars {
    color: #f59e0b;
    font-size: 13px;
    margin-bottom: 10px;
}

.fn-review-msg {
    font-size: 14px;
    color: #475569;
    line-height: 1.6;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
}

/* Mobile Media Queries */
@media (max-width: 991px) {
    .fn-hero-grid,
    .fn-cta-grid {
        grid-template-columns: 1fr;
        text-align: center;
    }
    .fn-hero-title {
        font-size: 2.5rem;
    }
    .fn-hero-trust {
        justify-content: center;
    }
    .fn-cta-actions {
        justify-content: center;
    }
}
</style>

<!-- ==============================================
     1. HERO SECTION WITH INSTANT SEARCH
     ============================================== -->
<section class="fn-hero" id="home">
    <div class="fn-container">
        <div class="fn-hero-grid">
            <div class="fn-hero-content">
                <span class="fn-hero-badge">
                    <i class="fa-solid fa-bolt"></i> Sri Lanka's #1 On-Demand Services Network
                </span>

                <h1 class="fn-hero-title">
                    Trusted Specialists, <br>
                    <span>Right at Your Doorstep.</span>
                </h1>

                <p class="fn-hero-desc">
                    Book verified electricians, plumbers, appliance technicians, and home care specialists. 
                    Transparent pricing, scheduled time slots, and 100% platform escrow protection.
                </p>

                <!-- Interactive Hero Search Widget -->
                <form action="vendors.php" method="GET" class="fn-hero-search-box">
                    <div class="fn-search-field">
                        <i class="fa-solid fa-wrench"></i>
                        <select name="service_type" aria-label="Select Service">
                            <option value="">All Services</option>
                            <?php foreach ($hero_services as $st): ?>
                                <option value="<?php echo $st['id']; ?>"><?php echo htmlspecialchars($st['service_type']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="fn-search-field">
                        <i class="fa-solid fa-location-dot"></i>
                        <select name="city" aria-label="Select City or Town">
                            <option value="">All Towns / Cities</option>
                            <?php foreach ($hero_towns as $tw): ?>
                                <option value="<?php echo $tw['id']; ?>"><?php echo htmlspecialchars($tw['town_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="fn-btn fn-btn-primary fn-btn-pill">
                        <i class="fa-solid fa-magnifying-glass"></i> Find Specialists
                    </button>
                </form>

                <!-- Trust Badges -->
                <div class="fn-hero-trust">
                    <div class="fn-trust-item"><i class="fa-solid fa-circle-check"></i> Vetted & Verified</div>
                    <div class="fn-trust-item"><i class="fa-solid fa-shield-halved"></i> Escrow Guarantee</div>
                    <div class="fn-trust-item"><i class="fa-solid fa-star"></i> 4.9/5 Rating</div>
                    <div class="fn-trust-item"><i class="fa-solid fa-clock"></i> Exact Time Slots</div>
                </div>
            </div>

            <div class="fn-hero-img-wrap">
                <img src="./assets/images/hero-banner2.png" alt="FIXNOW Specialists Network" />
            </div>
        </div>
    </div>
</section>


<!-- ==============================================
     2. CORE SOLUTIONS (SERVICES, OFFERS, HACKS)
     ============================================== -->
<section style="padding: 70px 0; background: #ffffff;">
    <div class="fn-container">
        <h2 class="fn-section-title">Everything You Need In One Place</h2>
        <p class="fn-section-subtitle">
            Experience complete convenience with verified professionals, promotional deals, and home maintenance tips.
        </p>

        <div class="fn-solution-grid">
            <!-- Services Card -->
            <div class="fn-solution-card">
                <img src="./assets/images/service.jpeg" class="fn-solution-img" alt="On-Demand Services" />
                <div class="fn-solution-body">
                    <h3>Specialist Services</h3>
                    <p>Discover skilled plumbers, electricians, AC technicians, carpenters, and cleaners near you.</p>
                    <a href="services.php" class="fn-link-arrow">
                        <span>Explore All Services</span> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Offers Card -->
            <div class="fn-solution-card">
                <img src="./assets/images/best offer.jpg" class="fn-solution-img" alt="Special Offers" />
                <div class="fn-solution-body">
                    <h3>Exclusive Deals & Offers</h3>
                    <p>Enjoy seasonal packages, bundle promotions, and limited-time discounts on trusted home repairs.</p>
                    <a href="offers.php" class="fn-link-arrow">
                        <span>View Current Deals</span> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Daily Hacks Card -->
            <div class="fn-solution-card">
                <img src="./assets/images/life hack.jpg" class="fn-solution-img" alt="Maintenance Hacks" />
                <div class="fn-solution-body">
                    <h3>Smart DIY & Maintenance</h3>
                    <p>Read expert advice, safety tips, and smart DIY hacks to keep your appliances and home running smoothly.</p>
                    <a href="dailyHacks.php" class="fn-link-arrow">
                        <span>Read Daily Hacks</span> <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ==============================================
     3. VALUE PROPOSITION / WHY CHOOSE US
     ============================================== -->
<section style="padding: 70px 0; background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0;">
    <div class="fn-container">
        <h2 class="fn-section-title">Why Thousands Choose FIXNOW</h2>
        <p class="fn-section-subtitle">
            We are redefining how home repairs and professional technical services are delivered across Sri Lanka.
        </p>

        <div class="fn-props-grid">
            <div class="fn-prop-box">
                <div class="fn-prop-icon"><i class="fa-solid fa-magnifying-glass-location"></i></div>
                <h4>Radial GPS Matching</h4>
                <p>Find the nearest available service provider with accurate distance filtering right in your town.</p>
            </div>

            <div class="fn-prop-box">
                <div class="fn-prop-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <h4>Commission Escrow</h4>
                <p>Your deposit remains 100% secured in platform escrow until the work order is successfully completed.</p>
            </div>

            <div class="fn-prop-box">
                <div class="fn-prop-icon"><i class="fa-solid fa-user-check"></i></div>
                <h4>Verified Specialists</h4>
                <p>Every technician is identity-checked, skill-verified, and rated by real clients in your neighborhood.</p>
            </div>

            <div class="fn-prop-box">
                <div class="fn-prop-icon"><i class="fa-solid fa-calendar-check"></i></div>
                <h4>Scheduled Arrival Slots</h4>
                <p>No more waiting around all day. Choose exact 2-hour arrival windows with automated collision prevention.</p>
            </div>
        </div>
    </div>
</section>


<!-- ==============================================
     4. SPECIALIST ONBOARDING CTA BANNER
     ============================================== -->
<div class="fn-container">
    <section class="fn-cta-section">
        <div class="fn-container">
            <div class="fn-cta-grid">
                <div class="fn-cta-content">
                    <h2>Are You a Skilled Specialist?</h2>
                    <p>
                        Expand your reach, get guaranteed client appointments, and receive on-time direct payouts. 
                        Join thousands of verified electricians, plumbers, and technicians on FIXNOW today.
                    </p>
                    <div class="fn-cta-actions">
                        <a href="signup.php" class="fn-btn fn-btn-primary fn-btn-pill" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fa-solid fa-user-plus"></i> Join as a Specialist
                        </a>
                        <a href="vendors.php" class="fn-btn fn-btn-outline fn-btn-pill" style="border-color: #ffffff; color: #ffffff !important;">
                            <i class="fa-solid fa-users"></i> Browse Specialists
                        </a>
                    </div>
                </div>

                <div style="text-align: center;">
                    <img src="./assets/images/features-job-search.png" alt="Specialist Network" style="max-width: 100%; height: auto; border-radius: 16px; box-shadow: 0 15px 30px rgba(0,0,0,0.3);" />
                </div>
            </div>
        </div>
    </section>
</div>


<!-- ==============================================
     5. USER REVIEWS CAROUSEL
     ============================================== -->
<section class="fn-reviews-section">
    <div class="fn-container">
        <h2 class="fn-section-title">What Our Customers Say</h2>
        <p class="fn-section-subtitle">Real experiences and verified reviews from homeowners across Sri Lanka.</p>

        <div class="swiper-container" style="padding-bottom: 20px;">
            <div class="swiper-wrapper">
                <?php
                $feedbacks = db_fetch_all($conn, "SELECT name, message, submitted_at FROM tbl_user_feedback ORDER BY submitted_at DESC LIMIT 8");
                if (!empty($feedbacks)) {
                    foreach ($feedbacks as $fb) {
                        $author = htmlspecialchars($fb['name']);
                        $text = htmlspecialchars($fb['message']);
                        $date = date('M d, Y', strtotime($fb['submitted_at']));
                        echo "
                        <div class='swiper-slide'>
                            <div class='fn-review-card'>
                                <div>
                                    <div class='fn-review-header'>
                                        <span class='fn-reviewer-name'>{$author}</span>
                                        <span class='fn-review-date'>{$date}</span>
                                    </div>
                                    <div class='fn-review-stars'>
                                        <i class='fa-solid fa-star'></i>
                                        <i class='fa-solid fa-star'></i>
                                        <i class='fa-solid fa-star'></i>
                                        <i class='fa-solid fa-star'></i>
                                        <i class='fa-solid fa-star'></i>
                                    </div>
                                    <p class='fn-review-msg'>\"{$text}\"</p>
                                </div>
                                <div style='display:flex; align-items:center; gap:6px; color:#10b981; font-size:12px; font-weight:700;'>
                                    <i class='fa-solid fa-circle-check'></i> Verified Booking
                                </div>
                            </div>
                        </div>";
                    }
                } else {
                    echo "<p style='text-align: center; color: #64748b; width:100%;'>No feedback submitted yet.</p>";
                }
                ?>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="user_feedback.php" class="fn-btn fn-btn-outline fn-btn-pill">
                <i class="fa-regular fa-comment-dots"></i> Share Your Experience
            </a>
        </div>
    </div>
</section>


<!-- ==============================================
     6. MODERN FLOATING CHATBOT WIDGET
     ============================================== -->
<button class="fn-chatbot-toggle" id="fnChatbotToggle" aria-label="Open Live Support Chat">
    <i class="fa-solid fa-comment-dots"></i>
</button>

<div class="fn-chatbot-window" id="fnChatbotWindow" style="display: none;">
    <div class="fn-chatbot-header">
        <h4><i class="fa-solid fa-headset"></i> FIXNOW Assistant</h4>
        <button type="button" class="fn-chatbot-close" id="fnChatbotClose">&times;</button>
    </div>

    <div class="fn-chatbot-body" id="fnChatbotMessages">
        <div class="fn-chat-bubble fn-chat-bubble-bot">
            👋 Hello! I am your FIXNOW Assistant. How can I help you today? Ask about booking a specialist, pricing, or registration!
        </div>
    </div>

    <div class="fn-chatbot-footer">
        <input type="text" id="fnChatbotInput" class="fn-chatbot-input" placeholder="Type your message..." />
        <button type="button" id="fnChatbotSend" class="fn-chatbot-send">
            <i class="fa-solid fa-paper-plane fa-sm"></i>
        </button>
    </div>
</div>

<script>
(function() {
    const toggleBtn = document.getElementById('fnChatbotToggle');
    const chatWindow = document.getElementById('fnChatbotWindow');
    const closeBtn = document.getElementById('fnChatbotClose');
    const sendBtn = document.getElementById('fnChatbotSend');
    const input = document.getElementById('fnChatbotInput');
    const messages = document.getElementById('fnChatbotMessages');

    if (toggleBtn && chatWindow) {
        toggleBtn.addEventListener('click', () => {
            chatWindow.style.display = (chatWindow.style.display === 'none') ? 'flex' : 'none';
            if (chatWindow.style.display === 'flex') input.focus();
        });

        closeBtn.addEventListener('click', () => {
            chatWindow.style.display = 'none';
        });

        function handleSend() {
            const text = input.value.trim();
            if (!text) return;

            // Append user bubble
            const uMsg = document.createElement('div');
            uMsg.className = 'fn-chat-bubble fn-chat-bubble-user';
            uMsg.textContent = text;
            messages.appendChild(uMsg);
            messages.scrollTop = messages.scrollHeight;
            input.value = '';

            // Simulate assistant response
            setTimeout(() => {
                const bMsg = document.createElement('div');
                bMsg.className = 'fn-chat-bubble fn-chat-bubble-bot';
                bMsg.textContent = getQuickReply(text);
                messages.appendChild(bMsg);
                messages.scrollTop = messages.scrollHeight;
            }, 600);
        }

        sendBtn.addEventListener('click', handleSend);
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') handleSend();
        });

        function getQuickReply(msg) {
            const lower = msg.toLowerCase();
            if (lower.includes('service') || lower.includes('find') || lower.includes('book')) {
                return 'You can easily find and book specialists by clicking "Services" in the header or using the search bar above!';
            }
            if (lower.includes('price') || lower.includes('cost') || lower.includes('fee')) {
                return 'All service prices are upfront with zero hidden charges. 10% platform fee is held in escrow until work completion.';
            }
            if (lower.includes('specialist') || lower.includes('join') || lower.includes('vendor') || lower.includes('register')) {
                return 'To register as a service specialist, click "Join Us" at the top and select "Register as Service Provider".';
            }
            return 'Thank you for your message! You can browse our verified specialists, check special offers, or contact our 24/7 support line anytime.';
        }
    }
})();
</script>

<?php include './partials/footer.php'; ?>
