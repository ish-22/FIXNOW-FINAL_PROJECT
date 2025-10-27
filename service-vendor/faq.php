<?php
ob_start(); 

include './partials/headerwithoutcss.php'; 

$query = "SELECT * FROM tbl_contact ORDER BY created_at DESC"; 
$result = mysqli_query($conn, $query);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['id']) && isset($_POST['reply'])) {
      $id = mysqli_real_escape_string($conn, $_POST['id']);
      $reply = mysqli_real_escape_string($conn, $_POST['reply']);

      $update_query = "UPDATE tbl_contact SET reply = '$reply' WHERE id = $id";
      if (mysqli_query($conn, $update_query)) {
        header("Location: " . SITEURL . 'service-vendor/faq.php?status=success&action=reply_message');
          exit();
      } else {
          echo "Error updating reply: " . mysqli_error($conn);
      }
  } else {
      echo "Error: Missing required parameters.";
  }
}
?>

<link rel="stylesheet" href="./assets/css/custom.css">
<link rel="stylesheet" href="./assets/css/question.css">

<div class="container_fqa">
    <h1>Contact (FAQ) Messages</h1>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Message</th>
                <th>Reply</th>
            
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td>
                        <?php if (empty($row['reply'])): ?>
                            <form action="" method="POST">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <input type="text" name="reply" class="reply-input" placeholder="Add a reply">
                                <button type="submit" class="btn btn-reply">Reply</button>
                            </form>
                        <?php else: ?>
                            <span>Reply Sent</span>
                        <?php endif; ?>
                    </td>
                 
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php 
ob_end_flush(); // End output buffering and flush the output
include './partials/footer.php'; 
?>
