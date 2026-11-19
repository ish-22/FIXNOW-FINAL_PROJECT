<?php
ob_start();
include('./config/constant.php');

function emailExists($conn, $email, $table, $column) {
    $query = "SELECT * FROM $table WHERE $column = '$email'";
    $result = mysqli_query($conn, $query);
    return (mysqli_num_rows($result) > 0);
}

$alert_message = "";
$alert_type = "";

// Handle the form submission for vendor registration
if (isset($_POST['submit'])) {
    $full_name = isset($_POST['full_name']) ? mysqli_real_escape_string($conn, trim($_POST['full_name'])) : '';
    $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, trim($_POST['email'])) : '';
    $contact_no = isset($_POST['contact_no']) ? mysqli_real_escape_string($conn, trim($_POST['contact_no'])) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $district_id = isset($_POST['district_id']) ? intval($_POST['district_id']) : 0;
    $town_id = isset($_POST['town_id']) ? intval($_POST['town_id']) : 0;
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $servicetype_id = isset($_POST['servicetype_id']) ? intval($_POST['servicetype_id']) : 0;
    $experience = isset($_POST['experience']) ? mysqli_real_escape_string($conn, trim($_POST['experience'])) : '';
    $available_dates_and_times = isset($_POST['available_dates_and_times']) ? mysqli_real_escape_string($conn, trim($_POST['available_dates_and_times'])) : '';
    $about_you = isset($_POST['about_you']) ? mysqli_real_escape_string($conn, trim($_POST['about_you'])) : '';

    if (emailExists($conn, $email, "tbl_service_providers", "email")) {
        $alert_message = "This email is already registered as a service provider. Please use another email or sign in.";
        $alert_type = "error";
    } else {
        // Industry-standard Bcrypt password hash
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $vendor_image = "";

        if (isset($_FILES['vendor_image']) && $_FILES['vendor_image']['name'] != "") {
            $image_name = $_FILES['vendor_image']['name'];
            $ext = pathinfo($image_name, PATHINFO_EXTENSION);
            $image_name = "vendor_" . rand(1000, 9999) . "." . $ext;
            $target_directory = "images/vendor_images/";
            $target_file = $target_directory . $image_name;

            if (move_uploaded_file($_FILES['vendor_image']['tmp_name'], $target_file)) {
                $vendor_image = $image_name;
            }
        }

        // Auto-generate unique registration code (e.g. REG-1005)
        $next_id_row = db_fetch_one($conn, "SELECT MAX(id) AS max_id FROM tbl_service_providers");
        $next_id = ($next_id_row && $next_id_row['max_id']) ? intval($next_id_row['max_id']) + 1 : 1;
        $registration_code = "REG" . (1000 + $next_id);

        $insert_res = db_execute(
            $conn,
            "INSERT INTO tbl_service_providers (registration_code, full_name, email, contact_no, password, district_id, town_id, category_id, servicetype_id, experience, available_dates_and_times, about_you, registration_fee_paid, vendor_image, status) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'no', ?, 'Pending')",
            [$registration_code, $full_name, $email, $contact_no, $hashed_password, $district_id, $town_id, $category_id, $servicetype_id, $experience, $available_dates_and_times, $about_you, $vendor_image],
            "ssssiiiiissss"
        );

        if ($insert_res['success']) {
            $service_provider_id = $insert_res['insert_id'];
            $feedback_url = SITEURL . "service-vendor/feedback.php?service_provider_id=" . $service_provider_id;
            db_execute($conn, "UPDATE tbl_service_providers SET feedback_url = ? WHERE id = ?", [$feedback_url, $service_provider_id], "si");
            header("Location: registration-payment.php?service_provider_id=" . $service_provider_id);
            exit();
        } else {
            $alert_message = "Error registering service provider: " . $insert_res['error'];
            $alert_type = "error";
        }
    }
}

