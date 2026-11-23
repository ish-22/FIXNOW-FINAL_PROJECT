<?php
include_once('../config/constant.php');

if (!isset($_SESSION['vendor_id'])) {
    header("Location: ../signin.php");
    exit();
}

$vendor_id = intval($_SESSION['vendor_id']);
$error_msg = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_update'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $district_id = intval($_POST['district_id'] ?? 0);
    $town_id = intval($_POST['town_id'] ?? 0);
    $experience = trim($_POST['experience'] ?? '');
    $about_you = trim($_POST['about_you'] ?? '');
    $available_times = trim($_POST['available_times'] ?? '');

    if (!empty($name) && !empty($email) && !empty($phone)) {
        // Handle Profile Image Upload
        $profile_picture = null;
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
            $file_ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
            if (in_array($file_ext, $allowed_exts)) {
                $profile_picture = 'vendor_' . $vendor_id . '_' . time() . '.' . $file_ext;
                $target_file = __DIR__ . '/../images/vendor_images/' . $profile_picture;
                move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file);
            }
        }

        if ($profile_picture) {
            $sql_update = "UPDATE tbl_service_providers SET 
                            full_name = ?, email = ?, contact_no = ?, district_id = ?, town_id = ?, 
                            experience = ?, about_you = ?, available_dates_and_times = ?, vendor_image = ? 
                           WHERE id = ?";
            $updated = db_execute($conn, $sql_update, [$name, $email, $phone, $district_id, $town_id, $experience, $about_you, $available_times, $profile_picture, $vendor_id], "sssiissssi");
        } else {
            $sql_update = "UPDATE tbl_service_providers SET 
                            full_name = ?, email = ?, contact_no = ?, district_id = ?, town_id = ?, 
                            experience = ?, about_you = ?, available_dates_and_times = ? 
                           WHERE id = ?";
            $updated = db_execute($conn, $sql_update, [$name, $email, $phone, $district_id, $town_id, $experience, $about_you, $available_times, $vendor_id], "sssiisssi");
        }

        if ($updated) {
            header("Location: profile.php?status=success&action=profile_updated");
            exit();
        } else {
            $error_msg = "Database error saving changes. Please ensure email is unique.";
        }
    } else {
        $error_msg = "Please fill in all required fields.";
    }
}

// Fetch vendor details securely
$vendor = db_fetch_one($conn, "SELECT * FROM tbl_service_providers WHERE id = ?", [$vendor_id], "i");
if (!$vendor) {
    header("Location: ../signin.php");
    exit();
}

// Fetch districts
$districts = db_fetch_all($conn, "SELECT id, district_name FROM tbl_districts ORDER BY district_name ASC");

// Fetch towns for current district
$current_district_id = intval($vendor['district_id'] ?? 0);
$towns = [];
if ($current_district_id > 0) {
    $towns = db_fetch_all($conn, "SELECT id, town_name FROM tbl_towns WHERE district_id = ? ORDER BY town_name ASC", [$current_district_id], "i");
}

$current_page = 'profile.php';
include('./partials/header.php');
?>

<!-- Hero Header Section -->
<div style="background: linear-gradient(180deg, #f0f9ff 0%, #ffffff 100%); padding: 50px 0 30px 0; border-bottom: 1px solid #e2e8f0; text-align: center;">
    <div style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
        <span style="display: inline-flex; align-items: center; gap: 8px; margin-bottom: 14px; background: #e0f2fe; color: #0284c7; padding: 6px 14px; border-radius: 50px; font-size: 13.5px; font-weight: 700;">
            <i class="fa-solid fa-user-pen"></i> Specialist Console
        </span>
        <h1 style="font-size: 32px; font-weight: 900; color: #0f172a; margin: 0 0 8px 0;">
            Edit Specialist Profile
        </h1>
        <p style="color: #64748b; font-size: 15.5px; margin: 0 auto; max-width: 600px;">
            Keep your contact numbers, primary service town, and availability updated to maintain reliable job dispatching.
        </p>
    </div>
</div>

