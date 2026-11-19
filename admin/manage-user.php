<?php 
include('partials/main.php'); 

// Fetch Admins
$admins = db_fetch_all($conn, "SELECT * FROM tbl_admin ORDER BY id ASC");

// Fetch Customers
$customers = db_fetch_all($conn, "SELECT c.*, t.town_name, d.district_name FROM tbl_customer c LEFT JOIN tbl_towns t ON c.town_id = t.id LEFT JOIN tbl_districts d ON c.district_id = d.id ORDER BY c.id DESC");
?>

<!-- Section 1: System Administrators -->
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">System Administrators</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Authorized administrator users with platform moderation and oversight privileges.</p>
        </div>
        <a href="add-admin.php" class="btn-primary">
            <i class="fa-solid fa-user-plus"></i> Add New Administrator
        </a>
    </div>

    <table class="tbl-full">
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email Address</th>
                <th>Role</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!empty($admins)) {
                foreach ($admins as $admin) {
            ?>
                <tr>
                    <td style="font-weight: 700; color: #64748b;">#<?php echo $admin['id']; ?></td>
                    <td><strong style="color: #0f172a;"><?php echo htmlspecialchars($admin['full_name']); ?></strong></td>
                    <td><span style="background: #e0f2fe; color: #0284c7; padding: 3px 8px; border-radius: 4px; font-weight: 700; font-size: 13px;">@<?php echo htmlspecialchars($admin['username']); ?></span></td>
                    <td><?php echo htmlspecialchars($admin['email']); ?></td>
                    <td><span class="status-label status-completed">Super Admin</span></td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="update-admin.php?id=<?php echo $admin['id']; ?>" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <?php if ($admin['id'] != 1): ?>
                            <a href="delete-admin.php?id=<?php echo $admin['id']; ?>" class="btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Remove admin access for this user?');">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>No administrators found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Section 2: Registered Customers -->
<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Registered Customers</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Verified residential and commercial customers placing service requests.</p>
        </div>
    </div>

    <table class="tbl-full">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Full Name</th>
                <th>Email Address</th>
                <th>Contact</th>
                <th>Town / District</th>
                <th>Joined Date</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if (!empty($customers)) {
                foreach ($customers as $c) {
            ?>
                <tr>
                    <td style="font-weight: 700; color: #0284c7;">#CUS-<?php echo $c['id']; ?></td>
                    <td><strong style="color: #0f172a;"><?php echo htmlspecialchars($c['cus_name']); ?></strong></td>
                    <td><?php echo htmlspecialchars($c['cus_email']); ?></td>
                    <td><?php echo htmlspecialchars($c['cus_phone']); ?></td>
                    <td><?php echo htmlspecialchars(($c['town_name'] ?? 'N/A') . ', ' . ($c['district_name'] ?? '')); ?></td>
                    <td style="color: #64748b; font-size: 13px;">
                        <?php echo !empty($c['created_at']) ? date('M d, Y', strtotime($c['created_at'])) : 'Active'; ?>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='6' style='text-align: center; padding: 25px; color: #94a3b8;'>No customers registered yet.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php 
include('partials/footer.php'); 
?>
