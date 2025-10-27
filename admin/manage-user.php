
            <?php 
include('partials/main.php'); 
?>

<div class="main-content">
    <div class="wrapper">
        <h1 class="main-welcome">Manage Admin</h1>
        <br />

        <!-- Button to add admins -->
        <a href="add-admin.php" class="btn-primary">Add Admin</a>

        <br />
        <br />

        <!-- Table to display admins -->
        <table class="tbl-full">
            <tr>
                <th>S.N</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            
            <?php
            // SQL query to get all data from tbl_admin
            $sql = "SELECT * FROM tbl_admin";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Check if there are any results
            if ($result) {
                $count = $result->num_rows;
                $sn = 1;
                if ($count > 0) {
                    while ($rows = $result->fetch_assoc()) {
                        // Fetching data for each row
                        $id = $rows['id'];
                        $full_name = $rows['full_name'];
                        $username = $rows['username'];
                        $email = $rows['email'];
            ?>

                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $full_name; ?></td>
                            <td><?php echo $username; ?></td>
                            <td><?php echo $email; ?></td>
                            <td>
                                <!-- Update and Delete buttons with links -->
                                <a href="update-admin.php?id=<?php echo $id; ?>" class="btn-secondary">Update Admin</a>
                                <a href="delete-admin.php?id=<?php echo $id; ?>" class="btn-danger">Delete Admin</a>
                            </td>
                        </tr>

            <?php
                    }
                } else {
                    echo "<tr><td colspan='6'>No admins found.</td></tr>";
                }
            }
            ?>
        </table>











        <br><br>
        <h1 class="main-welcome">Customer Details</h1>
        <table class="tbl-full">
            <tr>
                <th>S.N</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Address</th>
                <th>Contacts</th>
            </tr>

            <?php
            // SQL query to fetch customer details from tbl_customer
            $sql_cus = "SELECT * FROM tbl_customer";
            $stmt_cus = $conn->prepare($sql_cus);
            $stmt_cus->execute();
            $result_cus = $stmt_cus->get_result();

            // Check if customers exist
            if ($result_cus) {
                $sn = 1;
                if ($result_cus->num_rows > 0) {
                    while ($row_cus = $result_cus->fetch_assoc()) {
                        // Fetching data
                        $full_name = $row_cus['cus_name'];
                        $email = $row_cus['cus_email'];
                        $address = $row_cus['cus_address'];// Generate username
                        $contact = $row_cus['cus_phone'];
            ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $full_name; ?></td>
                            <td><?php echo $email; ?></td>
                            <td><?php echo $address; ?></td>
                            <td><?php echo $contact; ?></td>
                        </tr>
            <?php
                    }
                } else {
                    echo "<tr><td colspan='5'>No customers found.</td></tr>";
                }
            }
            ?>
        </table>

    </div>
</div>

<?php include('partials/footer.php'); ?>