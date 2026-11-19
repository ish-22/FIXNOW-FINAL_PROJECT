<?php 
include('partials/main.php'); 

// Get provider filter if set
$provider_id_filter = isset($_GET['provider_id']) ? intval($_GET['provider_id']) : 0;

// Build query
$sql = "SELECT 
            i.id AS invoice_id,
            i.service_provider_id,
            i.service_type_id,
            i.mobile_no,
            i.email,
            i.paid_month,
            i.paid_amount,
            i.status,
            i.created_at,
            sp.full_name AS provider_name,
            s.service_type
        FROM tbl_invoice i
        JOIN tbl_service_providers sp ON i.service_provider_id = sp.id
        LEFT JOIN tbl_service s ON i.service_type_id = s.id";

if ($provider_id_filter > 0) {
    $sql .= " WHERE i.service_provider_id = " . $provider_id_filter;
}
$sql .= " ORDER BY i.id DESC";
$invoice_result = mysqli_query($conn, $sql);

// Fetch providers for dropdown
$providers_list = db_fetch_all($conn, "SELECT id, full_name FROM tbl_service_providers ORDER BY full_name ASC");
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Vendor Invoices &amp; Subscriptions</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Track monthly recurring vendor platform dues and registration payments.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
            <form action="" method="GET" style="display: flex; gap: 8px;">
                <select name="provider_id" style="padding: 8px 14px; border-radius: 8px; border: 1px solid var(--fn-border); font-size: 13.5px; background: #ffffff;">
                    <option value="">All Specialists</option>
                    <?php foreach ($providers_list as $pl): ?>
                        <option value="<?php echo $pl['id']; ?>" <?php echo ($provider_id_filter == $pl['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($pl['full_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn-secondary">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
            </form>
            <a href="add-amount.php" class="btn-primary">
                <i class="fa-solid fa-coins"></i> Subscription Rates
            </a>
        </div>
    </div>

    <table class="tbl-full">
        <thead>
            <tr>
                <th>Invoice #</th>
                <th>Specialist</th>
                <th>Trade Type</th>
                <th>Contact</th>
                <th>Billing Period</th>
                <th>Amount Paid</th>
                <th>Payment Status</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($invoice_result && mysqli_num_rows($invoice_result) > 0) {
                while ($inv = mysqli_fetch_assoc($invoice_result)) {
                    $is_paid = (strtolower($inv['status']) == 'paid');
            ?>
            <tr>
                <td style="font-weight: 700; color: #0284c7;">#INV-<?php echo $inv['invoice_id']; ?></td>
                <td>
                    <strong style="color: #0f172a; display: block;"><?php echo htmlspecialchars($inv['provider_name']); ?></strong>
                    <small style="color: #64748b;">ID: <?php echo $inv['service_provider_id']; ?></small>
                </td>
                <td><?php echo htmlspecialchars($inv['service_type'] ?? 'General Service'); ?></td>
                <td>
                    <div><?php echo htmlspecialchars($inv['mobile_no']); ?></div>
                    <small style="color: #64748b;"><?php echo htmlspecialchars($inv['email']); ?></small>
                </td>
                <td><strong style="color: #334155;"><?php echo htmlspecialchars($inv['paid_month'] ?? 'Current Cycle'); ?></strong></td>
                <td style="font-weight: 800; color: #10b981;">Rs. <?php echo number_format($inv['paid_amount'], 2); ?></td>
                <td>
                    <span class="status-label <?php echo $is_paid ? 'status-confirmed' : 'status-pending'; ?>">
                        <?php echo htmlspecialchars(ucfirst($inv['status'])); ?>
                    </span>
                </td>
                <td style="text-align: right;">
                    <a href="delete-invoice.php?id=<?php echo $inv['invoice_id']; ?>" class="btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Delete this invoice record?');">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </td>
            </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='8' style='text-align: center; padding: 30px; color: #94a3b8;'>No invoices found matching criteria.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<?php 
include('partials/footer.php'); 
?>
