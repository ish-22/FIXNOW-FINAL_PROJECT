<?php
include('./config/constant.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $category = trim($_POST['feedback_category'] ?? '');

    if (!empty($category) && $category !== 'General') {
        $message = "[" . $category . "] " . $message;
    }

    if (!empty($name) && !empty($email) && !empty($message)) {
        $sql = "INSERT INTO tbl_user_feedback (name, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            header("Location: " . SITEURL . 'user_feedback.php?status=success');
            exit();
        }
    }
    header("Location: " . SITEURL . 'user_feedback.php?status=error');
    exit();
}
header("Location: " . SITEURL . 'user_feedback.php');
exit();
