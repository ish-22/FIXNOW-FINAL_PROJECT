<?php 
include './partials/header.php'; 

// Get filters securely
$search_name = isset($_GET['search_name']) ? trim($_GET['search_name']) : '';  
$service_type = isset($_GET['service_type']) ? intval($_GET['service_type']) : 0; 
$city = isset($_GET['city']) ? intval($_GET['city']) : 0; 

// Fetch filter options
$service_types_list = db_fetch_all($conn, "SELECT id, service_type FROM tbl_service ORDER BY service_type ASC");
$towns_list = db_fetch_all($conn, "SELECT id, town_name FROM tbl_towns ORDER BY town_name ASC");

// Build query
$params = [];
$types = "";

$search_query = "SELECT sp.*, s.service_type, t.town_name, t.latitude AS town_lat, t.longitude AS town_lng 
                 FROM tbl_service_providers sp 
                 JOIN tbl_service s ON s.id = sp.servicetype_id
                 LEFT JOIN tbl_towns t ON t.id = sp.town_id
                 WHERE sp.status = 'Confirmed'";

if ($search_name !== '') {
    $search_query .= " AND sp.full_name LIKE ?";
    $params[] = '%' . $search_name . '%';
    $types .= "s";
}

if ($service_type > 0) {
    $search_query .= " AND sp.servicetype_id = ?";
    $params[] = $service_type;
    $types .= "i";
}

if ($city > 0) {
    $search_query .= " AND sp.town_id = ?";
    $params[] = $city;
    $types .= "i";
}

$search_query .= " ORDER BY sp.id DESC";
$vendors = db_fetch_all($conn, $search_query, $params, $types);
$service_count = count($vendors);

// Fetch logged in customer location for radial distance
$cus_coords = null;
if (isset($_SESSION['customer_id'])) {
    $cus_id = intval($_SESSION['customer_id']);
    $cus_row = db_fetch_one($conn, "SELECT t.latitude, t.longitude FROM tbl_customer c LEFT JOIN tbl_towns t ON c.town_id = t.id WHERE c.id = ?", [$cus_id], "i");
    if ($cus_row && $cus_row['latitude']) {
        $cus_coords = $cus_row;
    }
}
?>

<style>
.fn-filter-bar {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    margin-bottom: 35px;
    display: flex;
    gap: 15px;
    align-items: center;
    flex-wrap: wrap;
}

.fn-filter-col {
    flex: 1;
    min-width: 200px;
}

.fn-filter-select,
.fn-filter-input {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    font-size: 14.5px;
    font-family: inherit;
    outline: none;
    background: #f8fafc;
    color: #334155;
    transition: all 0.2s;
}

.fn-filter-select:focus,
.fn-filter-input:focus {
    border-color: #0284c7;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
}
</style>

<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 20px 0;">
    <div class="fn-container">
        <h1 class="fn-section-title">Verified Specialists & Providers</h1>
        <p class="fn-section-subtitle">
            Find vetted local technicians, compare experience and distance, and schedule an appointment in seconds.
        </p>

        <!-- Filter Form Toolbar -->
        <form action="vendors.php" method="GET" class="fn-filter-bar">
            <div class="fn-filter-col">
                <input type="text" name="search_name" class="fn-filter-input" placeholder="Search by specialist name..." value="<?php echo htmlspecialchars($search_name); ?>" />
            </div>

            <div class="fn-filter-col">
                <select name="service_type" class="fn-filter-select" onchange="this.form.submit()">
                    <option value="">All Service Trades</option>
                    <?php foreach ($service_types_list as $st): ?>
                        <option value="<?php echo $st['id']; ?>" <?php echo ($service_type == $st['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($st['service_type']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="fn-filter-col">
                <select name="city" class="fn-filter-select" onchange="this.form.submit()">
                    <option value="">All Towns & Cities</option>
                    <?php foreach ($towns_list as $tw): ?>
                        <option value="<?php echo $tw['id']; ?>" <?php echo ($city == $tw['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tw['town_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="fn-btn fn-btn-primary" style="padding: 11px 20px;">
                    <i class="fa-solid fa-filter"></i> Apply
                </button>
                <?php if ($search_name || $service_type || $city): ?>
                    <a href="vendors.php" class="fn-btn fn-btn-outline" style="padding: 11px 16px;">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<section style="padding: 10px 0 80px 0;">
    <div class="fn-container">
        <?php if ($service_count > 0): ?>
            <div class="fn-grid-3">
                <?php foreach ($vendors as $vendor): 
                    $v_id = intval($vendor['id']);
                    $v_name = htmlspecialchars($vendor['full_name']);
                    $v_trade = htmlspecialchars($vendor['service_type']);
                    $v_town = htmlspecialchars($vendor['town_name'] ?? 'Local');
                    $v_img = !empty($vendor['vendor_image']) && file_exists('./images/vendor_images/' . $vendor['vendor_image']) 
                        ? './images/vendor_images/' . htmlspecialchars($vendor['vendor_image']) 
                        : './assets/images/features-job-search.png';

                    // Distance Calculation
                    $dist_km = null;
                    $lat = $vendor['latitude'] ?? $vendor['town_lat'];
                    $lng = $vendor['longitude'] ?? $vendor['town_lng'];
                    if ($cus_coords && $lat && $lng) {
                        $dist_km = calculate_distance_km($cus_coords['latitude'], $cus_coords['longitude'], $lat, $lng);
                    }
                ?>
                <div class="fn-card fn-vendor-card">
                    <span class="fn-vendor-badge-verified">
                        <i class="fa-solid fa-certificate"></i> Verified
                    </span>

                    <img src="<?php echo $v_img; ?>" alt="<?php echo $v_name; ?>" class="fn-vendor-avatar" />

                    <h3 class="fn-vendor-name"><?php echo $v_name; ?></h3>
                    <span class="fn-vendor-trade"><?php echo $v_trade; ?></span>

                    <div class="fn-vendor-location">
                        <i class="fa-solid fa-location-dot"></i> <?php echo $v_town; ?>, Sri Lanka
                    </div>

                    <?php if ($dist_km !== null): ?>
                        <div class="fn-vendor-distance">
                            <i class="fa-solid fa-location-arrow"></i> ~<?php echo $dist_km; ?> km from you
                        </div>
                    <?php else: ?>
                        <div style="height: 20px;"></div>
                    <?php endif; ?>

                    <div style="display: flex; gap: 8px; width: 100%; margin-top: 14px;">
                        <a href="booking.php?service_provider_id=<?php echo $v_id; ?>" class="fn-btn fn-btn-primary" style="flex: 1; padding: 10px 14px; font-size: 14px;">
                            <i class="fa-regular fa-calendar-plus"></i> Book Now
                        </a>
                        <a href="vendors-details.php?vendor_id=<?php echo $v_id; ?>" class="fn-btn fn-btn-outline" style="padding: 10px 14px; font-size: 14px;" title="View Profile">
                            <i class="fa-regular fa-id-badge"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 70px 20px; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1;">
                <i class="fa-solid fa-user-slash fa-3x" style="color: #94a3b8; margin-bottom: 16px;"></i>
                <h3 style="color: #1e293b; font-size: 1.5rem; margin-bottom: 8px;">No Specialists Found</h3>
                <p style="color: #64748b; margin-bottom: 20px;">We couldn't find any verified providers matching your exact criteria.</p>
                <a href="vendors.php" class="fn-btn fn-btn-primary">View All Available Specialists</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include './partials/footer.php'; ?>
