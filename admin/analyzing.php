<?php
include('partials/main.php');

// Fetch data for analysis
$booking_status_query = "SELECT booking_status, COUNT(*) as count FROM tbl_booking GROUP BY booking_status";
$booking_status_result = mysqli_query($conn, $booking_status_query);

$payment_status_query = "SELECT payment_status, COUNT(*) as count FROM tbl_booking GROUP BY payment_status";
$payment_status_result = mysqli_query($conn, $payment_status_query);

$service_query = "SELECT s.service_type, COUNT(b.id) as count 
                 FROM tbl_service s 
                 LEFT JOIN tbl_booking b ON s.id = b.service_id 
                 GROUP BY s.id, s.service_type";
$service_result = mysqli_query($conn, $service_query);

// Total counts
$total_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tbl_booking"));
$total_customers = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tbl_customer"));
$total_providers = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM tbl_service_providers"));
$total_escrow_res = db_fetch_one($conn, "SELECT SUM(total_amount) as total FROM tbl_booking WHERE payment_status = 'Paid'");
$total_escrow = $total_escrow_res ? floatval($total_escrow_res['total']) : 0;
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Top Analytics Summary Cards -->
<div class="admin-stats-grid">
    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-title">Platform Bookings</span>
            <div class="admin-stat-icon primary"><i class="fa-solid fa-calendar-check"></i></div>
        </div>
        <div class="admin-stat-value"><?php echo $total_bookings; ?></div>
        <div class="admin-stat-footer"><i class="fa-solid fa-arrow-trend-up" style="color:#10b981;"></i> Lifetime orders placed</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-title">Registered Clients</span>
            <div class="admin-stat-icon success"><i class="fa-solid fa-users"></i></div>
        </div>
        <div class="admin-stat-value" style="color: #059669;"><?php echo $total_customers; ?></div>
        <div class="admin-stat-footer"><i class="fa-solid fa-house" style="color:#10b981;"></i> Verified households</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-title">Active Specialists</span>
            <div class="admin-stat-icon warning"><i class="fa-solid fa-screwdriver-wrench"></i></div>
        </div>
        <div class="admin-stat-value" style="color: #d97706;"><?php echo $total_providers; ?></div>
        <div class="admin-stat-footer"><i class="fa-solid fa-id-badge" style="color:#f59e0b;"></i> Vetted trade contractors</div>
    </div>

    <div class="admin-stat-card">
        <div class="admin-stat-header">
            <span class="admin-stat-title">Cumulative Escrow</span>
            <div class="admin-stat-icon info"><i class="fa-solid fa-vault"></i></div>
        </div>
        <div class="admin-stat-value" style="color: #4f46e5;">Rs. <?php echo number_format($total_escrow, 2); ?></div>
        <div class="admin-stat-footer"><i class="fa-solid fa-shield-check" style="color:#6366f1;"></i> 100% Escrow protected</div>
    </div>
</div>

<!-- Charts Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 24px; margin-bottom: 30px;">
    <!-- Chart 1: Booking Status -->
    <div class="admin-card" style="margin-bottom: 0;">
        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
            <i class="fa-solid fa-chart-column" style="color:#0284c7; margin-right: 8px;"></i> Booking Order Distribution
        </h3>
        <div style="height: 300px; position: relative;">
            <canvas id="bookingStatusChart"></canvas>
        </div>
    </div>

    <!-- Chart 2: Payment Escrow Status -->
    <div class="admin-card" style="margin-bottom: 0;">
        <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
            <i class="fa-solid fa-chart-pie" style="color:#10b981; margin-right: 8px;"></i> Payment &amp; Escrow Settlement
        </h3>
        <div style="height: 300px; position: relative;">
            <canvas id="paymentStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Full Width Chart: Service Popularity -->
<div class="admin-card">
    <h3 style="font-size: 17px; font-weight: 700; color: #0f172a; margin-bottom: 16px;">
        <i class="fa-solid fa-chart-bar" style="color:#6366f1; margin-right: 8px;"></i> Trade Demand &amp; Service Category Popularity
    </h3>
    <div style="height: 340px; position: relative;">
        <canvas id="servicePopularityChart"></canvas>
    </div>
</div>

<script>
// Chart 1: Booking Status Distribution
const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
const bookingStatusData = {
    labels: [<?php 
        $labels = [];
        mysqli_data_seek($booking_status_result, 0);
        while ($row = mysqli_fetch_assoc($booking_status_result)) {
            $labels[] = "'" . addslashes($row['booking_status']) . "'";
        }
        echo implode(',', $labels);
    ?>],
    datasets: [{
        label: 'Orders',
        data: [<?php 
            $data = [];
            mysqli_data_seek($booking_status_result, 0);
            while ($row = mysqli_fetch_assoc($booking_status_result)) {
                $data[] = $row['count'];
            }
            echo implode(',', $data);
        ?>],
        backgroundColor: [
            'rgba(2, 132, 199, 0.7)',
            'rgba(16, 185, 129, 0.7)',
            'rgba(245, 158, 11, 0.7)',
            'rgba(239, 68, 68, 0.7)'
        ],
        borderRadius: 8
    }]
};

new Chart(bookingStatusCtx, {
    type: 'bar',
    data: bookingStatusData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});

// Chart 2: Payment Escrow Status Distribution
const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
const paymentStatusData = {
    labels: [<?php 
        $labels = [];
        mysqli_data_seek($payment_status_result, 0);
        while ($row = mysqli_fetch_assoc($payment_status_result)) {
            $labels[] = "'" . addslashes($row['payment_status']) . "'";
        }
        echo implode(',', $labels);
    ?>],
    datasets: [{
        data: [<?php 
            $data = [];
            mysqli_data_seek($payment_status_result, 0);
            while ($row = mysqli_fetch_assoc($payment_status_result)) {
                $data[] = $row['count'];
            }
            echo implode(',', $data);
        ?>],
        backgroundColor: [
            'rgba(16, 185, 129, 0.8)',
            'rgba(245, 158, 11, 0.8)',
            'rgba(239, 68, 68, 0.8)'
        ]
    }]
};

new Chart(paymentStatusCtx, {
    type: 'doughnut',
    data: paymentStatusData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// Chart 3: Service Popularity
const servicePopularityCtx = document.getElementById('servicePopularityChart').getContext('2d');
const servicePopularityData = {
    labels: [<?php 
        $labels = [];
        mysqli_data_seek($service_result, 0);
        while ($row = mysqli_fetch_assoc($service_result)) {
            $labels[] = "'" . addslashes($row['service_type']) . "'";
        }
        echo implode(',', $labels);
    ?>],
    datasets: [{
        label: 'Total Bookings',
        data: [<?php 
            $data = [];
            mysqli_data_seek($service_result, 0);
            while ($row = mysqli_fetch_assoc($service_result)) {
                $data[] = $row['count'];
            }
            echo implode(',', $data);
        ?>],
        backgroundColor: 'rgba(99, 102, 241, 0.75)',
        borderRadius: 8
    }]
};

new Chart(servicePopularityCtx, {
    type: 'bar',
    data: servicePopularityData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
});
</script>

<?php 
include('partials/footer.php'); 
?>
