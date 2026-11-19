<?php 
include('../config/constant.php');
include('partials/login-check.php');

// Handle submitting a reply BEFORE any HTML output
if (isset($_POST['submit_reply']) && isset($_POST['contact_id'])) {
    $c_id = intval($_POST['contact_id']);
    $reply_msg = trim($_POST['reply_message'] ?? '');
    db_execute($conn, "UPDATE tbl_contact SET reply = ? WHERE id = ?", [$reply_msg, $c_id], "si");
    header("Location: manage-question.php?status=success&action=reply");
    exit();
}

// Fetch all contact messages
$contacts = db_fetch_all($conn, "SELECT * FROM tbl_contact ORDER BY id DESC");

include('partials/main.php'); 
?>

<div class="admin-card">
    <div class="admin-card-header">
        <div>
            <h2 class="main-welcome" style="margin-bottom: 4px;">Customer Inquiries &amp; Support Messages</h2>
            <p style="color: var(--fn-text-muted); font-size: 14px; margin: 0;">Direct inquiries submitted through the public Contact Support form.</p>
        </div>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="alert-success">
            <i class="fa-solid fa-circle-check"></i> Inquiry updated successfully!
        </div>
    <?php endif; ?>

    <table class="tbl-full">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sender</th>
                <th>Contact</th>
                <th>Message Content</th>
                <th>Admin Response</th>
                <th>Date</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($contacts)): ?>
                <?php foreach ($contacts as $c): ?>
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">#<?php echo $c['id']; ?></td>
                        <td>
                            <strong style="color: #0f172a; display: block;"><?php echo htmlspecialchars($c['name']); ?></strong>
                            <small style="color: #64748b;"><?php echo htmlspecialchars($c['email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($c['phone']); ?></td>
                        <td style="max-width: 280px; font-size: 13.5px; line-height: 1.5;">
                            <?php echo nl2br(htmlspecialchars($c['message'])); ?>
                        </td>
                        <td style="max-width: 250px;">
                            <?php if (!empty($c['reply'])): ?>
                                <div style="background: #f0fdf4; border-left: 3px solid #10b981; padding: 8px 12px; border-radius: 4px; font-size: 13px; color: #166534;">
                                    <strong>Admin:</strong> <?php echo htmlspecialchars($c['reply']); ?>
                                </div>
                            <?php else: ?>
                                <span class="status-label status-pending">Unanswered</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size: 12.5px; color: #64748b; white-space: nowrap;">
                            <?php echo date('M d, Y', strtotime($c['created_at'])); ?>
                        </td>
                        <td style="text-align: right; white-space: nowrap;">
                            <form action="" method="POST" style="display: inline-block;">
                                <input type="hidden" name="contact_id" value="<?php echo $c['id']; ?>">
                                <input type="text" name="reply_message" placeholder="Type reply..." style="padding: 6px 10px; border-radius: 6px; border: 1px solid var(--fn-border); font-size: 12px; width: 140px;" required>
                                <button type="submit" name="submit_reply" class="btn-primary" style="padding: 6px 10px; font-size: 12px;">
                                    <i class="fa-solid fa-reply"></i>
                                </button>
                            </form>
                            <a href="delete-message.php?id=<?php echo $c['id']; ?>" class="btn-danger" style="padding: 6px 10px; font-size: 12px;" onclick="return confirm('Delete this inquiry?');">
                                <i class="fa-solid fa-trash-can"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">No customer messages found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
include('partials/footer.php'); 
?>
