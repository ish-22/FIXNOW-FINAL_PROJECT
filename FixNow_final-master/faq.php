<?php 
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
    color: #475569;
    font-size: 15px;
    line-height: 1.65;
    background: #fafbfc;
}

.fn-faq-item.active .fn-faq-answer {
    max-height: 400px;
    padding: 16px 24px 22px 24px;
    border-top: 1px dashed #e2e8f0;
}

.fn-faq-item.active .fn-faq-question i {
    transform: rotate(180deg);
}

.fn-filter-pill {
    padding: 8px 18px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid transparent;
    transition: all 0.2s ease;
}

.fn-filter-pill:hover, .fn-filter-pill.active {
    background: #0284c7;
    color: #ffffff;
}
</style>

<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 35px 0; text-align: center; border-bottom: 1px solid #e2e8f0;">
    <div class="fn-container" style="max-width: 800px;">
        <span class="fn-hero-badge" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
            <i class="fa-regular fa-circle-question" style="color: #0284c7;"></i> Help Center & Knowledge Base
        </span>
        <h1 class="fn-section-title" style="margin-bottom: 12px; font-size: 34px;">Frequently Asked Questions</h1>
        <p class="fn-section-subtitle" style="margin-bottom: 24px; color: #64748b; font-size: 16px;">
            Find immediate answers on booking specialists, escrow payment protection, arrival windows, and cancellations.
        </p>

        <!-- Live Search Bar -->
        <div style="max-width: 540px; margin: 0 auto; position: relative;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
            <input type="text" id="faqSearchInput" placeholder="Search questions (e.g. escrow, booking, refunds, security)..." 
                   style="width: 100%; padding: 14px 18px 14px 46px; border-radius: 50px; border: 1px solid #cbd5e1; outline: none; font-size: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); font-family: inherit;">
        </div>

        <!-- Category Filters -->
        <div style="display: flex; justify-content: center; flex-wrap: wrap; gap: 10px; margin-top: 24px;" id="faqCategoryFilters">
            <button type="button" class="fn-filter-pill active" data-category="all">All Questions</button>
            <button type="button" class="fn-filter-pill" data-category="booking">Booking & Slots</button>
            <button type="button" class="fn-filter-pill" data-category="escrow">Escrow & Pricing</button>
            <button type="button" class="fn-filter-pill" data-category="security">Trust & Safety</button>
            <button type="button" class="fn-filter-pill" data-category="vendors">For Specialists</button>
        </div>
    </div>
</div>

