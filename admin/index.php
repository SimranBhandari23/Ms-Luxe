<?php include('header.php'); ?>

<?php if (!isset($_SESSION['admin_logged_in'])) {
    header('location:login.php');
    exit();
}
?>
<?php

// Dashboard statistics
$total_users = 0;
$total_orders = 0;
$total_revenue = 0;
$pending_orders = 0;

$stmt_stats = $conn->prepare("SELECT COUNT(*) FROM users");
$stmt_stats->execute();
$stmt_stats->bind_result($total_users);
$stmt_stats->fetch();
$stmt_stats->close();

$stmt_stats = $conn->prepare("SELECT COUNT(*), COALESCE(SUM(order_cost), 0) FROM orders");
$stmt_stats->execute();
$stmt_stats->bind_result($total_orders, $total_revenue);
$stmt_stats->fetch();
$stmt_stats->close();

$stmt_stats = $conn->prepare("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'");
$stmt_stats->execute();
$stmt_stats->bind_result($pending_orders);
$stmt_stats->fetch();
$stmt_stats->close();

// Orders per day (last 30 days)
$orders_date = [];
$orders_date_count = [];
$stmt_charts = $conn->prepare("SELECT DATE(order_date) AS order_day, COUNT(*) AS order_count FROM orders GROUP BY DATE(order_date) ORDER BY DATE(order_date) DESC LIMIT 30");
$stmt_charts->execute();
$result_charts = $stmt_charts->get_result();
while ($row = $result_charts->fetch_assoc()) {
    $orders_date[] = $row['order_day'];
    $orders_date_count[] = $row['order_count'];
}
$stmt_charts->close();

// Order status distribution
$status_labels = [];
$status_counts = [];
$stmt_status = $conn->prepare("SELECT order_status, COUNT(*) AS status_count FROM orders GROUP BY order_status");
$stmt_status->execute();
$result_status = $stmt_status->get_result();
while ($row = $result_status->fetch_assoc()) {
    $status_labels[] = $row['order_status'];
    $status_counts[] = $row['status_count'];
}
$stmt_status->close();

// Recent orders data for quick summary (if needed)
$stmt_recent = $conn->prepare("SELECT order_id, user_id, order_date, order_cost, order_status FROM orders ORDER BY order_date DESC LIMIT 10");
$stmt_recent->execute();
$recent_orders = $stmt_recent->get_result();






?>

<body>
    
<div class="container-fluid">
    <div class="row" style="min-height: 1000px">




        <?php include('sidemenu.php'); ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2"></div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white" style="background-color:#d3acb3;">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text display-6"><?php echo number_format($total_users); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background-color:#6c757d;">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <p class="card-text display-6"><?php echo number_format($total_orders); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background-color:#198754;">
                        <div class="card-body">
                            <h5 class="card-title">Total Revenue</h5>
                            <p class="card-text display-6">₹ <?php echo number_format($total_revenue, 2); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background-color:#ffc107;">
                        <div class="card-body">
                            <h5 class="card-title">Pending Orders</h5>
                            <p class="card-text display-6"><?php echo number_format($pending_orders); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4>Orders Per Day</h4>
                            <canvas id="ordersBarChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4>Order Status Distribution</h4>
                            <canvas id="orderStatusPie" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </main>


    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const orderDates = <?php echo json_encode(array_reverse($orders_date)); ?>;
const orderCounts = <?php echo json_encode(array_reverse($orders_date_count)); ?>;
const statusLabels = <?php echo json_encode($status_labels); ?>;
const statusCounts = <?php echo json_encode($status_counts); ?>;

const ctxBar = document.getElementById('ordersBarChart').getContext('2d');
new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: orderDates,
        datasets: [{
            label: 'Orders',
            data: orderCounts,
            backgroundColor: '#d3acb3',
            borderColor: '#a86d8f',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});

const ctxPie = document.getElementById('orderStatusPie').getContext('2d');
new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: statusLabels,
        datasets: [{
            data: statusCounts,
            backgroundColor: ['#ffc107', '#198754', '#dc3545', '#0d6efd'],
            borderColor: '#fff',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.2/dist/feather.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
    
    
