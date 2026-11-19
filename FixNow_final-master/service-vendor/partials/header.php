<?php
// Start session and include configuration
include_once('../config/constant.php');
$current_page = basename($_SERVER['PHP_SELF']);
$vendor_id = $_SESSION['vendor_id'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FIXNOW Specialist Portal - Dashboard & Work Orders</title>
    <link rel="shortcut icon" href="../assets/images/fixnow-logo-icon.png" type="image/svg+xml" />
    
    <!-- Google Fonts & Font Awesome 6 Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../assets/css/fixnow-modern.css" />

    <style>
        /* ========================================================
           FIXNOW SPECIALIST PORTAL MODERN HEADER STYLES
           ======================================================== */
        :root {
            --fn-primary: #0284c7;
            --fn-primary-hover: #0369a1;
            --fn-text-dark: #0f172a;
            --fn-text-muted: #475569;
            --fn-border: #e2e8f0;
            --fn-bg-hover: #f0f9ff;
        }

        header.header,
        header.fn-vendor-header {
            all: initial !important;
            box-sizing: border-box !important;
            font-family: 'League Spartan', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            position: sticky !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 74px !important;
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(14px) !important;
            -webkit-backdrop-filter: blur(14px) !important;
            border-bottom: 1px solid var(--fn-border) !important;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.06) !important;
            z-index: 99999 !important;
            display: block !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .fn-nav-wrapper {
            box-sizing: border-box !important;
            max-width: 1320px !important;
            height: 100% !important;
            margin: 0 auto !important;
            padding: 0 24px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 20px !important;
        }

        /* Brand Logo with Specialist Tag */
        .fn-brand-link {
            all: unset !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 10px !important;
            text-decoration: none !important;
            transition: opacity 0.2s ease !important;
        }

        .fn-brand-link:hover {
            opacity: 0.85 !important;
        }

        .fn-brand-link img {
            height: 40px !important;
            width: auto !important;
            display: block !important;
            object-fit: contain !important;
        }

        .fn-badge-specialist {
            background: #e0f2fe !important;
            color: #0369a1 !important;
            font-size: 11.5px !important;
            font-weight: 800 !important;
            padding: 3px 8px !important;
            border-radius: 6px !important;
            letter-spacing: 0.5px !important;
            text-transform: uppercase !important;
            border: 1px solid #bae6fd !important;
        }

        /* Navigation Links List */
        .fn-nav-links {
            all: unset !important;
            box-sizing: border-box !important;
            display: flex !important;
            align-items: center !important;
            gap: 4px !important;
            list-style: none !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .fn-nav-item {
            all: unset !important;
            display: block !important;
            position: relative !important;
        }

        .fn-nav-a {
            all: unset !important;
            box-sizing: border-box !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            color: var(--fn-text-muted) !important;
            font-size: 15px !important;
            font-weight: 600 !important;
            padding: 8px 12px !important;
            border-radius: 8px !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            white-space: nowrap !important;
        }

        .fn-nav-a i {
            font-size: 13.5px !important;
            opacity: 0.7 !important;
            transition: opacity 0.2s ease !important;
        }

        .fn-nav-a:hover {
            color: var(--fn-primary) !important;
            background: var(--fn-bg-hover) !important;
        }

        .fn-nav-a:hover i {
            opacity: 1 !important;
        }

        .fn-nav-a.active-page {
            color: var(--fn-primary) !important;
            background: #e0f2fe !important;
            font-weight: 700 !important;
        }

        .fn-nav-a.active-page i {
            opacity: 1 !important;
            color: var(--fn-primary) !important;
        }

        /* Action Buttons Area */
        .fn-auth-actions {
            box-sizing: border-box !important;
            display: flex !important;
            align-items: center !important;
            gap: 10px !important;
        }

        .fn-user-chip {
            all: unset !important;
            box-sizing: border-box !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            background: #f8fafc !important;
            border: 1.5px solid var(--fn-border) !important;
            padding: 6px 14px !important;
            border-radius: 50px !important;
            color: var(--fn-text-dark) !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            white-space: nowrap !important;
        }

        .fn-user-chip:hover {
            border-color: var(--fn-primary) !important;
            color: var(--fn-primary) !important;
            background: var(--fn-bg-hover) !important;
        }

        .fn-user-chip i {
            color: var(--fn-primary) !important;
            font-size: 15px !important;
        }

        .fn-btn-public {
            all: unset !important;
            box-sizing: border-box !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            color: #475569 !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            padding: 7px 12px !important;
            border-radius: 8px !important;
            border: 1px dashed #cbd5e1 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            white-space: nowrap !important;
        }

        .fn-btn-public:hover {
            color: var(--fn-primary) !important;
            border-color: var(--fn-primary) !important;
            background: #f8fafc !important;
        }

        .fn-btn-logout {
            all: unset !important;
            box-sizing: border-box !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            color: #dc2626 !important;
            background: #fef2f2 !important;
            border: 1px solid #fee2e2 !important;
            padding: 7px 14px !important;
            border-radius: 8px !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: all 0.2s ease !important;
            white-space: nowrap !important;
        }

        .fn-btn-logout:hover {
            background: #dc2626 !important;
            color: #ffffff !important;
            border-color: #dc2626 !important;
        }

        /* Mobile Hamburger Toggle */
        .fn-mobile-toggle {
            all: unset !important;
            box-sizing: border-box !important;
            display: none !important;
            cursor: pointer !important;
            padding: 8px !important;
            border-radius: 8px !important;
            color: var(--fn-text-dark) !important;
            font-size: 22px !important;
            transition: background 0.2s ease !important;
        }

        .fn-mobile-toggle:hover {
            background: #f1f5f9 !important;
        }

        /* Mobile Drawer */
        .fn-mobile-drawer {
            display: none;
            box-sizing: border-box !important;
            position: absolute !important;
            top: 74px !important;
            left: 0 !important;
            width: 100% !important;
            background: #ffffff !important;
            border-bottom: 2px solid var(--fn-border) !important;
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1) !important;
            padding: 20px !important;
            flex-direction: column !important;
            gap: 8px !important;
            z-index: 99998 !important;
        }

        .fn-mobile-drawer.fn-drawer-open {
            display: flex !important;
        }

        .fn-mobile-drawer .fn-nav-a {
            width: 100% !important;
            padding: 12px 16px !important;
            border-bottom: 1px solid #f1f5f9 !important;
        }

        .fn-mobile-drawer .fn-auth-actions {
            flex-direction: column !important;
            width: 100% !important;
            margin-top: 10px !important;
            gap: 10px !important;
        }

        .fn-mobile-drawer .fn-user-chip,
        .fn-mobile-drawer .fn-btn-public,
        .fn-mobile-drawer .fn-btn-logout {
            width: 100% !important;
            justify-content: center !important;
            text-align: center !important;
            padding: 12px 16px !important;
        }

        /* Responsive Breakpoint */
        @media (max-width: 1024px) {
            .fn-desktop-nav {
                display: none !important;
            }
            .fn-desktop-actions {
                display: none !important;
            }
            .fn-mobile-toggle {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
        }
    </style>
</head>

<body id="top">

    <!-- MODERN SPECIALIST PORTAL HEADER -->
    <header class="header fn-vendor-header" data-header>
        <div class="fn-nav-wrapper">
            <!-- Brand Logo with Specialist Tag -->
            <a href="index.php" class="fn-brand-link" title="FIXNOW Specialist Dashboard">
                <img src="../assets/images/fixnow-logo.png" alt="FIXNOW" />
                <span class="fn-badge-specialist">Specialist</span>
            </a>

            <!-- Desktop Navigation Links -->
            <nav class="fn-desktop-nav">
                <ul class="fn-nav-links">
                    <li class="fn-nav-item">
                        <a href="index.php" class="fn-nav-a <?php echo ($current_page == 'index.php') ? 'active-page' : ''; ?>">
                            <i class="fa-solid fa-chart-line"></i> Dashboard
                        </a>
                    </li>
                    <li class="fn-nav-item">
                        <a href="services.php" class="fn-nav-a <?php echo ($current_page == 'services.php') ? 'active-page' : ''; ?>">
                            <i class="fa-solid fa-screwdriver-wrench"></i> Services
                        </a>
                    </li>
                    <li class="fn-nav-item">
                        <a href="offers.php" class="fn-nav-a <?php echo ($current_page == 'offers.php') ? 'active-page' : ''; ?>">
                            <i class="fa-solid fa-tags"></i> Offers
                        </a>
                    </li>
                    <?php if (isset($_SESSION['vendor_id'])): ?>
                    <li class="fn-nav-item">
                        <a href="customer-bookings.php" class="fn-nav-a <?php echo ($current_page == 'customer-bookings.php') ? 'active-page' : ''; ?>">
                            <i class="fa-regular fa-calendar-check"></i> Bookings
                        </a>
                    </li>
                    <li class="fn-nav-item">
                        <a href="invoice.php" class="fn-nav-a <?php echo ($current_page == 'invoice.php' || $current_page == 'payment.php') ? 'active-page' : ''; ?>">
                            <i class="fa-solid fa-receipt"></i> Subscription
                        </a>
                    </li>
                    <li class="fn-nav-item">
                        <a href="chat_serviceProvider.php" class="fn-nav-a <?php echo ($current_page == 'chat_serviceProvider.php') ? 'active-page' : ''; ?>">
                            <i class="fa-regular fa-comments"></i> Messages
                        </a>
                    </li>
                    <li class="fn-nav-item">
                        <a href="feedback.php?service_provider_id=<?php echo $vendor_id; ?>" class="fn-nav-a <?php echo ($current_page == 'feedback.php') ? 'active-page' : ''; ?>">
                            <i class="fa-regular fa-star"></i> Reviews
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>

            <!-- Desktop User Actions -->
            <div class="fn-auth-actions fn-desktop-actions">
                <?php if (isset($_SESSION['vendor_id'])): ?>
                    <a href="profile.php" class="fn-user-chip" title="Specialist Profile">
                        <i class="fa-regular fa-circle-user"></i>
                        <span><?php echo htmlspecialchars($_SESSION['vendor_name'] ?? 'Specialist'); ?></span>
                    </a>
                    <a href="../index.php" class="fn-btn-public" title="View Public Marketplace">
                        <i class="fa-solid fa-globe"></i> Marketplace
                    </a>
                    <a href="logout.php" class="fn-btn-logout" title="Sign Out">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="../signin.php" class="fn-nav-a">Sign In</a>
                    <a href="../signup.php" class="fn-nav-a">Register</a>
                <?php endif; ?>
            </div>

            <!-- Mobile Hamburger Toggle -->
            <button type="button" class="fn-mobile-toggle" id="fnVendorMobileToggle" aria-label="Toggle Navigation">
                <i class="fa-solid fa-bars" id="fnVendorToggleIcon"></i>
            </button>
        </div>

        <!-- Mobile Dropdown Drawer -->
        <div class="fn-mobile-drawer" id="fnVendorMobileDrawer">
            <a href="index.php" class="fn-nav-a <?php echo ($current_page == 'index.php') ? 'active-page' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i> Dashboard
            </a>
            <a href="services.php" class="fn-nav-a <?php echo ($current_page == 'services.php') ? 'active-page' : ''; ?>">
                <i class="fa-solid fa-screwdriver-wrench"></i> Services
            </a>
            <a href="offers.php" class="fn-nav-a <?php echo ($current_page == 'offers.php') ? 'active-page' : ''; ?>">
                <i class="fa-solid fa-tags"></i> Offers
            </a>
            <?php if (isset($_SESSION['vendor_id'])): ?>
            <a href="customer-bookings.php" class="fn-nav-a <?php echo ($current_page == 'customer-bookings.php') ? 'active-page' : ''; ?>">
                <i class="fa-regular fa-calendar-check"></i> Bookings
            </a>
            <a href="invoice.php" class="fn-nav-a <?php echo ($current_page == 'invoice.php' || $current_page == 'payment.php') ? 'active-page' : ''; ?>">
                <i class="fa-solid fa-receipt"></i> Subscription
            </a>
            <a href="chat_serviceProvider.php" class="fn-nav-a <?php echo ($current_page == 'chat_serviceProvider.php') ? 'active-page' : ''; ?>">
                <i class="fa-regular fa-comments"></i> Messages
            </a>
            <a href="feedback.php?service_provider_id=<?php echo $vendor_id; ?>" class="fn-nav-a <?php echo ($current_page == 'feedback.php') ? 'active-page' : ''; ?>">
                <i class="fa-regular fa-star"></i> Reviews
            </a>

            <div class="fn-auth-actions">
                <a href="profile.php" class="fn-user-chip">
                    <i class="fa-regular fa-circle-user"></i>
                    <span><?php echo htmlspecialchars($_SESSION['vendor_name'] ?? 'Specialist'); ?></span>
                </a>
                <a href="../index.php" class="fn-btn-public">
                    <i class="fa-solid fa-globe"></i> Marketplace
                </a>
                <a href="logout.php" class="fn-btn-logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </a>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <script>
    (function() {
        const toggleBtn = document.getElementById('fnVendorMobileToggle');
        const drawer = document.getElementById('fnVendorMobileDrawer');
        const icon = document.getElementById('fnVendorToggleIcon');

        if (toggleBtn && drawer) {
            toggleBtn.addEventListener('click', function() {
                drawer.classList.toggle('fn-drawer-open');
                if (drawer.classList.contains('fn-drawer-open')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-xmark');
                } else {
                    icon.classList.remove('fa-xmark');
                    icon.classList.add('fa-bars');
                }
            });
        }
    })();
    </script>
