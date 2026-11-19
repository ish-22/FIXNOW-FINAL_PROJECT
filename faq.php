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
</style>

<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 30px 0; text-align: center;">
    <div class="fn-container">
        <span class="fn-hero-badge">
            <i class="fa-regular fa-circle-question"></i> Help Center & Knowledge Base
        </span>
        <h1 class="fn-section-title">Frequently Asked Questions</h1>
        <p class="fn-section-subtitle">
            Find immediate answers on booking specialists, escrow payment protection, arrival windows, and cancellations.
        </p>
    </div>
</div>

<section style="padding: 10px 0 80px 0;">
    <div class="fn-container">
        <div class="fn-faq-accordion" id="fnFaqAccordion">
            <div class="fn-faq-item active">
                <button type="button" class="fn-faq-question">
                    <span>How do I book a certified specialist on FIXNOW?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Simply browse our <strong>Services</strong> or <strong>Specialists</strong> directory, choose a verified technician near your town, and click "Book Now". You can pick your desired date and 2-hour arrival window with upfront pricing.
                </div>
            </div>

            <div class="fn-faq-item">
                <button type="button" class="fn-faq-question">
                    <span>How does the 10% Escrow Protection work?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    When you confirm your booking, your payment is held securely in platform escrow. The specialist is dispatched, and funds are only disbursed once the work order is marked completed to your full satisfaction.
                </div>
            </div>

            <div class="fn-faq-item">
                <button type="button" class="fn-faq-question">
                    <span>How are technicians and service providers vetted?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Every service provider must undergo identity verification, submit proof of technical certifications, pay an onboarding registration fee, and maintain high customer feedback ratings to stay active on the platform.
                </div>
            </div>

            <div class="fn-faq-item">
                <button type="button" class="fn-faq-question">
                    <span>Can I reschedule or cancel my service booking?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Yes. Log into your account and navigate to <strong>My Bookings</strong>. You can view real-time task statuses and request updates or cancellations before the specialist arrives.
                </div>
            </div>

            <div class="fn-faq-item">
                <button type="button" class="fn-faq-question">
                    <span>Can I message or chat with the specialist before arrival?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Yes! FIXNOW provides direct messaging capabilities so you can share photos of the issue, confirm spare part requirements, and communicate directly with your assigned specialist.
                </div>
            </div>

            <div class="fn-faq-item">
                <button type="button" class="fn-faq-question">
                    <span>How can I register as a service specialist on FIXNOW?</span>
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="fn-faq-answer">
                    Click "Join Us" in the header, select <strong>"Register as Service Provider"</strong>, fill out your trade category and operating towns, and complete the one-time registration payment to start receiving client appointments.
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 50px;">
            <p style="color: #64748b; font-size: 15px; margin-bottom: 16px;">Still have questions? Our support team is ready to help 24/7.</p>
            <a href="contact.php" class="fn-btn fn-btn-primary fn-btn-pill">
                <i class="fa-solid fa-headset"></i> Contact Support Team
            </a>
        </div>
    </div>
</section>

<script>
document.querySelectorAll('.fn-faq-question').forEach(btn => {
    btn.addEventListener('click', function() {
        const item = this.parentElement;
        item.classList.toggle('active');
    });
});
</script>

<?php include './partials/footer.php'; ?>
