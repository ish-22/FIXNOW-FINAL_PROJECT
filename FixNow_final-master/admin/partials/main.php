<?php 
require_once('../config/constant.php');
require_once('login-check.php');

// Determine current page for active nav item highlighting
$current_page = basename($_SERVER['PHP_SELF']);
$admin_username = isset($_SESSION['user']) ? $_SESSION['user'] : 'Administrator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNow | Admin Workspace</title>
    <link rel="shortcut icon" href="../assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    <!-- Font Awesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Modern Admin Design System -->
    <link rel="stylesheet" href="admin_css/style.css">
</head>
<body>

    <!-- Modern Sidebar Navigation -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="admin-sidebar-header">
            <a href="admin-index.php" class="admin-sidebar-brand">
                <div class="admin-brand-icon">
                    <i class="fa-solid fa-wrench"></i>
                </div>
                <div>
                    <div class="admin-brand-text">FIXNOW</div>
                    <span class="admin-brand-badge">Admin Suite</span>
                </div>
            </a>
        </div>

        <div class="admin-nav">
            <div class="admin-nav-group">Main Overview</div>
            <a href="admin-index.php" class="admin-nav-item <?php echo ($current_page == 'admin-index.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i> Dashboard
            </a>
            <a href="analyzing.php" class="admin-nav-item <?php echo ($current_page == 'analyzing.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-line"></i> Analytics &amp; Reports
            </a>

            <div class="admin-nav-group">Operations &amp; Escrow</div>
            <a href="bookings.php" class="admin-nav-item <?php echo ($current_page == 'bookings.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-calendar-check"></i> Bookings &amp; Escrow
            </a>
            <a href="manage-vendor.php" class="admin-nav-item <?php echo ($current_page == 'manage-vendor.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-gear"></i> Specialists &amp; Vetting
            </a>
            <a href="manage-invoice.php" class="admin-nav-item <?php echo ($current_page == 'manage-invoice.php' || $current_page == 'add-amount.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i> Invoices &amp; Subscriptions
            </a>

            <div class="admin-nav-group">Directory &amp; Content</div>
            <a href="manage-services.php" class="admin-nav-item <?php echo ($current_page == 'manage-services.php' || $current_page == 'add-services.php' || $current_page == 'update-services.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-list-check"></i> Services Catalog
            </a>
            <a href="manage-offers.php" class="admin-nav-item <?php echo ($current_page == 'manage-offers.php' || $current_page == 'add-offer.php' || $current_page == 'update-offer.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-tags"></i> Deals &amp; Offers
            </a>
            <a href="manage-dailyHacks.php" class="admin-nav-item <?php echo ($current_page == 'manage-dailyHacks.php' || $current_page == 'add-dailyHacks.php' || $current_page == 'update-dailyHacks.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-lightbulb"></i> DIY Daily Hacks
            </a>

            <div class="admin-nav-group">User &amp; Inquiries</div>
            <a href="manage-user.php" class="admin-nav-item <?php echo ($current_page == 'manage-user.php' || $current_page == 'add-admin.php' || $current_page == 'update-admin.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-users-gear"></i> Users &amp; Admins
            </a>
            <a href="manage-question.php" class="admin-nav-item <?php echo ($current_page == 'manage-question.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope-open-text"></i> Customer Inquiries
            </a>
        </div>

        <div class="admin-sidebar-footer">
            <div class="admin-user-pill">
                <div class="admin-avatar">
                    <?php echo strtoupper(substr($admin_username, 0, 1)); ?>
                </div>
                <div class="admin-user-info">
                    <div class="admin-user-name"><?php echo htmlspecialchars($admin_username); ?></div>
                    <div class="admin-user-role">Super Administrator</div>
                </div>
            </div>
            <a href="admin-logout.php" class="admin-logout-btn" title="Sign Out">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="admin-main-wrapper">
        <!-- Topbar -->
        <header class="admin-topbar">
            <div class="admin-page-title-area">
                <button type="button" class="admin-mobile-toggle" id="adminSidebarToggle" aria-label="Toggle Sidebar">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div class="admin-page-title">
                    <?php 
                    $page_titles = [
                        'admin-index.php' => 'Dashboard Overview',
                        'bookings.php' => 'Booking Orders & Escrow Management',
                        'manage-vendor.php' => 'Service Specialists & Vetting',
                        'manage-services.php' => 'Services Catalog Management',
                        'manage-offers.php' => 'Special Deals & Promotions',
                        'manage-invoice.php' => 'Vendor Invoices & Fee Collection',
                        'manage-user.php' => 'Customer & Administrator Accounts',
                        'manage-question.php' => 'Customer Inquiries & Support Messages',
                        'manage-dailyHacks.php' => 'Daily DIY Maintenance Tips',
                        'analyzing.php' => 'Platform Analytics & Revenue Insights',
                        'add-services.php' => 'Create New Service Item',
                        'update-services.php' => 'Edit Service Details',
                        'add-offer.php' => 'Publish New Promotion',
                        'add-admin.php' => 'Register New System Administrator',
                        'add-amount.php' => 'Configure Subscription Pricing',
                        'add-dailyHacks.php' => 'Add Daily Hack Article'
                    ];
                    echo isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Admin Control Panel';
                    ?>
                </div>
            </div>

            <div class="admin-topbar-actions">
                <a href="../index.php" target="_blank" class="admin-view-site-btn">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> View Live Site
                </a>
            </div>
        </header>

        <!-- Dynamic Content Canvas -->
        <main class="admin-content">
