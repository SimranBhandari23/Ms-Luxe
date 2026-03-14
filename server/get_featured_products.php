<?php
include('connection.php');
$stmt=$conn->prepare("SELECT * FROM  products WHERE product_category='featured'LIMIT 3");
$stmt->execute();
$featured_products=$stmt->get_result();
?>