<section style="padding: 40px 0 80px 0;">
    <div class="fn-container">
        <div class="fn-faq-accordion" id="fnFaqAccordion">
            <!-- 1 -->
            <div class="fn-faq-item active" data-category="booking">
                <button type="button" class="fn-faq-question">
                    <span>How do I book a certified specialist on FIXNOW?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Simply browse our <strong>Services</strong> or <strong>Specialists</strong> directory, choose a verified technician near your town, and click "Book Now". You can pick your desired date and 2-hour arrival window with upfront pricing.
                </div>
            </div>

            <!-- 2 -->
            <div class="fn-faq-item" data-category="escrow">
                <button type="button" class="fn-faq-question">
                    <span>How does the 10% Escrow Protection work?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    When you confirm your booking, your payment is held securely in platform escrow. The specialist is dispatched, and funds (90% vendor payout) are only disbursed once the work order is marked completed to your full satisfaction.
                </div>
            </div>

            <!-- 3 -->
            <div class="fn-faq-item" data-category="security">
                <button type="button" class="fn-faq-question">
                    <span>How are technicians and service providers vetted?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Every service provider must undergo identity verification, submit proof of technical certifications, pay an onboarding registration fee, and maintain high customer feedback ratings to stay active on the platform.
                </div>
            </div>

            <!-- 4 -->
            <div class="fn-faq-item" data-category="booking">
                <button type="button" class="fn-faq-question">
                    <span>Can I reschedule or cancel my service booking?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Yes. Log into your account and navigate to <strong>My Bookings</strong>. You can view real-time task statuses and request updates or free cancellations up to 12 hours prior to the scheduled arrival time.
                </div>
            </div>

            <!-- 5 -->
            <div class="fn-faq-item" data-category="booking">
                <button type="button" class="fn-faq-question">
                    <span>Can I message or chat with the specialist before arrival?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Yes! FIXNOW provides direct messaging capabilities so you can share photos of the issue, confirm spare part requirements, and communicate directly with your assigned specialist.
                </div>
            </div>

            <!-- 6 -->
            <div class="fn-faq-item" data-category="security">
                <button type="button" class="fn-faq-question">
                    <span>What cybersecurity safeguards protect my account and password?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    FIXNOW enforces Bcrypt cryptographic password hashing across all roles, 100% parameterized SQL prepared statements to eliminate database injection attacks, and PayHere 256-bit SSL transaction encryption. Learn more on our dedicated <a href="security.php" style="color:#0284c7; font-weight:600;">Security Center</a>.
                </div>
            </div>

            <!-- 7 -->
            <div class="fn-faq-item" data-category="vendors">
                <button type="button" class="fn-faq-question">
                    <span>How can I register as a service specialist on FIXNOW?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Click "Join Us" in the header, select <strong>"Register as Service Provider"</strong>, fill out your trade category and operating towns, and complete the one-time registration payment to start receiving client appointments.
                </div>
            </div>

            <!-- 8 -->
            <div class="fn-faq-item" data-category="escrow">
                <button type="button" class="fn-faq-question">
                    <span>When and how does a service provider receive payment?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Once the customer confirms the job is finished and marks the task as completed, the 90% net payout is automatically credited to the provider's registered bank account via PayHere direct deposit.
                </div>
            </div>
        </div>

        <!-- Security Center Callout -->
        <div style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border: 1px solid #bae6fd; border-radius: 16px; padding: 30px; margin-top: 50px; text-align: center; max-width: 860px; margin-left: auto; margin-right: auto;">
            <div style="font-size: 28px; color: #0284c7; margin-bottom: 10px;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h3 style="font-size: 20px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Want in-depth details on our Platform Security?</h3>
            <p style="color: #475569; font-size: 15px; max-width: 580px; margin: 0 auto 20px auto;">
                Explore our full security architecture including Bcrypt encryption, SQL injection defense, escrow safeguards, and technician vetting.
            </p>
            <a href="security.php" class="fn-btn fn-btn-primary fn-btn-pill" style="padding: 12px 26px;">
                <i class="fa-solid fa-arrow-right"></i> Visit FixNow Security Center
            </a>
        </div>

        <div style="text-align: center; margin-top: 50px;">
            <p style="color: #64748b; font-size: 15px; margin-bottom: 16px;">Still have questions? Our support team is ready to help 24/7.</p>
            <a href="contact.php" class="fn-btn fn-btn-outline fn-btn-pill" style="padding: 12px 28px;">
                <i class="fa-solid fa-headset"></i> Contact Support Team
            </a>
        </div>
    </div>
</section>

<script>
// Accordion Toggle
document.querySelectorAll('.fn-faq-question').forEach(btn => {
    btn.addEventListener('click', function() {
        const item = this.parentElement;
        item.classList.toggle('active');
    });
});

// Category Filter
const categoryButtons = document.querySelectorAll('#faqCategoryFilters .fn-filter-pill');
const faqItems = document.querySelectorAll('.fn-faq-item');

categoryButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        categoryButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const cat = this.getAttribute('data-category');
        faqItems.forEach(item => {
            if (cat === 'all' || item.getAttribute('data-category') === cat) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Live Search Filter
const searchInput = document.getElementById('faqSearchInput');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        faqItems.forEach(item => {
            const text = item.innerText.toLowerCase();
            if (query === '' || text.includes(query)) {
                item.style.display = 'block';
                if (query !== '') {
                    item.classList.add('active');
                }
            } else {
                item.style.display = 'none';
            }
        });
    });
}
</script>

<?php include './partials/footer.php'; ?>
