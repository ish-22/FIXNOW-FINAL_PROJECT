<?php 
include('../config/constant.php'); 
include('partials/login-check.php');

$msg = '';
$err = '';

// Handle Add District
if (isset($_POST['submit-district'])) {
    $district_name = trim($_POST['district_name'] ?? '');
    if (!empty($district_name)) {
        $check = db_fetch_one($conn, "SELECT id FROM tbl_districts WHERE district_name = ?", [$district_name], "s");
        if ($check) {
            $err = "District '{$district_name}' already exists.";
        } else {
            db_execute($conn, "INSERT INTO tbl_districts (district_name) VALUES (?)", [$district_name], "s");
            header("Location: add-locations.php?status=success&action=add-district");
            exit();
        }
    }
}

// Handle Add Town
if (isset($_POST['submit-town'])) {
    $district_id = intval($_POST['district_id'] ?? 0);
    $town_name = trim($_POST['town_name'] ?? '');
    $lat = !empty($_POST['latitude']) ? floatval($_POST['latitude']) : NULL;
    $lng = !empty($_POST['longitude']) ? floatval($_POST['longitude']) : NULL;

    if (!empty($town_name) && $district_id > 0) {
        $check = db_fetch_one($conn, "SELECT id FROM tbl_towns WHERE town_name = ? AND district_id = ?", [$town_name, $district_id], "si");
        if ($check) {
            $err = "Town '{$town_name}' already exists under this district.";
        } else {
            db_execute($conn, "INSERT INTO tbl_towns (town_name, district_id, latitude, longitude) VALUES (?, ?, ?, ?)", [$town_name, $district_id, $lat, $lng], "sidd");
            header("Location: add-locations.php?status=success&action=add-town");
            exit();
        }
    }
}

// Fetch districts for dropdown
$districts = db_fetch_all($conn, "SELECT * FROM tbl_districts ORDER BY district_name ASC");

include('partials/main.php'); 
?>

<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h2 class="main-welcome" style="margin-bottom: 4px;">Service Coverage &amp; Geographic Territories</h2>
        <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Add Sri Lankan districts, towns, and GPS coordinates for radial technician dispatch.</p>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i> Geographic territory updated successfully!
        </div>
    <?php endif; ?>

    <?php if (!empty($err)): ?>
        <div class="alert-danger">
            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($err); ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 24px;">
        <!-- Form 1: Add District -->
        <div class="admin-card">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
                <i class="fa-solid fa-map" style="color: #0284c7; margin-right: 8px;"></i> 1. Add District
            </h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="district_name">District Name</label>
                    <input type="text" id="district_name" name="district_name" placeholder="e.g. Colombo, Gampaha, Kandy" required />
                </div>
                <button type="submit" name="submit-district" class="btn-primary" style="width: 100%;">
                    <i class="fa-solid fa-plus"></i> Save District
                </button>
            </form>
        </div>

        <!-- Form 2: Add Town -->
        <div class="admin-card">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
                <i class="fa-solid fa-location-dot" style="color: #10b981; margin-right: 8px;"></i> 2. Add Town / Service Hub
            </h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="district_id">Select Parent District</label>
                    <select id="district_id" name="district_id" required>
                        <option value="">Select District</option>
                        <?php foreach ($districts as $d): ?>
                            <option value="<?php echo $d['id']; ?>"><?php echo htmlspecialchars($d['district_name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="town_name">Town Name</label>
                    <input type="text" id="town_name" name="town_name" placeholder="e.g. Nugegoda, Maharagama, Negombo" required />
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label for="latitude">GPS Latitude</label>
                        <input type="number" id="latitude" name="latitude" placeholder="e.g. 6.8724" step="any" />
                    </div>
                    <div class="form-group">
                        <label for="longitude">GPS Longitude</label>
                        <input type="number" id="longitude" name="longitude" placeholder="e.g. 79.8893" step="any" />
                    </div>
                </div>

                <button type="submit" name="submit-town" class="btn-primary" style="width: 100%; background: #10b981;">
                    <i class="fa-solid fa-plus"></i> Save Town
                </button>
            </form>
        </div>
    </div>
</div>

<?php 
include('partials/footer.php'); 
?>
