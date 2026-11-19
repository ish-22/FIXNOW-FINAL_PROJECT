<?php
include('../config/constant.php');
include('partials/login-check.php');

if (isset($_GET['id'])) {
    $offer_id = intval($_GET['id']);

    // Fetch image path to remove file
    $offer = db_fetch_one($conn, "SELECT offer_image FROM tbl_offer WHERE id = ?", [$offer_id], "i");
    if ($offer && !empty($offer['offer_image'])) {
        $img_path = "../images/offer_images/" . $offer['offer_image'];
        if (file_exists($img_path)) {
            @unlink($img_path);
        }
    }

    $deleted = db_execute($conn, "DELETE FROM tbl_offer WHERE id = ?", [$offer_id], "i");

    if ($deleted) {
        header("Location: manage-offers.php?status=success&action=delete-offer");
    } else {
        header("Location: manage-offers.php?status=error&action=delete-offer");
    }
    exit();
} else {
    header("Location: manage-offers.php");
    exit();
}
?>
