<?php
include('./config/constant.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    $sql = "INSERT INTO tbl_user_feedback (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        header("Location: " . SITEURL . 'index.php?status=success&action=success');
        exit();
    } else {
        header("Location: " . SITEURL . 'index.php?status=error&action=success');
    }
}
