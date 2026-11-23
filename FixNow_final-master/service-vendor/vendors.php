<?php 
include_once('../config/constant.php');
$current_page = 'vendors.php';
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

$search_query = "SELECT sp.*, s.service_type, s.booking_price, t.town_name 
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
?>

<!-- Hero Section -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 20px 30px 20px; text-align: center; border-bottom: 1px solid #e2e8f0;">
    <div style="max-width: 800px; margin: 0 auto;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px; background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-user-gear"></i> Specialist Directory
        </span>
        <h1 style="font-size: 34px; font-weight: 900; color: #0f172a; margin: 0 0 10px 0;">Certified Trade Specialists</h1>
        <p style="color: #64748b; font-size: 16px; margin: 0 auto; max-width: 600px; line-height: 1.6;">
            Browse onboarded and accredited specialists across Colombo, Gampaha, Kandy, and islandwide.
        </p>
    </div>
</div>

<section style="padding: 30px 20px 80px 20px; max-width: 1200px; margin: 0 auto;">
    
    <!-- Filter Bar Form -->
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 22px 26px; box-shadow: 0 4px 16px rgba(0,0,0,0.03); margin-bottom: 35px;">
        <form action="vendors.php" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) 130px; gap: 14px; align-items: end;">
            <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #475569; margin-bottom: 6px;">Specialist Name</label>
                <input type="text" name="search_name" value="<?php echo htmlspecialchars($search_name); ?>" placeholder="Search by name..." 
                       style="width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; font-family: inherit;" />
            </div>

            <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #475569; margin-bottom: 6px;">Trade Specialty</label>
                <select name="service_type" style="width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; font-family: inherit; background: #ffffff;">
                    <option value="0">All Specialties</option>
                    <?php foreach ($service_types_list as $st): ?>
                        <option value="<?php echo $st['id']; ?>" <?php echo ($service_type == $st['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($st['service_type']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label style="display: block; font-size: 12.5px; font-weight: 700; color: #475569; margin-bottom: 6px;">Coverage Town</label>
                <select name="city" style="width: 100%; box-sizing: border-box; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; font-family: inherit; background: #ffffff;">
                    <option value="0">All Towns</option>
                    <?php foreach ($towns_list as $t): ?>
                        <option value="<?php echo $t['id']; ?>" <?php echo ($city == $t['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($t['town_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button type="submit" style="width: 100%; background: #0284c7; color: #ffffff; border: none; padding: 11px 18px; border-radius: 8px; font-size: 14.5px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 6px;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Results Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px;">
        <h2 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;">
            Available Specialists (<?php echo $service_count; ?>)
        </h2>
        <?php if ($search_name !== '' || $service_type > 0 || $city > 0): ?>
            <a href="vendors.php" style="color: #64748b; font-size: 13.5px; text-decoration: none; font-weight: 600;">
                <i class="fa-solid fa-xmark"></i> Clear Filters
            </a>
        <?php endif; ?>
    </div>

    <!-- Vendors Grid -->
    <?php if (!empty($vendors)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
            <?php foreach ($vendors as $v): 
                $v_id = $v['id'];
                $v_img_name = basename($v['vendor_image'] ?? '');
                $v_img = (!empty($v['vendor_image']) && file_exists('../images/vendor_images/' . $v_img_name))
                    ? '../images/vendor_images/' . htmlspecialchars($v_img_name)
                    : '../images/vendor_images/default.jpg';
                
                // Fetch rating
                $r_data = db_fetch_one($conn, "SELECT AVG(rating) AS avg_r, COUNT(*) AS cnt FROM tbl_feedback WHERE vendor_id = ?", [$v_id], "i");
                $avg_r = ($r_data && $r_data['avg_r']) ? round(floatval($r_data['avg_r']), 1) : 5.0;
                $review_cnt = ($r_data && $r_data['cnt']) ? intval($r_data['cnt']) : 0;
            ?>
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 18px; padding: 24px; box-shadow: 0 4px 16px rgba(0,0,0,0.02); display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 25px rgba(2,132,199,0.08)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 16px rgba(0,0,0,0.02)';">
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                        <img src="<?php echo $v_img; ?>" alt="<?php echo htmlspecialchars($v['full_name']); ?>" 
                             style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2.5px solid #0284c7;" />
                        <div>
                            <span style="font-size: 11.5px; font-weight: 800; color: #0284c7; background: #e0f2fe; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                <?php echo htmlspecialchars($v['service_type']); ?>
                            </span>
                            <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin: 4px 0 2px 0;">
                                <?php echo htmlspecialchars($v['full_name']); ?>
                            </h3>
                            <div style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: #f59e0b;">
                                <i class="fa-solid fa-star"></i>
                                <strong style="color: #0f172a;"><?php echo $avg_r; ?></strong>
                                <span style="color: #94a3b8;">(<?php echo $review_cnt; ?>)</span>
                            </div>
                        </div>
                    </div>

                    <div style="font-size: 13.5px; color: #64748b; line-height: 1.6; margin-bottom: 18px; display: flex; flex-direction: column; gap: 6px;">
                        <div>
                            <i class="fa-solid fa-location-dot" style="color: #0284c7; width: 16px;"></i>
                            <span><?php echo htmlspecialchars($v['town_name'] ?? 'Sri Lanka'); ?></span>
                        </div>
                        <div>
                            <i class="fa-solid fa-briefcase" style="color: #0284c7; width: 16px;"></i>
                            <span>Experience: <?php echo htmlspecialchars($v['experience'] ?? 'Experienced'); ?></span>
                        </div>
                        <div>
                            <i class="fa-solid fa-tag" style="color: #0284c7; width: 16px;"></i>
                            <span>Rates: From LKR <?php echo number_format(floatval($v['booking_price']), 2); ?></span>
                        </div>
                    </div>

                    <div style="margin-top: auto; padding-top: 14px; border-top: 1px dashed #e2e8f0;">
                        <a href="vendors-details.php?vendor_id=<?php echo $v_id; ?>" style="display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%; padding: 10px; font-size: 13.5px; font-weight: 700; color: #0284c7; border: 1.5px solid #0284c7; border-radius: 50px; text-decoration: none; box-sizing: border-box;">
                            View Profile &amp; Reviews <i class="fa-solid fa-arrow-right fa-xs"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 70px 20px; background: #ffffff; border-radius: 18px; border: 1px dashed #cbd5e1;">
            <i class="fa-solid fa-user-slash fa-3x" style="color: #94a3b8; margin-bottom: 16px;"></i>
            <h3 style="font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 6px;">No Specialists Found</h3>
            <p style="color: #64748b; margin-bottom: 20px;">Try adjusting your search criteria or clearing filters.</p>
            <a href="vendors.php" style="background: #0284c7; color: #ffffff; padding: 10px 24px; border-radius: 50px; text-decoration: none; font-weight: 700;">Reset Filters</a>
        </div>
    <?php endif; ?>

</section>

<?php 
include './partials/footer.php'; 
?>
