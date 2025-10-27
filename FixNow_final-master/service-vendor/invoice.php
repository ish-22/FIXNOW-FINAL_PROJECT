<?php 
 include './partials/headerwithoutcss.php'; 

// Start the session to retrieve vendor_id
if (!isset($_SESSION['vendor_id'])) {
    // Redirect to login page if vendor is not logged in
    header("Location: login.php");
    exit();
}

$vendor_id = $_SESSION['vendor_id']; // Get the vendor_id from the session

// Fetch service provider details from tbl_service_providers
$query_service_provider = "SELECT * FROM tbl_service_providers WHERE id = '$vendor_id'";
$result_service_provider = mysqli_query($conn, $query_service_provider);
$service_provider = mysqli_fetch_assoc($result_service_provider);

// Fetch the fixed payment amount from tbl_payment
$query_payment = "SELECT payment_amount FROM tbl_payment ORDER BY id DESC LIMIT 1"; 
$result_payment = mysqli_query($conn, $query_payment);
$payment = mysqli_fetch_assoc($result_payment);

// Fetch the service type name from tbl_services using service_type_id from tbl_service_providers
$service_type_id = $service_provider['servicetype_id'];
$query_service_type = "SELECT service_type FROM tbl_service WHERE id = '$service_type_id'";
$result_service_type = mysqli_query($conn, $query_service_type);
$service_type = mysqli_fetch_assoc($result_service_type);

// Fetch the status from tbl_invoice for the service provider
$query_invoice = "SELECT status FROM tbl_invoice WHERE service_provider_id = '$vendor_id' ORDER BY id DESC LIMIT 1";
$result_invoice = mysqli_query($conn, $query_invoice);
$invoice = mysqli_fetch_assoc($result_invoice);

// Display the status dynamically
$status = $invoice ? $invoice['status'] : 'Pending';
?>

<link rel="stylesheet" href="./assets/css/custom.css">
<link rel="stylesheet" href="./assets/css/payment_details.css">

<section class="section-payment-details">
    <div class="payment-container">

        <div class="payment-section active">
            <div class="payment-details-box">
                <h2>Payment Information</h2>
                <pre>
    At FixNow, service providers are required to pay a fixed monthly subscription fee for the first three months 
after registration to maintain their active accounts. This payment ensures continued access to the platform and the ability to 
accept bookings from customers. Once the service provider has completed three months of payments,their account will be made permanent, 
allowing them to continue using the platform without further monthly fees.Additionally, after the three-month payment period, 
service providers will have the opportunity to promote their services and enhance their visibility on the platform. 
As part of this promotion, also ,FixNow will actively market vendor profiles through media channels, including social media platforms, 
email campaigns, and targeted advertisements, to help service providers gain more exposure and attract potential customers.If the monthly
 payment is not made by the due date(before the 30th of each month) during the three-month period, the service provider's account 
 will be suspended until the pending payment is completed. During the suspension, 
 providers will not be able to access bookings or manage their profiles.
                </pre>
                <!-- Display payment details dynamically -->
                <p><strong>Monthly Amount:</strong> <?php echo $payment ? $payment['payment_amount'] : 'Not set yet'; ?> /=</p>
                <p><strong>Service Provider Name:</strong> <?php echo htmlspecialchars($service_provider['full_name']); ?></p>
                <p><strong>Service Id:</strong> <?php echo htmlspecialchars($service_provider['id']); ?></p>
                <p><strong>Service Provider Email:</strong> <?php echo htmlspecialchars($service_provider['email']); ?></p>
                <p><strong>Mobile No:</strong> <?php echo htmlspecialchars($service_provider['contact_no']); ?></p>
                <p><strong>Service Type:</strong> <?php echo isset($service_type['service_type']) ? htmlspecialchars($service_type['service_type']) : 'Not available'; ?></p>
            
                <a href="payment.php?service_provider_id=<?php echo $service_provider['id']; ?>" class="payment-swap-btn" id="find-job-btn">I Agree and Make Payment</a>

            </div>
        </div>

</section>

<?php include '../partials/footer.php'; ?>

 