// Handle the form submission for customer registration
if (isset($_POST['submit_customer'])) {
    $cus_name = trim($_POST['cus_name']);
    $cus_email = trim($_POST['cus_email']);
    $cus_phone = trim($_POST['cus_phone']);
    $cus_address = trim($_POST['cus_address']);
    $cus_password = $_POST['cus_password'];
    $district_id = intval($_POST['district_id']);
    $town_id = intval($_POST['town_id']);

    if (emailExists($conn, $cus_email, "tbl_customer", "cus_email")) {
        $alert_message = "This email is already registered as a customer. Please sign in or use another email.";
        $alert_type = "error";
    } else {
        // Industry-standard Bcrypt password hash
        $hashed_password = password_hash($cus_password, PASSWORD_DEFAULT);

        $insert_cus = db_execute(
            $conn,
            "INSERT INTO tbl_customer (cus_name, cus_email, cus_phone, cus_address, cus_password, district_id, town_id, registration_date) 
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW())",
            [$cus_name, $cus_email, $cus_phone, $cus_address, $hashed_password, $district_id, $town_id],
            "sssssii"
        );

        if ($insert_cus['success']) {
            header("Location: " . SITEURL . 'signin.php?status=success&action=cus_registration');
            exit();
        } else {
            $alert_message = "Error registering customer: " . $insert_cus['error'];
            $alert_type = "error";
        }
    }
}

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Your Account | FIXNOW</title>
    <link rel="shortcut icon" href="./assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #1976d2;
            --primary-dark: #0d47a1;
            --primary-light: #e3f2fd;
            --secondary: #00897b;
            --dark: #0f172a;
            --gray-700: #334155;
            --gray-500: #64748b;
            --gray-300: #cbd5e1;
            --gray-100: #f1f5f9;
            --white: #ffffff;
            --radius-lg: 18px;
            --radius-md: 10px;
            --radius-sm: 6px;
            --shadow-card: 0 20px 45px rgba(15, 23, 42, 0.12);
            --transition: all 0.25s ease-in-out;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #f0f7ff 0%, #e2eafc 50%, #dbeafe 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
            position: relative;
        }

        /* Decorative background elements */
        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            z-index: 0;
            pointer-events: none;
        }
        body::before {
            width: 380px;
            height: 380px;
            background: rgba(25, 118, 210, 0.18);
            top: -100px;
            left: -100px;
        }
        body::after {
            width: 420px;
            height: 420px;
            background: rgba(0, 137, 123, 0.15);
            bottom: -100px;
            right: -100px;
        }

        .auth-container {
            width: 100%;
            max-width: 820px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(16px);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-card);
            border: 1px solid rgba(255, 255, 255, 0.8);
            padding: 40px 45px;
            position: relative;
            z-index: 1;
            margin-bottom: 25px;
        }

        @media (max-width: 768px) {
            .auth-container {
                padding: 30px 20px;
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo {
            display: inline-block;
            margin-bottom: 12px;
            transition: var(--transition);
        }
        .brand-logo img {
            max-height: 48px;
            width: auto;
        }
        .brand-logo:hover {
            transform: scale(1.02);
        }

        .auth-title {
            font-family: 'League Spartan', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .auth-subtitle {
            font-size: 14px;
            color: var(--gray-500);
        }

        /* Role Selector Switcher */
        .role-switcher {
            display: flex;
            background: var(--gray-100);
            border-radius: 40px;
            padding: 5px;
            margin: 25px auto 30px;
            max-width: 460px;
            border: 1px solid var(--gray-300);
            position: relative;
        }

        .role-tab {
            flex: 1;
            text-align: center;
            padding: 11px 18px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 35px;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            background: transparent;
            color: var(--gray-500);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .role-tab.active {
            background: var(--primary);
            color: var(--white);
            box-shadow: 0 4px 14px rgba(25, 118, 210, 0.35);
        }

        /* Forms Layout */
        .form-panel {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        .form-panel.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        @media (max-width: 640px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }
        }

        .full-width {
            grid-column: 1 / -1;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .input-wrap {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrap i.field-icon {
            position: absolute;
            left: 14px;
            color: var(--gray-500);
            font-size: 15px;
            pointer-events: none;
            transition: var(--transition);
        }

        .input-wrap .form-control {
            width: 100%;
            padding: 12px 14px 12px 42px;
            font-size: 14px;
            color: var(--dark);
            background: var(--white);
            border: 1.5px solid var(--gray-300);
            border-radius: var(--radius-md);
            outline: none;
            transition: var(--transition);
        }

        .input-wrap textarea.form-control {
            padding-left: 42px;
            resize: vertical;
            min-height: 80px;
        }

        .input-wrap select.form-control {
            appearance: none;
            cursor: pointer;
            padding-right: 36px;
        }

        .input-wrap .select-arrow {
            position: absolute;
            right: 14px;
            color: var(--gray-500);
            pointer-events: none;
            font-size: 12px;
        }

        .input-wrap .toggle-eye {
            position: absolute;
            right: 14px;
            color: var(--gray-500);
            cursor: pointer;
            font-size: 14px;
            transition: var(--transition);
        }
        .input-wrap .toggle-eye:hover {
            color: var(--primary);
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.15);
        }
        .form-control:focus + i.field-icon,
        .input-wrap:focus-within i.field-icon {
            color: var(--primary);
        }

        /* File Upload */
        .file-upload-box {
            position: relative;
            border: 2px dashed var(--gray-300);
            border-radius: var(--radius-md);
            padding: 12px 15px;
            text-align: center;
            background: var(--gray-100);
            cursor: pointer;
            transition: var(--transition);
        }
        .file-upload-box:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        .file-upload-box input[type="file"] {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-700);
        }
        .file-upload-label i {
            color: var(--primary);
            font-size: 18px;
        }

        /* Notice Banner */
        .notice-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 1px solid #bbf7d0;
            border-radius: var(--radius-md);
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #166534;
        }
        .notice-card i {
            font-size: 20px;
            color: #16a34a;
            flex-shrink: 0;
        }

        /* Submit Buttons */
        .btn-submit {
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--white);
            font-size: 16px;
            font-weight: 700;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
            box-shadow: 0 6px 18px rgba(25, 118, 210, 0.3);
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(25, 118, 210, 0.4);
            background: linear-gradient(135deg, #1e88e5 0%, #1565c0 100%);
        }
        .btn-submit:active {
            transform: translateY(0);
        }

        .auth-footer-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--gray-500);
        }
        .auth-footer-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: var(--transition);
        }
        .auth-footer-link a:hover {
            text-decoration: underline;
            color: var(--primary-dark);
        }

        /* Alert Banner */
        .alert-box {
            padding: 12px 18px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-box.error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }

        .back-home {
            margin-top: 15px;
            font-size: 13px;
            color: var(--gray-500);
            text-align: center;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .back-home:hover {
            color: var(--dark);
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <!-- Header -->
        <div class="auth-header">
            <a href="index.php" class="brand-logo">
                <img src="./assets/images/fixnow-logo.png" alt="FIXNOW Logo">
            </a>
            <h1 class="auth-title" id="page-title">Create Your Account</h1>
            <p class="auth-subtitle" id="page-subtitle">Join thousands of customers and verified experts on FixNow</p>

            <!-- Role Selector -->
            <div class="role-switcher">
                <button type="button" class="role-tab active" id="tab-customer" onclick="selectRole('customer')">
                    <i class="fa-solid fa-user"></i> Customer Account
                </button>
                <button type="button" class="role-tab" id="tab-vendor" onclick="selectRole('vendor')">
                    <i class="fa-solid fa-screwdriver-wrench"></i> Service Provider
                </button>
            </div>
        </div>

        <?php if (!empty($alert_message)): ?>
            <div class="alert-box <?php echo $alert_type; ?>">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo htmlspecialchars($alert_message); ?></span>
            </div>
        <?php endif; ?>

        <!-- ============================================== -->
        <!-- Form 1: CUSTOMER REGISTRATION -->
        <!-- ============================================== -->
        <form id="customer-form" class="form-panel active" method="POST" action="">
            <div class="form-grid">
                <div class="form-group">
                    <label for="cus_name"><i class="fa-solid fa-user"></i> Full Name</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-user field-icon"></i>
                        <input type="text" id="cus_name" name="cus_name" class="form-control" placeholder="John Doe" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cus_email"><i class="fa-solid fa-envelope"></i> Email Address</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-envelope field-icon"></i>
                        <input type="email" id="cus_email" name="cus_email" class="form-control" placeholder="name@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cus_phone"><i class="fa-solid fa-phone"></i> Contact Number</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-phone field-icon"></i>
                        <input type="tel" id="cus_phone" name="cus_phone" class="form-control" placeholder="07XXXXXXXX" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cus_password"><i class="fa-solid fa-lock"></i> Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock field-icon"></i>
                        <input type="password" id="cus_password" name="cus_password" class="form-control" placeholder="At least 6 characters" minlength="4" required>
                        <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('cus_password', this)"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="district_id_customer"><i class="fa-solid fa-map-location-dot"></i> District</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-location-dot field-icon"></i>
                        <select id="district_id_customer" name="district_id" class="form-control" required>
                            <option value="">Select District</option>
                            <?php
                            $districts_query = "SELECT * FROM tbl_districts ORDER BY district_name ASC";
                            $districts_result = mysqli_query($conn, $districts_query);
                            while ($district = mysqli_fetch_assoc($districts_result)): ?>
                                <option value="<?php echo $district['id']; ?>"><?php echo htmlspecialchars($district['district_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                        <i class="fa-solid fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="town_id_customer"><i class="fa-solid fa-city"></i> Town / City</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-city field-icon"></i>
                        <select id="town_id_customer" name="town_id" class="form-control" required>
                            <option value="">Select District First</option>
                        </select>
                        <i class="fa-solid fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="cus_address"><i class="fa-solid fa-map-pin"></i> Residential Address</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-house field-icon" style="top: 18px;"></i>
                        <textarea id="cus_address" name="cus_address" class="form-control" placeholder="Street number, road, area" rows="2" required></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" name="submit_customer" class="btn-submit" style="margin-top: 22px;">
                <span>Register as Customer</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>

            <p class="auth-footer-link">
                Already have an account? <a href="signin.php">Sign In here</a>
            </p>
        </form>

        <!-- ============================================== -->
        <!-- Form 2: VENDOR / SERVICE PROVIDER REGISTRATION -->
        <!-- ============================================== -->
        <form id="vendor-form" class="form-panel" method="POST" action="" enctype="multipart/form-data">
            <div class="notice-card">
                <i class="fa-solid fa-circle-info"></i>
                <div>
                    <strong>Service Provider Onboarding:</strong> After submitting your credentials, you will be directed to settle the one-time registration fee to verify and publish your profile.
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="vendor_name"><i class="fa-solid fa-user-tie"></i> Full Name</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-user field-icon"></i>
                        <input type="text" id="vendor_name" name="full_name" class="form-control" placeholder="Expert Name / Company" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="vendor_email"><i class="fa-solid fa-envelope"></i> Email Address</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-envelope field-icon"></i>
                        <input type="email" id="vendor_email" name="email" class="form-control" placeholder="provider@example.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="vendor_phone"><i class="fa-solid fa-phone"></i> Contact Number</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-phone field-icon"></i>
                        <input type="tel" id="vendor_phone" name="contact_no" class="form-control" placeholder="07XXXXXXXX" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="vendor_password"><i class="fa-solid fa-lock"></i> Password</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-lock field-icon"></i>
                        <input type="password" id="vendor_password" name="password" class="form-control" placeholder="Create secure password" minlength="4" required>
                        <i class="fa-regular fa-eye toggle-eye" onclick="togglePass('vendor_password', this)"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="category_id"><i class="fa-solid fa-list-check"></i> Service Category</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-toolbox field-icon"></i>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="">Select Category</option>
                            <?php
                            $categories_query = "SELECT * FROM tbl_category ORDER BY category_name ASC";
                            $categories_result = mysqli_query($conn, $categories_query);
                            while ($category = mysqli_fetch_assoc($categories_result)): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                        <i class="fa-solid fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="servicetype_id"><i class="fa-solid fa-screwdriver-wrench"></i> Specific Service Type</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-wrench field-icon"></i>
                        <select id="servicetype_id" name="servicetype_id" class="form-control" required>
                            <option value="">Select Category First</option>
                        </select>
                        <i class="fa-solid fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="district_id"><i class="fa-solid fa-map-location-dot"></i> Base District</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-location-dot field-icon"></i>
                        <select id="district_id" name="district_id" class="form-control" required>
                            <option value="">Select District</option>
                            <?php
                            $districts_res2 = mysqli_query($conn, "SELECT * FROM tbl_districts ORDER BY district_name ASC");
                            while ($district = mysqli_fetch_assoc($districts_res2)): ?>
                                <option value="<?php echo $district['id']; ?>"><?php echo htmlspecialchars($district['district_name']); ?></option>
                            <?php endwhile; ?>
                        </select>
                        <i class="fa-solid fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="town_id"><i class="fa-solid fa-city"></i> Base Town</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-city field-icon"></i>
                        <select id="town_id" name="town_id" class="form-control" required>
                            <option value="">Select District First</option>
                        </select>
                        <i class="fa-solid fa-chevron-down select-arrow"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="experience"><i class="fa-solid fa-award"></i> Experience Level</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-award field-icon"></i>
                        <input type="text" id="experience" name="experience" class="form-control" placeholder="e.g. 5+ Years" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="available_dates_and_times"><i class="fa-solid fa-clock"></i> Working Hours & Days</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-clock field-icon"></i>
                        <input type="text" id="available_dates_and_times" name="available_dates_and_times" class="form-control" placeholder="e.g. Mon-Sat, 8:00 AM - 6:00 PM" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label><i class="fa-solid fa-image"></i> Profile Photo (Optional)</label>
                    <div class="file-upload-box" onclick="document.getElementById('vendor_image').click();">
                        <input type="file" id="vendor_image" name="vendor_image" accept="image/*" onchange="updateFileName(this)">
                        <div class="file-upload-label" id="file-label">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span id="file-text">Click or drag image here to upload profile picture</span>
                        </div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="about_you"><i class="fa-solid fa-user-pen"></i> About You & Services</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-comment-dots field-icon" style="top: 18px;"></i>
                        <textarea id="about_you" name="about_you" class="form-control" rows="3" placeholder="Describe your skills, qualifications, certifications, and service warranty details..." required></textarea>
                    </div>
                </div>
            </div>

            <button type="submit" name="submit" class="btn-submit" style="margin-top: 22px;">
                <span>Proceed to Provider Registration</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>

            <p class="auth-footer-link">
                Already registered as a provider? <a href="signin.php">Sign In here</a>
            </p>
        </form>
    </div>

    <a href="index.php" class="back-home">
        <i class="fa-solid fa-arrow-left"></i> Return to Homepage
    </a>

    <!-- Client-side Dynamic Dropdown & UI Scripts -->
    <script>
        function selectRole(role) {
            const customerForm = document.getElementById('customer-form');
            const vendorForm = document.getElementById('vendor-form');
            const tabCustomer = document.getElementById('tab-customer');
            const tabVendor = document.getElementById('tab-vendor');
            const pageTitle = document.getElementById('page-title');
            const pageSubtitle = document.getElementById('page-subtitle');

            if (role === 'customer') {
                customerForm.classList.add('active');
                vendorForm.classList.remove('active');
                tabCustomer.classList.add('active');
                tabVendor.classList.remove('active');
                pageTitle.innerText = "Create Customer Account";
                pageSubtitle.innerText = "Browse verified professionals, request bookings, and manage services";
            } else {
                vendorForm.classList.add('active');
                customerForm.classList.remove('active');
                tabVendor.classList.add('active');
                tabCustomer.classList.remove('active');
                pageTitle.innerText = "Service Provider Registration";
                pageSubtitle.innerText = "Join FixNow as a certified specialist and expand your local client base";
            }
        }

        function togglePass(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function updateFileName(input) {
            if (input.files && input.files[0]) {
                document.getElementById('file-text').innerText = "Selected: " + input.files[0].name;
                document.getElementById('file-label').style.color = '#1976d2';
            }
        }

        // Customer: Load towns based on district selection
        document.getElementById('district_id_customer').addEventListener('change', function() {
            const districtId = this.value;
            const townSelect = document.getElementById('town_id_customer');
            townSelect.innerHTML = '<option value="">Loading towns...</option>';

            if (!districtId) {
                townSelect.innerHTML = '<option value="">Select District First</option>';
                return;
            }

            fetch(`get_towns.php?district_id=${districtId}`)
                .then(res => res.json())
                .then(data => {
                    townSelect.innerHTML = '<option value="">Select Town</option>';
                    data.forEach(town => {
                        const opt = document.createElement('option');
                        opt.value = town.id;
                        opt.textContent = town.town_name;
                        townSelect.appendChild(opt);
                    });
                })
                .catch(() => {
                    townSelect.innerHTML = '<option value="">Failed to load towns</option>';
                });
        });

        // Vendor: Load towns based on district selection
        document.getElementById('district_id').addEventListener('change', function() {
            const districtId = this.value;
            const townSelect = document.getElementById('town_id');
            townSelect.innerHTML = '<option value="">Loading towns...</option>';

            if (!districtId) {
                townSelect.innerHTML = '<option value="">Select District First</option>';
                return;
            }

            fetch(`get_towns.php?district_id=${districtId}`)
                .then(res => res.json())
                .then(data => {
                    townSelect.innerHTML = '<option value="">Select Town</option>';
                    data.forEach(town => {
                        const opt = document.createElement('option');
                        opt.value = town.id;
                        opt.textContent = town.town_name;
                        townSelect.appendChild(opt);
                    });
                })
                .catch(() => {
                    townSelect.innerHTML = '<option value="">Failed to load towns</option>';
                });
        });

        // Vendor: Load service types based on category selection
        document.getElementById('category_id').addEventListener('change', function() {
            const categoryId = this.value;
            const serviceTypeSelect = document.getElementById('servicetype_id');
            serviceTypeSelect.innerHTML = '<option value="">Loading services...</option>';

            if (!categoryId) {
                serviceTypeSelect.innerHTML = '<option value="">Select Category First</option>';
                return;
            }

            fetch(`get_service_types.php?category_id=${categoryId}`)
                .then(res => res.json())
                .then(data => {
                    serviceTypeSelect.innerHTML = '<option value="">Select Service Type</option>';
                    data.forEach(service => {
                        const opt = document.createElement('option');
                        opt.value = service.id;
                        opt.textContent = service.service_type;
                        serviceTypeSelect.appendChild(opt);
                    });
                })
                .catch(() => {
                    serviceTypeSelect.innerHTML = '<option value="">Failed to load services</option>';
                });
        });

        // Support URL parameter: ?type=vendor or ?type=customer
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const userType = urlParams.get('type');
            if (userType === 'vendor') {
                selectRole('vendor');
            } else {
                selectRole('customer');
            }
        });
    </script>
</body>
</html>
