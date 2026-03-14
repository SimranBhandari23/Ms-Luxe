<?php include('header.php'); ?>

<?php if (!isset($_SESSION['admin_logged_in'])) {
    header('location:login.php');
    exit();
}

// Pagination for orders
if (isset($_GET['page_no']) && $_GET['page_no'] != "") {
    $page_no = $_GET['page_no'];
} else {
    $page_no = 1;
}

$stmt1 = $conn->prepare("SELECT COUNT(*) As total_records FROM orders");
$stmt1->execute();
$stmt1->bind_result($total_records);
$stmt1->store_result();
$stmt1->fetch();

$total_records_per_page = 9;
$offset = ($page_no - 1) * $total_records_per_page;

$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$total_no_of_pages = ceil($total_records / $total_records_per_page);

$stmt2 = $conn->prepare("SELECT * FROM orders ORDER BY order_date DESC LIMIT ?, ?");
$stmt2->bind_param('ii', $offset, $total_records_per_page);
$stmt2->execute();
$orders = $stmt2->get_result();
?>

<body>
    <div class="container-fluid">
        <div class="row" style="min-height: 1000px">
            <?php include('sidemenu.php'); ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h1 class="h2">Orders</h1>
                </div>

                <?php if(isset($_GET['order_updated'])) { ?>
                    <p class="text-center text-success"><?php echo htmlspecialchars($_GET['order_updated']); ?></p>
                <?php } ?>
                <?php if(isset($_GET['order_failed'])) { ?>
                    <p class="text-center text-danger"><?php echo htmlspecialchars($_GET['order_failed']); ?></p>
                <?php } ?>

                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Order Status</th>
                                <th>User Id</th>
                                <th>Order Date</th>
                                <th>User Phone</th>
                                <th>User Address</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) { ?>
                            <tr>
                                <td><?php echo $order['order_id']; ?></td>
                                <td><?php echo $order['order_status']; ?></td>
                                <td><?php echo $order['user_id']; ?></td>
                                <td><?php echo $order['order_date']; ?></td>
                                <td><?php echo $order['user_phone']; ?></td>
                                <td><?php echo $order['user_address']; ?></td>
                                <td><a class="btn btn-primary" href="edit_order.php?order_id=<?php echo $order['order_id']; ?>">Edit</a></td>
                                <td><a class="btn btn-danger" href="#">Delete</a></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation" class="mx-auto">
                        <ul class="pagination mt-4">
                            <li class="page-item <?php if ($page_no <= 1) { echo 'disabled'; } ?>">
                                <a class="page-link" href="<?php echo ($page_no <= 1 ? '#' : '?page_no=' . ($page_no - 1)); ?>">Previous</a>
                            </li>
                            <?php for ($i = 1; $i <= $total_no_of_pages; $i++) { ?>
                                <li class="page-item <?php if ($page_no == $i) { echo 'active'; } ?>">
                                    <a class="page-link" href="?page_no=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                            <li class="page-item <?php if ($page_no >= $total_no_of_pages) { echo 'disabled'; } ?>">
                                <a class="page-link" href="<?php echo ($page_no >= $total_no_of_pages ? '#' : '?page_no=' . ($page_no + 1)); ?>">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.29.2/dist/feather.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>