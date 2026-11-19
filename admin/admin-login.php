<?php
include('../config/constant.php');

// Handle form submission
$login_error = '';
if (isset($_POST['submit'])) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        // Query admin by username or email with parameterized statement
        $admin = db_fetch_one($conn, "SELECT * FROM tbl_admin WHERE username = ? OR email = ?", [$username, $username], "ss");

        if ($admin && secure_password_verify($password, $admin['password'])) {
            // Transparent password hash rehash if needed
            if (password_needs_rehash($admin['password'], PASSWORD_BCRYPT)) {
                $new_hash = secure_password_hash($password);
                db_execute($conn, "UPDATE tbl_admin SET password = ? WHERE id = ?", [$new_hash, $admin['id']], "si");
            }

            $_SESSION['user'] = $admin['username'];
            $_SESSION['user_id'] = $admin['id'];
            header('Location: admin-index.php');
            exit();
        } else {
            $login_error = 'Invalid administrative username or password.';
        }
    } else {
        $login_error = 'Please enter both username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sign In | FIXNOW</title>
    <link rel="shortcut icon" href="../assets/images/fixnow-logo-icon.png" type="image/svg+xml">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .admin-auth-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 900px;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
        }
        @media (max-width: 768px) {
            .admin-auth-card {
                grid-template-columns: 1fr;
            }
            .admin-auth-brand {
                display: none !important;
            }
        }
        .admin-auth-form {
            padding: 48px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .admin-auth-brand {
            background: linear-gradient(135deg, #0284c7 0%, #0f172a 100%);
            padding: 48px 40px;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .auth-logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
        }
        .auth-logo-icon {
            width: 44px;
            height: 44px;
            background: #0284c7;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.4);
        }
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 8px;
        }
        .form-input {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 14.5px;
            outline: none;
            transition: all 0.2s ease;
            font-family: inherit;
        }
        .form-input:focus {
            border-color: #0284c7;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 40px;
            color: #94a3b8;
            font-size: 15px;
        }
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: #0284c7;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);
            margin-top: 10px;
        }
        .btn-submit:hover {
            background: #0369a1;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.4);
        }
        .error-banner {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #64748b;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            margin-top: 24px;
            transition: color 0.2s ease;
        }
        .back-link:hover {
            color: #0284c7;
        }
    </style>
</head>
<body>

    <div class="admin-auth-card">
        <!-- Form Section -->
        <div class="admin-auth-form">
            <div class="auth-logo-badge">
                <div class="auth-logo-icon">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <div>
                    <h2 style="font-size: 20px; font-weight: 800; color: #0f172a;">FIXNOW</h2>
                    <span style="font-size: 11px; font-weight: 700; color: #0284c7; text-transform: uppercase;">Admin Portal</span>
                </div>
            </div>

            <h1 style="font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Admin Sign In</h1>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 24px;">Please authenticate to access platform moderation controls.</p>

            <?php if (!empty($login_error) || (isset($_GET['status']) && $_GET['status'] == 'error')): ?>
                <div class="error-banner">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span><?php echo !empty($login_error) ? $login_error : 'Invalid username or password.'; ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <label class="form-label">Username or Admin Email</label>
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" name="username" class="form-input" placeholder="admin or admin@fixnow.lk" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" name="password" id="adminPassword" class="form-input" placeholder="••••••••" required>
                    <i class="fa-solid fa-eye" id="toggleAdminPass" style="position: absolute; right: 14px; top: 40px; color: #94a3b8; cursor: pointer;"></i>
                </div>

                <button type="submit" name="submit" class="btn-submit">
                    <i class="fa-solid fa-right-to-bracket" style="margin-right: 6px;"></i> Sign In to Dashboard
                </button>
            </form>

            <a href="../index.php" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> Return to FIXNOW Homepage
            </a>
        </div>

        <!-- Right Side Brand Panel -->
        <div class="admin-auth-brand">
            <div>
                <span style="background: rgba(255,255,255,0.15); padding: 4px 12px; border-radius: 50px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; margin-bottom: 20px;">
                    <i class="fa-solid fa-shield-halved"></i> High-Security Area
                </span>
                <h2 style="font-size: 28px; font-weight: 800; line-height: 1.3; margin-bottom: 16px;">
                    Operations, Escrow &amp; Specialist Moderation
                </h2>
                <p style="color: #cbd5e1; font-size: 14.5px; line-height: 1.7;">
                    Centralized supervision of booking time-slots, 10% platform protection payouts, and contractor background certifications across Sri Lanka.
                </p>
            </div>

            <div style="border-top: 1px solid rgba(255,255,255,0.15); padding-top: 20px; font-size: 12.5px; color: #94a3b8;">
                <i class="fa-solid fa-lock"></i> Protected by Bcrypt Salted Hashing &amp; Parameterized SQL Guards.
            </div>
        </div>
    </div>

    <script>
    document.getElementById('toggleAdminPass').addEventListener('click', function() {
        const pass = document.getElementById('adminPassword');
        if (pass.type === 'password') {
            pass.type = 'text';
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        } else {
            pass.type = 'password';
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        }
    });
    </script>
</body>
</html>