<!-- Main Form Section -->
<section style="padding: 40px 20px 80px 20px; max-width: 760px; margin: 0 auto;">
    
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 36px; box-shadow: 0 4px 20px rgba(0,0,0,0.03);">
        
        <?php if (!empty($error_msg)): ?>
            <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px 20px; color: #991b1b; display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                <i class="fa-solid fa-triangle-exclamation" style="font-size: 18px; color: #ef4444; flex-shrink: 0;"></i>
                <span style="font-size: 14px; font-weight: 600;"><?php echo htmlspecialchars($error_msg); ?></span>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            
            <!-- Avatar Upload & Preview -->
            <div style="text-align: center; margin-bottom: 30px; padding-bottom: 25px; border-bottom: 1px solid #e2e8f0;">
                <?php 
                    $v_img = '../images/vendor_images/' . ($vendor['vendor_image'] ?? '');
                    $avatar_src = (!empty($vendor['vendor_image']) && file_exists($v_img)) ? $v_img : '../images/vendor_images/default.jpg';
                ?>
                <img id="vendor-preview" src="<?php echo htmlspecialchars($avatar_src); ?>" alt="Profile Picture" 
                     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #0284c7; box-shadow: 0 4px 14px rgba(2,132,199,0.2); margin-bottom: 14px;" />
                <div>
                    <label for="profile_picture" style="background: #e0f2fe; color: #0284c7; padding: 8px 18px; border-radius: 50px; font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-camera"></i> Change Photo
                    </label>
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" style="display: none;" onchange="previewVendorImage(this);">
                    <p style="font-size: 12px; color: #94a3b8; margin: 6px 0 0 0;">JPG, PNG, WEBP (Max 2MB)</p>
                </div>
            </div>

            <!-- Full Name & Contact -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        Specialist Name <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($vendor['full_name'] ?? ''); ?>" required
                           style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit;">
                </div>

                <div>
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        Contact Phone <span style="color: #ef4444;">*</span>
                    </label>
                    <input type="tel" name="phone" value="<?php echo htmlspecialchars($vendor['contact_no'] ?? ''); ?>" required
                           style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit;">
                </div>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                    Registered Email <span style="color: #ef4444;">*</span>
                </label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($vendor['email'] ?? ''); ?>" required
                       style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit;">
            </div>

            <!-- District & Town Selection -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        District
                    </label>
                    <select id="district_select" name="district_id" onchange="loadVendorTowns(this.value);"
                            style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; background: #ffffff;">
                        <option value="">Select District</option>
                        <?php foreach ($districts as $d): ?>
                            <option value="<?php echo $d['id']; ?>" <?php echo ($d['id'] == $vendor['district_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($d['district_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        Service Town / Primary Base
                    </label>
                    <select id="town_select" name="town_id"
                            style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; background: #ffffff;">
                        <option value="">Select Town</option>
                        <?php foreach ($towns as $t): ?>
                            <option value="<?php echo $t['id']; ?>" <?php echo ($t['id'] == $vendor['town_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($t['town_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Experience & Working Hours -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px;">
                <div>
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        Years of Experience
                    </label>
                    <input type="text" name="experience" value="<?php echo htmlspecialchars($vendor['experience'] ?? ''); ?>" placeholder="e.g. 7 Years"
                           style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit;">
                </div>

                <div>
                    <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                        Availability &amp; Hours
                    </label>
                    <input type="text" name="available_times" value="<?php echo htmlspecialchars($vendor['available_dates_and_times'] ?? ''); ?>" placeholder="e.g. Mon-Sat 8:00 AM - 6:00 PM"
                           style="width: 100%; box-sizing: border-box; padding: 12px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit;">
                </div>
            </div>

            <!-- About / Bio -->
            <div style="margin-bottom: 28px;">
                <label style="display: block; font-size: 13.5px; font-weight: 700; color: #334155; margin-bottom: 7px;">
                    Professional Bio &amp; Specialty Equipment
                </label>
                <textarea name="about_you" rows="4" placeholder="Briefly describe your specialization, equipment, and warranty commitments..."
                          style="width: 100%; box-sizing: border-box; padding: 14px 16px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14.5px; outline: none; font-family: inherit; line-height: 1.6; resize: vertical;"><?php echo htmlspecialchars($vendor['about_you'] ?? ''); ?></textarea>
            </div>

            <!-- Submit Buttons -->
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
                <button type="submit" name="submit_update" style="background: #0284c7; color: #ffffff; border: none; padding: 12px 30px; font-size: 15px; font-weight: 700; border-radius: 50px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-floppy-disk"></i> Save Profile Changes
                </button>
                <a href="profile.php" style="color: #64748b; font-size: 14px; text-decoration: none; font-weight: 600;">
                    Cancel &amp; Return
                </a>
            </div>

        </form>

    </div>

</section>

<script>
function previewVendorImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('vendor-preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function loadVendorTowns(districtId) {
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
