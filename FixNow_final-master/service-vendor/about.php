<?php 
include_once('../config/constant.php');
$current_page = 'about.php';
include './partials/header.php'; 
?>

<!-- Hero Banner -->
<section style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 60px 20px 40px 20px; border-bottom: 1px solid #e2e8f0; text-align: center;">
    <div style="max-width: 960px; margin: 0 auto;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 18px; background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-screwdriver-wrench"></i> Specialist Portal &amp; Platform Mission
        </span>
        <h1 style="font-size: 38px; line-height: 1.25; margin-bottom: 18px; color: #0f172a; font-weight: 900;">
            Empowering Skilled Trade Professionals Across Sri Lanka
        </h1>
        <p style="font-size: 16.5px; max-width: 720px; margin: 0 auto 30px auto; color: #475569; line-height: 1.7;">
            FIXNOW bridges the gap between quality-conscious homeowners and certified, background-checked specialists through transparent escrow protection, smart time-slot scheduling, and verified ratings.
        </p>

        <!-- Trust Stats Strip -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 18px; max-width: 820px; margin: 0 auto; text-align: center;">
            <div style="background: #ffffff; padding: 20px; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div style="font-size: 28px; font-weight: 800; color: #0284c7;">1,200+</div>
                <div style="font-size: 13.5px; color: #64748b; font-weight: 600; margin-top: 4px;">Completed Jobs</div>
            </div>
            <div style="background: #ffffff; padding: 20px; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div style="font-size: 28px; font-weight: 800; color: #0284c7;">100%</div>
                <div style="font-size: 13.5px; color: #64748b; font-weight: 600; margin-top: 4px;">Escrow Protected</div>
            </div>
            <div style="background: #ffffff; padding: 20px; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div style="font-size: 28px; font-weight: 800; color: #0284c7;">25+</div>
                <div style="font-size: 13.5px; color: #64748b; font-weight: 600; margin-top: 4px;">Districts &amp; Towns</div>
            </div>
            <div style="background: #ffffff; padding: 20px; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                <div style="font-size: 28px; font-weight: 800; color: #0284c7;">99.4%</div>
                <div style="font-size: 13.5px; color: #64748b; font-weight: 600; margin-top: 4px;">Satisfaction Rate</div>
            </div>
        </div>
    </div>
</section>

<!-- Vision & Mission Section -->
<section style="padding: 60px 20px; background: #ffffff;">
    <div style="max-width: 1050px; margin: 0 auto;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
            <!-- Vision Card -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 36px; border-top: 4px solid #0284c7; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                <div style="width: 52px; height: 52px; border-radius: 14px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 20px;">
                    <i class="fa-solid fa-compass"></i>
                </div>
                <h3 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Our Vision</h3>
                <p style="font-size: 15.5px; color: #475569; line-height: 1.7; margin: 0;">
                    To establish Sri Lanka's most dependable and accessible on-demand home service ecosystem. We envision a future where finding a skilled electrician, AC technician, plumber, or carpenter is as effortless, safe, and transparent as booking a cab.
                </p>
            </div>

            <!-- Mission Card -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; padding: 36px; border-top: 4px solid #10b981; box-shadow: 0 4px 16px rgba(0,0,0,0.02);">
                <div style="width: 52px; height: 52px; border-radius: 14px; background: #dcfce7; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 20px;">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h3 style="font-size: 22px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Our Mission</h3>
                <p style="font-size: 15.5px; color: #475569; line-height: 1.7; margin: 0;">
                    To equip domestic trade workers and independent technicians with digital identity, verified reputations, and direct access to high-value homeowners, while protecting clients with secure escrow holding.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Values Pillars -->
<section style="padding: 50px 20px 80px 20px; background: #f8fafc; border-top: 1px solid #e2e8f0;">
    <div style="max-width: 1050px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 40px;">
            <h2 style="font-size: 28px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">The FixNow Difference</h2>
            <p style="color: #64748b; font-size: 15px;">Built on three fundamental commitments to our users.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            <div style="background: #ffffff; padding: 28px; border-radius: 16px; border: 1px solid #e2e8f0;">
                <div style="color: #0284c7; font-size: 24px; margin-bottom: 14px;"><i class="fa-solid fa-shield-halved"></i></div>
                <h4 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">100% Escrow Protection</h4>
                <p style="font-size: 14px; color: #64748b; line-height: 1.6; margin: 0;">Customer payments remain safely locked in PayHere escrow until the specialist completes the work order to full satisfaction.</p>
            </div>
            <div style="background: #ffffff; padding: 28px; border-radius: 16px; border: 1px solid #e2e8f0;">
                <div style="color: #10b981; font-size: 24px; margin-bottom: 14px;"><i class="fa-solid fa-id-card-clip"></i></div>
                <h4 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Vetted Specialists</h4>
                <p style="font-size: 14px; color: #64748b; line-height: 1.6; margin: 0;">Every service provider undergoes national identity checks, trade credential verification, and ongoing customer rating audits.</p>
            </div>
            <div style="background: #ffffff; padding: 28px; border-radius: 16px; border: 1px solid #e2e8f0;">
                <div style="color: #f59e0b; font-size: 24px; margin-bottom: 14px;"><i class="fa-solid fa-bolt"></i></div>
                <h4 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 8px;">Fast Dispatch &amp; Live Tracking</h4>
                <p style="font-size: 14px; color: #64748b; line-height: 1.6; margin: 0;">Instant appointment scheduling, confirmed time windows, and live communication between technicians and homeowners.</p>
            </div>
        </div>
    </div>
</section>

<?php 
include './partials/footer.php'; 
?>
