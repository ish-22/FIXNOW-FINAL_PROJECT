<?php
// Database connection
include('../config/constant.php');

// Fetch data for analysis
// Booking statistics
$booking_status_query = "SELECT booking_status, COUNT(*) as count FROM tbl_booking GROUP BY booking_status";
$booking_status_result = mysqli_query($conn, $booking_status_query);

// Payment status
$payment_status_query = "SELECT payment_status, COUNT(*) as count FROM tbl_booking GROUP BY payment_status";
$payment_status_result = mysqli_query($conn, $payment_status_query);

// Service popularity
$service_query = "SELECT s.service_type, COUNT(b.id) as count 
                 FROM tbl_service s 
                 LEFT JOIN tbl_booking b ON s.id = b.service_id 
                 GROUP BY s.id, s.service_type";
$service_result = mysqli_query($conn, $service_query);

// Total counts
$total_bookings = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_booking"));
$total_customers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_customer"));
$total_providers = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_service_providers"));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color:rgb(211, 137, 18);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background-color:rgb(103, 84, 3);
        }

        .count-box {
            text-align: center;
            padding: 20px;
        }

        .count-box h3 {
            color: #333;
            margin-bottom: 10px;
        }

        .count-box .number {
            font-size: 2.5em;
            color: #2c3e50;
            font-weight: bold;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 10px;
            backdrop-filter: blur(5px);
        }

        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .chart-container {
                height: 250px;
            }
        }
    </style>
</head>
<body>
<button class="back-button" onclick="window.location.href='admin-index.php'">← Back</button>


    <div class="container">
        <h1>Analysis Dashboard</h1>
        
        <div class="dashboard">
            <!-- Count Boxes -->
            <div class="card count-box">
                <h3>Total Bookings</h3>
                <div class="number"><?php echo $total_bookings; ?></div>
            </div>
            
            <div class="card count-box">
                <h3>Total Customers</h3>
                <div class="number"><?php echo $total_customers; ?></div>
            </div>
            
            <div class="card count-box">
                <h3>Total Providers</h3>
                <div class="number"><?php echo $total_providers; ?></div>
            </div>
            
            <!-- Booking Status Bar Chart -->
            <div class="card">
                <div class="chart-container">
                    <canvas id="bookingStatusChart"></canvas>
                </div>
            </div>
            
            <!-- Payment Status Pie Chart -->
            <div class="card">
                <div class="chart-container">
                    <canvas id="paymentStatusChart"></canvas>
                </div>
            </div>
            
            <!-- Service Popularity Bar Chart -->
            <div class="card">
                <div class="chart-container">
                    <canvas id="servicePopularityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Booking Status Chart
        const bookingStatusCtx = document.getElementById('bookingStatusChart').getContext('2d');
        const bookingStatusData = {
            labels: [<?php 
                $labels = [];
                while ($row = mysqli_fetch_assoc($booking_status_result)) {
                    $labels[] = "'{$row['booking_status']}'";
                }
                echo implode(',', $labels);
            ?>],
            datasets: [{
                label: 'Booking Status',
                data: [<?php 
                    mysqli_data_seek($booking_status_result, 0);
                    $data = [];
                    while ($row = mysqli_fetch_assoc($booking_status_result)) {
                        $data[] = $row['count'];
                    }
                    echo implode(',', $data);
                ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ]
            }]
        };
        
        new Chart(bookingStatusCtx, {
            type: 'bar',
            data: bookingStatusData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    title: { display: true, text: 'Booking Status Distribution' }
                }
            }
        });

// JavaScript Code
const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');

const paymentStatusData = {
    labels: <?php 
        $labels = [];
        while ($row = mysqli_fetch_assoc($payment_status_result)) {
            $labels[] = $row['payment_status'];
        }
        echo json_encode($labels); // Properly formatted JSON output
    ?>,
    datasets: [{
        data: <?php 
            mysqli_data_seek($payment_status_result, 0);
            $data = [];
            while ($row = mysqli_fetch_assoc($payment_status_result)) {
                $data[] = $row['count'];
            }
            echo json_encode($data); // Properly formatted JSON output
        ?>,
        backgroundColor: [
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)'
        ]
    }]
};

        new Chart(paymentStatusCtx, {
            type: 'pie',
            data: paymentStatusData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: { display: true, text: 'Payment Status Distribution' }
                }
            }
        });

        // Service Popularity Chart
        const servicePopularityCtx = document.getElementById('servicePopularityChart').getContext('2d');
        const servicePopularityData = {
            labels: [<?php 
                $labels = [];
                while ($row = mysqli_fetch_assoc($service_result)) {
                    $labels[] = "'{$row['service_type']}'";
                }
                echo implode(',', $labels);
            ?>],
            datasets: [{
                label: 'Number of Bookings',
                data: [<?php 
                    mysqli_data_seek($service_result, 0);
                    $data = [];
                    while ($row = mysqli_fetch_assoc($service_result)) {
                        $data[] = $row['count'];
                    }
                    echo implode(',', $data);
                ?>],
                backgroundColor: 'rgba(153, 102, 255, 0.6)'
            }]
        };
        
        new Chart(servicePopularityCtx, {
            type: 'bar',
            data: servicePopularityData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    title: { display: true, text: 'Service Popularity' }
                }
            }
        });
    </script>
</body>
</html>

<?php mysqli_close($conn);?>