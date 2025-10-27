<?php 
include('partials/main.php'); 

// Handle Add District
if (isset($_POST['submit-district'])) {
    $district_name = $_POST['district_name'];

    // Insert district into tbl_districts
    $sql = "INSERT INTO tbl_districts (district_name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $district_name);

    if ($stmt->execute()) {
        header("Location: " . SITEURL . 'admin/add-locations.php?status=success&action=add-district');
    } else {
        header("Location: " . SITEURL . 'admin/add-locations.php?status=error&action=add-district');
    }
}

// Handle Add Town
if (isset($_POST['submit-town'])) {
    $district_id = $_POST['district_id'];
    $town_name = $_POST['town_name'];

    // Insert town into tbl_towns
    $sql = "INSERT INTO tbl_towns (town_name, district_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $town_name, $district_id);

    if ($stmt->execute()) {
        header("Location: " . SITEURL . 'admin/add-locations.php?status=success&action=add-town');
    } else {
        header("Location: " . SITEURL . 'admin/add-locations.php?status=error&action=add-town');
    }
}
?>

<div class="main-content">
    <div class="wrapper">
        <br />

        <!-- Form for Add District -->
        <form class="form-container" action="" method="POST">
            <h2 class="text_center">Add District</h2>
            <div class="input-field">
                <label for="district-name">Enter District Name:</label>
                <input type="text" id="district-name" name="district_name" placeholder="Enter District Name" required />
            </div>
            <div class="input-field">
                <input type="submit" name="submit-district" value="Add District" class="btn-primary" />
            </div>
        </form>

        <br />

        <!-- Form for Add Town -->
        <form class="form-container"action="" method="POST">
            <h2 class="text_center">Add Town</h2>
            <div class="input-field">
                <label for="district-select">Select District:</label>
                <select id="district-select" name="district_id" required>
                    <?php
                    // Fetch districts from tbl_districts
                    $sql = "SELECT * FROM tbl_districts";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['district_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="input-field">
                <label for="town-name">Enter Town Name:</label>
                <input type="text" id="town-name" name="town_name" placeholder="Enter Town Name" required />
            </div>

            <div class="input-field">
                <input type="submit" name="submit-town" value="Add Town" class="btn-primary" />
            </div>
        </form>
    </div>
</div>

<?php include('partials/footer.php'); ?>
