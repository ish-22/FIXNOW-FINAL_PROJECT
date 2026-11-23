<?php
include_once('./config/constant.php');

if (!isset($_SESSION['customer_id'])) {
    header("Location: signin.php"); 
    exit();
}

$customer_id = intval($_SESSION['customer_id']); 
$error_msg = '';

// Handle Profile Update Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_update'])) {
    $cus_name = trim($_POST['cus_name'] ?? '');
    $cus_phone = trim($_POST['cus_phone'] ?? '');
    $cus_email = trim($_POST['cus_email'] ?? '');
    $cus_address = trim($_POST['cus_address'] ?? '');
    $district_id = intval($_POST['district_id'] ?? 0);
    $town_id = intval($_POST['town_id'] ?? 0);

    if (!empty($cus_name) && !empty($cus_phone) && !empty($cus_email)) {
        // Handle optional profile picture upload
        $image_name = null;
        if (isset($_FILES['cus_image']) && $_FILES['cus_image']['error'] === UPLOAD_ERR_OK) {
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
            $file_ext = strtolower(pathinfo($_FILES['cus_image']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed_exts)) {
                $image_name = $customer_id . '_' . time() . '.' . $file_ext;
                $target_path = __DIR__ . '/images/cus_images/' . $image_name;
                move_uploaded_file($_FILES['cus_image']['tmp_name'], $target_path);
            }
        }

        if ($image_name) {
            $update_sql = "UPDATE tbl_customer SET cus_name = ?, cus_phone = ?, cus_email = ?, cus_address = ?, district_id = ?, town_id = ?, cus_image = ? WHERE id = ?";
            $updated = db_execute($conn, $update_sql, [$cus_name, $cus_phone, $cus_email, $cus_address, $district_id, $town_id, $image_name, $customer_id], "ssssiisi");
        } else {
            $update_sql = "UPDATE tbl_customer SET cus_name = ?, cus_phone = ?, cus_email = ?, cus_address = ?, district_id = ?, town_id = ? WHERE id = ?";
            $updated = db_execute($conn, $update_sql, [$cus_name, $cus_phone, $cus_email, $cus_address, $district_id, $town_id, $customer_id], "ssssiii");
        }

        if ($updated) {
            header("Location: profile.php?status=success&action=profile_updated");
            exit();
        } else {
            $error_msg = "Database error updating profile. Please ensure email is not already in use.";
        }
    } else {
        $error_msg = "Please fill in all required fields.";
    }
}

// Fetch current customer details securely
$customer = db_fetch_one($conn, "SELECT * FROM tbl_customer WHERE id = ?", [$customer_id], "i");
if (!$customer) {
    header("Location: signin.php");
    exit();
}

// Fetch districts for dropdown
$districts = db_fetch_all($conn, "SELECT id, district_name FROM tbl_districts ORDER BY district_name ASC");

// Fetch towns for current customer's district
$current_district_id = intval($customer['district_id'] ?? 0);
$towns = [];
if ($current_district_id > 0) {
    $towns = db_fetch_all($conn, "SELECT id, town_name FROM tbl_towns WHERE district_id = ? ORDER BY town_name ASC", [$current_district_id], "i");
}

$current_page = 'profile.php';
include('./partials/header.php');
?>

<!-- Hero Section -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 30px 0; border-bottom: 1px solid #e2e8f0; text-align: center;">
    <div class="fn-container" style="max-width: 800px;">
        <span class="fn-hero-badge" style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 16px;">
            <i class="fa-solid fa-user-pen" style="color: #0284c7;"></i> Account Settings
        </span>
        <h1 class="fn-section-title" style="margin-bottom: 10px; font-size: 32px; font-weight: 900; color: #0f172a;">
            Edit Account Profile
        </h1>
        <p class="fn-section-subtitle" style="color: #64748b; font-size: 15.5px; margin: 0 auto; max-width: 600px;">
            Update your contact numbers, primary delivery address, and profile photo for seamless specialist scheduling.
        </p>
    </div>
</div>

