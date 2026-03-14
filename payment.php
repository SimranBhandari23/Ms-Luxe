<?php
session_start();

// Ensure checkout data and cart exist
if (empty($_SESSION['checkout']) || empty($_SESSION['cart'])) {
    header('location:checkout.php');
    exit();
}

$checkout = $_SESSION['checkout'];
include('layouts/header.php');
?>

<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold" style="border-bottom: 3px solid #d3acb3;">Payment Page</h2>
    </div>

    <div class="mx-auto-container text-center">
        <div class="payment-summary mb-4">
            <p style="font-size: 1.8rem; margin-bottom: 1rem;"><strong>Total Amount to Pay:</strong> <i class="fa fa-rupee"></i> <?php echo number_format($checkout['total'], 2); ?></p>
        </div>

        <form method="POST" action="server/place_order.php">
            <input type="hidden" name="place_order" value="1">
            <input type="hidden" name="name" value="<?php echo htmlspecialchars($checkout['name']); ?>">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($checkout['email']); ?>">
            <input type="hidden" name="phone" value="<?php echo htmlspecialchars($checkout['phone']); ?>">
            <input type="hidden" name="city" value="<?php echo htmlspecialchars($checkout['city']); ?>">
            <input type="hidden" name="address" value="<?php echo htmlspecialchars($checkout['address']); ?>">

            <div class="form-group checkout-btn-container" style="display:flex; justify-content:center; gap:1rem;">
                <button type="submit" class="btn btn-success" disabled style="background-color:#d3acb3; border-color:#d3acb3; color:#ffffff;">Pay Now</button>
                <a href="checkout.php" class="btn btn-secondary" style="background-color:#d3acb3; border-color:#d3acb3; color:#ffffff;">Back to Checkout</a>
            </div>
        </form>
    </div>
</section>

<?php include('layouts/footer.php'); ?>
