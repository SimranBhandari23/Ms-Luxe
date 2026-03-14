<?php
include('connection.php');
$stmt=$conn->prepare("SELECT * FROM products WHERE product_category='newarrival' LIMIT 3");
$stmt->execute();
$new_arrivals=$stmt->get_result();
?>