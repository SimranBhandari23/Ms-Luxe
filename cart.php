<?php
session_start();

// Initialize the cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Function to calculate the total cart value
function calculateTotalCart()
{
    $total_quantity = 0;
    $total = 0;

    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $value) {
            $total += $value['product_price'] * $value['product_quantity'];
            $total_quantity += $value['product_quantity'];
        }
    }

    $_SESSION['total'] = $total;  // Fix: Store total amount properly
    $_SESSION['quantity'] = $total_quantity;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_STRING);

    if (isset($_POST['add_to_cart']) && $product_id) {
        $product_array = [
            'product_id' => $product_id,
            'product_name' => htmlspecialchars($_POST['product_name'] ?? '', ENT_QUOTES),
            'product_price' => (float)($_POST['product_price'] ?? 0),
            'product_image' => htmlspecialchars($_POST['product_image'] ?? '', ENT_QUOTES),
            'product_quantity' => max(1, (int)($_POST['product_quantity'] ?? 1)),
            'product_size' => htmlspecialchars($_POST['product_size'] ?? '', ENT_QUOTES),
        ];

        // Add or update the product in the cart
        if (!array_key_exists($product_id, $_SESSION['cart'])) {
            $_SESSION['cart'][$product_id] = $product_array;
        } else {
            $_SESSION['cart'][$product_id]['product_quantity'] += $product_array['product_quantity'];
        }

        calculateTotalCart();
    } elseif (isset($_POST['remove_product']) && $product_id) {
        unset($_SESSION['cart'][$product_id]);
        calculateTotalCart();
    } elseif (isset($_POST['edit_quantity']) && $product_id) {
        $product_quantity = max(1, (int)($_POST['product_quantity'] ?? 1));
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['product_quantity'] = $product_quantity;
        }
        calculateTotalCart();
    }
}

// Calculate totals if not already done
calculateTotalCart();
?>

<?php include('layouts/header.php'); ?>

<section class="cart container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold text-center" style="border-bottom: 3px solid #d3acb3;">Your Cart</h2>
    </div>
    <table class="mt-5 pt-5 w-100">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Size</th>
            <th>Subtotal</th>
        </tr>
        <?php if (empty($_SESSION['cart'])) { ?>
            <tr>
                <td colspan="4" class="text-center">Your cart is empty.</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($_SESSION['cart'] as $key => $value) { ?>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="assets/images/<?php echo htmlspecialchars($value['product_image']); ?>" alt="">
                            <div>
                                <p><?php echo htmlspecialchars($value['product_name']); ?></p>
                                <small>
                                    <span>
                                        <i class="fa fa-rupee"></i> <?php echo number_format($value['product_price'], 2); ?>
                                    </span>
                                </small>
                                <br>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($value['product_id']); ?>">
                                    <input type="submit" name="remove_product" class="remove-btn" value="Remove">
                                </form>
                            </div>
                        </div>
                    </td>
                    <td>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($value['product_id']); ?>">
                            <input type="number" name="product_quantity" min="1" value="<?php echo $value['product_quantity']; ?>">
                            <input type="submit" class="edit-btn" value="Edit" name="edit_quantity">
                        </form>
                    </td>
                    <td>
                        <select name="product_size" disabled>
                            <option value="Medium" <?php echo $value['product_size'] === 'Medium' ? 'selected' : ''; ?>>Medium</option>
                            <option value="Large" <?php echo $value['product_size'] === 'Large' ? 'selected' : ''; ?>>Large</option>
                            <option value="XL" <?php echo $value['product_size'] === 'XL' ? 'selected' : ''; ?>>XL</option>
                        </select>
                    </td>
                    <td>
                        <span>
                            <i class="fa fa-rupee"></i> <?php echo number_format($value['product_quantity'] * $value['product_price'], 2); ?>
                        </span>
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </table>

    <div class="cart-total">
        <table>
            <tr>
                <td>Total</td>
                <td><i class="fa fa-rupee"></i> <?php echo number_format($_SESSION['total'] ?? 0, 2); ?></td>
            </tr>
        </table>
    </div>
    <div class="checkout-container">
        <form method="POST" action="checkout.php">
            <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout">
        </form>
    </div>
</section>

<?php include('layouts/footer.php'); ?>
