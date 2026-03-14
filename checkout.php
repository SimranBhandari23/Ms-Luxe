<?php
session_start();
include('server/connection.php');

if (empty($_SESSION['cart'])) {
    header('location:index.php');
    exit();
}

$error_message = "";

// Server-side validation of checkout form and redirect to payment page
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $city = trim($_POST['city']);
    $address = trim($_POST['address']);

    // Name validation (Only letters and spaces)
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        $error_message = "Name should only contain letters and spaces.";
    }
    // Email validation (Should not start with a number/special char and not be only numbers before '@')
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/^[^a-zA-Z]/', $email) || preg_match('/^[0-9]+@/', $email)) {
        $error_message = "Invalid email format. Email should not start with a number or special character.";
    }
    // Phone number validation (Only digits)
    elseif (!preg_match("/^[0-9]+$/", $phone)) {
        $error_message = "Phone number should only contain numbers.";
    }

    if (empty($error_message)) {
        $_SESSION['checkout'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'city' => $city,
            'address' => $address,
            'total' => $_SESSION['total'] ?? 0,
        ];

        header('location:payment.php');
        exit();
    }
}

include('layouts/header.php');
?>

<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold" style="border-bottom: 3px solid #d3acb3;">Check Out</h2>
    </div>
    <div class="mx-auto-container">
        <form method="POST" id="checkout-form" action="checkout.php" onsubmit="return validateCheckoutForm()">
            <p id="error-message" class="text-center" style="color:red;">
                <?php echo $error_message; ?>
            </p>

            <!-- Name -->
            <div class="form-group checkout-small-element">
                <label for="checkout-name">Name</label>
                <input type="text" name="name" id="checkout-name" class="form-control" placeholder="Enter your name" required value="<?php echo isset($_SESSION['checkout']['name']) ? htmlspecialchars($_SESSION['checkout']['name']) : ''; ?>">
            </div>

            <!-- Email -->
            <div class="form-group checkout-small-element">
                <label for="checkout-email">Email</label>
                <input type="text" name="email" id="checkout-email" class="form-control" placeholder="Enter your email" required value="<?php echo isset($_SESSION['checkout']['email']) ? htmlspecialchars($_SESSION['checkout']['email']) : ''; ?>">
            </div>

            <!-- Phone -->
            <div class="form-group checkout-small-element">
                <label for="checkout-phone">Phone</label>
                <input type="tel" name="phone" id="checkout-phone" class="form-control" placeholder="Enter your phone number" required value="<?php echo isset($_SESSION['checkout']['phone']) ? htmlspecialchars($_SESSION['checkout']['phone']) : ''; ?>">
            </div>

            <!-- City -->
            <div class="form-group checkout-small-element">
                <label for="checkout-city">City</label>
                <input type="text" name="city" id="checkout-city" class="form-control" placeholder="Enter your city" required value="<?php echo isset($_SESSION['checkout']['city']) ? htmlspecialchars($_SESSION['checkout']['city']) : ''; ?>">
            </div>

            <!-- Address -->
            <div class="form-group checkout-large-element">
                <label for="checkout-address">Address</label>
                <input type="text" name="address" id="checkout-address" class="form-control" placeholder="Enter your address" required value="<?php echo isset($_SESSION['checkout']['address']) ? htmlspecialchars($_SESSION['checkout']['address']) : ''; ?>">
            </div>

            <!-- Checkout Button -->
            <div class="form-group checkout-btn-container">
                <p>Total amount: <i class="fa fa-rupee"></i> <?php echo $_SESSION['total']; ?></p>
                <input type="submit" name="place_order" id="checkout-btn" class="btn btn-primary" value="Place Order">
            </div>
        </form>
    </div>
</section>

<script>
function validateCheckoutForm() {
    let name = document.getElementById("checkout-name").value.trim();
    let email = document.getElementById("checkout-email").value.trim();
    let phone = document.getElementById("checkout-phone").value.trim();
    let errorMessage = document.getElementById("error-message");

    // Clear previous error message
    errorMessage.innerHTML = "";

    // Name validation (Only letters and spaces)
    let namePattern = /^[a-zA-Z ]+$/;
    if (!namePattern.test(name)) {
        errorMessage.innerHTML = "Name should only contain letters and spaces.";
        return false;
    }

    // Email validation (Should not start with a number/special char and not be only numbers before '@')
    let emailPattern = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        errorMessage.innerHTML = "Invalid email format. Email should not start with a number or special character.";
        return false;
    }

    // Phone number validation (Only digits)
    let phonePattern = /^[0-9]+$/;
    if (!phonePattern.test(phone)) {
        errorMessage.innerHTML = "Phone number should only contain numbers.";
        return false;
    }

    return true; // Allow form submission
}
</script>

<?php include('layouts/footer.php'); ?>
