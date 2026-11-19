<?php 
include('../config/constant.php');
include('partials/login-check.php');

// Handle status toggle (e.g. Confirm pending vendor) BEFORE any HTML output
if (isset($_GET['action']) && $_GET['action'] == 'confirm' && isset($_GET['id'])) {
    $confirm_id = intval($_GET['id']);
    db_execute($conn, "UPDATE tbl_service_providers SET status = 'Confirmed' WHERE id = ?", [$confirm_id], "i");
    header("Location: manage-vendor.php?status=success&msg=confirmed");
    exit();
}

// Fetch all service providers with service details and town
$providers = db_fetch_all(
    $conn,
    "SELECT sp.*, s.service_type, t.town_name, d.district_name 
     FROM tbl_service_providers sp
     LEFT JOIN tbl_service s ON sp.servicetype_id = s.id
     LEFT JOIN tbl_towns t ON sp.town_id = t.id
     LEFT JOIN tbl_districts d ON sp.district_id = d.id
     ORDER BY sp.id DESC"
);

include('partials/main.php'); 
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Service Specialists &amp; Vetting</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Review specialist applications, verification credentials, and service areas.</p>
        </div>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i> Specialist status updated successfully!
        </div>
    <?php endif; ?>

    <table class="tbl-full">
        <thead>
            <tr>
                <th>Code</th>
                <th>Specialist</th>
                <th>Service Trade</th>
                <th>Region</th>
                <th>Contact</th>
                <th>Fee Paid</th>
                <th>Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($providers)): ?>
                <?php foreach ($providers as $p): 
                    $is_confirmed = ($p['status'] == 'Confirmed');
                    $fee_paid = ($p['payment_status'] == 'Paid' || $p['registration_fee_paid'] == 'Yes');
                ?>
                    <tr>
                        <td style="font-weight: 700; color: #0284c7;"><?php echo htmlspecialchars($p['registration_code'] ?? ('VND-' . $p['id'])); ?></td>
                        <td>
                            <strong style="color: #0f172a; display: block;"><?php echo htmlspecialchars($p['full_name']); ?></strong>
                            <small style="color: #64748b;"><?php echo htmlspecialchars($p['email']); ?></small>
                        </td>
                        <td>
                            <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 13px; font-weight: 600; color: #334155;">
                                <?php echo htmlspecialchars($p['service_type'] ?? 'General Service'); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars(($p['town_name'] ?? 'N/A') . ', ' . ($p['district_name'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($p['contact_no']); ?></td>
                        <td>
                            <span class="status-label <?php echo $fee_paid ? 'status-confirmed' : 'status-pending'; ?>">
                                <i class="fa-solid <?php echo $fee_paid ? 'fa-check' : 'fa-xmark'; ?>"></i>
                                <?php echo $fee_paid ? 'Paid' : 'Pending'; ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-label <?php echo $is_confirmed ? 'status-confirmed' : 'status-pending'; ?>">
                                <?php echo htmlspecialchars($p['status']); ?>
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <?php if (!$is_confirmed): ?>
                                    <a href="manage-vendor.php?action=confirm&id=<?php echo $p['id']; ?>" class="btn-primary" style="padding: 6px 12px; font-size: 12px; background: #10b981;">
                                        <i class="fa-solid fa-check"></i> Approve
                                    </a>
                                <?php endif; ?>
                                <a href="delete-vendor.php?id=<?php echo $p['id']; ?>" class="btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Delete this specialist?');">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align: center; padding: 30px; color: #94a3b8;">No specialists currently on the platform.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
include('partials/footer.php'); 
?>
