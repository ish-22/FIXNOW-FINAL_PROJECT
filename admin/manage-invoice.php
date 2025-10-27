<?php 
include('partials/main.php'); 
?>

<div class="main-content">
    <h2 class="main-welcome">Manage Invoices</h2>

    <!-- Filter Form -->
    <form action="" method="GET" class="filter-form">
        <select name="provider_id" id="provider_id" class="btn-primary">
            <option value="">Select a Provider</option>
            <?php
            // Fetch all service providers
            $provider_query = "SELECT id, full_name FROM tbl_service_providers";
            $provider_result = mysqli_query($conn, $provider_query);

            // Populate the dropdown with providers
            if ($provider_result && mysqli_num_rows($provider_result) > 0) {
                while ($provider_row = mysqli_fetch_assoc($provider_result)) {
                    $selected = (isset($_GET['provider_id']) && $_GET['provider_id'] == $provider_row['id']) ? 'selected' : '';
                    echo "<option value='" . $provider_row['id'] . "' $selected>" . $provider_row['full_name'] . " (ID: " . $provider_row['id'] . ")</option>";
                }
            }
            ?>
        </select>
        <input type="submit" value="Filter" class="btn-primary" />
    </form>

    <br />
    <a href="add-amount.php" class="btn-primary">Add payment fee</a>
    <br />
    <br />

    <!-- Invoice Table -->
    <table class="tbl-full">
        <tr>
            <th>S.N</th>
            <th>Provider ID</th>
            <th>Service Type</th>
            <th>Provider Name</th>
            <th>Provider Email</th>
            <th>Contact</th>
            <th>Registration Date</th>
            <th>Paid Month</th>
            <th>Paid Amount</th>
            <th style="width: 200px;">Actions</th>
        </tr>

        <?php
        // Get the filter value from the GET request
        $provider_id_filter = isset($_GET['provider_id']) ? mysqli_real_escape_string($conn, $_GET['provider_id']) : '';

        // SQL Query for fetching invoice data with filter if provided
        $sql = "SELECT 
                    i.id AS invoice_id,
                    i.service_provider_id,
                    i.service_type_id,
                    i.mobile_no,
                    i.email,
                    i.paid_month,
                    i.created_at AS paid_date,
                    i.paid_amount,
                    i.message,  
                    sp.full_name AS provider_name,
                    sp.created_at AS registration_date, 
                    s.service_type AS service_type
                FROM tbl_invoice i
                LEFT JOIN tbl_service_providers sp ON i.service_provider_id = sp.id
                LEFT JOIN tbl_service s ON i.service_type_id = s.id";

        // Apply filter if provider_id is provided
        if ($provider_id_filter != '') {
            $sql .= " WHERE i.service_provider_id = '$provider_id_filter'";
        }

        // Execute the query
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $sn = 1; // Serial Number
            while ($row = mysqli_fetch_assoc($result)) {
                // Determine if the invoice has a message (i.e., if it has been sent)
                $is_message_filled = !empty($row['message']);
                ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo $row['service_provider_id']; ?></td>
                    <td><?php echo $row['service_type'] ? $row['service_type'] : 'N/A'; ?></td>
                    <td><?php echo $row['provider_name'] ? $row['provider_name'] : 'N/A'; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['mobile_no']; ?></td>
                    <td><?php echo date('Y-m-d', strtotime($row['registration_date'])); ?></td>
                    <td><?php echo $row['paid_month'] ? $row['paid_month'] : 'N/A'; ?></td>
                    <td><?php echo number_format($row['paid_amount'], 2); ?>/=</td>
                    <td>
                        <?php if (!$is_message_filled) { ?>
                            <a href="send-invoice.php?id=<?php echo $row['invoice_id']; ?>" class="btn-secondary">Send </a>
                        <?php } ?>
                        <a href="delete-invoice.php?id=<?php echo $row['invoice_id']; ?>" class="btn-danger">Delete</a>
                    </td>
                </tr>
                <?php
            }
        } else {
            // Display a message if no data is found
            echo "<tr><td colspan='10'>No Invoices Found.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include('partials/footer.php'); ?>