<!-- Main Form Section -->
<section style="padding: 40px 0 80px 0;">
    <div class="fn-container" style="max-width: 760px;">
        
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 36px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
            
            <?php if (!empty($error_msg)): ?>
                <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px 20px; color: #991b1b; display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px; color: #ef4444; flex-shrink: 0;"></i>
                    <span style="font-size: 14px; font-weight: 600;"><?php echo htmlspecialchars($error_msg); ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data">
                
                <!-- Avatar Preview & Upload -->
                <div style="text-align: center; margin-bottom: 30px; padding-bottom: 25px; border-bottom: 1px solid #e2e8f0;">
                    <?php 
                        $img_path = './images/cus_images/' . ($customer['cus_image'] ?? '');
                        $avatar_src = (!empty($customer['cus_image']) && file_exists($img_path)) ? $img_path : './images/cus_images/default.jpg';
                    ?>
                    <img id="avatar-preview" src="<?php echo htmlspecialchars($avatar_src); ?>" alt="Profile Picture" 
                         style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #0284c7; box-shadow: 0 4px 14px rgba(2,132,199,0.2); margin-bottom: 14px;" />
                    <div>
                        <label for="cus_image" style="background: #e0f2fe; color: #0284c7; padding: 8px 18px; border-radius: 50px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: background 0.2s;" onmouseover="this.style.background='#bae6fd'" onmouseout="this.style.background='#e0f2fe'">
                            <i class="fa-solid fa-camera"></i> Change Photo
                        </label>
                        <input type="file" id="cus_image" name="cus_image" accept="image/*" style="display: none;" onchange="previewImage(this);">
                        <p style="font-size: 12px; color: #94a3b8; margin: 6px 0 0 0;">Supported formats: JPG, PNG, WEBP (Max 2MB)</p>
                    </div>
                </div>

                <!-- Input Fields -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                    <div>
                        <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                            Full Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" name="cus_name" value="<?php echo htmlspecialchars($customer['cus_name'] ?? ''); ?>" required
                               style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                            Contact Phone <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="tel" name="cus_phone" value="<?php echo htmlspecialchars($customer['cus_phone'] ?? ''); ?>" required
                               style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit;">
                    </div>
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        Email Address <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="email" name="cus_email" value="<?php echo htmlspecialchars($customer['cus_email'] ?? ''); ?>" required
                           style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit;">
                </div>

                <div style="margin-bottom: 18px;">
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        Street Address / Landmark
                    </label>
                    <input type="text" name="cus_address" value="<?php echo htmlspecialchars($customer['cus_address'] ?? ''); ?>" placeholder="e.g. No. 45, Temple Road"
                           style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 28px;">
                    <div>
                        <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                            District
                        </label>
                        <select id="district_select" name="district_id" onchange="loadTowns(this.value);"
                                style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; background: #ffffff;">
                            <option value="">Select District</option>
                            <?php foreach ($districts as $d): ?>
                                <option value="<?php echo $d['id']; ?>" <?php echo ($d['id'] == $customer['district_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($d['district_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                            Town / Area
                        </label>
                        <select id="town_select" name="town_id"
                                style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; background: #ffffff;">
                            <option value="">Select Town</option>
                            <?php foreach ($towns as $t): ?>
                                <option value="<?php echo $t['id']; ?>" <?php echo ($t['id'] == $customer['town_id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t['town_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                    <button type="submit" name="submit_update" class="fn-btn fn-btn-primary" style="padding: 12px 30px; font-size: 15px; font-weight: 700; border-radius: 50px; cursor: pointer;">
                        <i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i> Save Changes
                    </button>
                    <a href="profile.php" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-arrow-left"></i> Cancel &amp; Back to Profile
                    </a>
                </div>

            </form>

        </div>

    </div>
</section>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function loadTowns(districtId) {
    var townSelect = document.getElementById('town_select');
    townSelect.innerHTML = '<option value="">Loading towns...</option>';
    if (!districtId) {
        townSelect.innerHTML = '<option value="">Select Town</option>';
        return;
    }
    fetch('get_towns.php?district_id=' + districtId)
        .then(response => response.json())
        .then(data => {
            townSelect.innerHTML = '<option value="">Select Town</option>';
            data.forEach(function(item) {
                var opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.town_name;
                townSelect.appendChild(opt);
            });
        })
        .catch(err => {
            townSelect.innerHTML = '<option value="">Failed to load towns</option>';
        });
}
</script>

<?php
include('./partials/footer.php');
?>
