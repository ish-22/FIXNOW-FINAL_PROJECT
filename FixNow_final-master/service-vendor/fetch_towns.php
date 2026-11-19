<?php
include('../config/constant.php'); 

if (isset($_POST['district_id'])) {
    $district_id = intval($_POST['district_id']);

    $towns = db_fetch_all($conn, "SELECT id, town_name FROM tbl_towns WHERE district_id = ? ORDER BY town_name ASC", [$district_id], "i");

    if (!empty($towns)) {
        echo "<option value=''>Select Town</option>";
        foreach ($towns as $town) {
            echo "<option value='" . htmlspecialchars($town['id']) . "'>" . htmlspecialchars($town['town_name']) . "</option>";
        }
    } else {
        echo "<option value=''>No towns available</option>";
    }
}
?>
