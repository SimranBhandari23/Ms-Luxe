<?php
session_start();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ms Luxe</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css ">
 
    <script src="https://kit.fontawesome.com/700a52d7ba.js"></script>
</head>
<body>
   <!-- navbar -->
   <nav class="navbar navbar-expand-lg navbar-light py-3 bg-light fixed-top">
    <div class="container-fluid">
        <img src="assets/images/logo.png" alt="Logo" width="100px" height="100px">
       
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse nav-buttons" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shop.php">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a href="cart.php" class="nav-link">
                        <i class="fa-solid fa-cart-shopping">
                            <?php if (isset($_SESSION['quantity']) && $_SESSION['quantity'] != 0) { ?>
                                <span class="cart-quantity"><?php echo $_SESSION['quantity']; ?></span>
                            <?php } ?>
                        </i>
                    </a>
                    
                </li>
                <li class="nav-item">
                    <a href="account.php" class="nav-link"><i class="fas fa-user-alt"></i> </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
