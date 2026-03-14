<?php
session_start();
include('server/connection.php');

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_btn'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/^\d/', $email) || preg_match('/^[0-9]+@/', $email)) {
        $_SESSION['error'] = "Invalid email format. Email should not start with a number or contain only numbers.";
        header('location: login.php');
        exit();
    }

    // Prepare SQL query to fetch user data
    $stmt = $conn->prepare("SELECT user_id, user_name, user_email, user_password FROM users WHERE user_email = ? LIMIT 1");
    $stmt->bind_param('s', $email);

    if ($stmt->execute()) {
        $stmt->bind_result($user_id, $user_name, $user_email, $hashed_password);
        $stmt->store_result();

        // Check if a user with the given email exists
        if ($stmt->num_rows() === 1) {
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Set session variables for logged-in user
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $user_name;
                $_SESSION['user_email'] = $user_email;
                $_SESSION['logged_in'] = true;

                // Redirect to account page on successful login
                header('location: account.php?login_success=Logged in successfully');
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password.";
            }
        } else {
            $_SESSION['error'] = "Account not found.";
        }
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
    }

    // Redirect back to login page with error message
    header('location: login.php');
    exit();
}
?>

<?php include('layouts/header.php'); ?>

<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Login</h2>
    </div>
    <div class="mx-auto-container">
        <form id="login-form" action="login.php" method="POST" onsubmit="return validateLoginForm()">
            <!-- Display error message if set -->
            <p id="error-message" style="color:red; text-align: center;">
                <?php
                if (isset($_SESSION['error'])) {
                    echo $_SESSION['error'];
                    unset($_SESSION['error']); // Clear the error after displaying it
                }
                ?>
            </p>

            <!-- Email Field -->
            <div class="form-group">
                <label for="login-email">Email</label>
                <input type="email" name="email" id="login-email" class="form-control" placeholder="Enter your email" required>
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <label for="login-password">Password</label>
                <input type="password" name="password" id="login-password" class="form-control" placeholder="Enter your password" required>
            </div>

            <!-- Login Button -->
            <div class="form-group">
                <input type="submit" id="login-btn" class="btn btn-primary" value="Login" name="login_btn" style="border: none">
            </div>

            <!-- Register Link -->
            <div class="form-group">
                <a href="register.php" id="register-url" class="btn btn-secondary bg-white" aria-label="Register for a new account" style="border: none">
                    Don't have an account? Register
                </a>
            </div>
        </form>
    </div>
</section>

<script>
    function validateLoginForm() {
        let email = document.getElementById("login-email").value;
        let password = document.getElementById("login-password").value;
        let errorMessage = document.getElementById("error-message");

        // Email validation regex: must not start with a number and must contain non-numeric characters before @
        let emailPattern = /^[^\d][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        // Clear previous error message
        errorMessage.innerHTML = "";

        if (!emailPattern.test(email)) {
            errorMessage.innerHTML = "Invalid email format. Email should not start with a number or contain only numbers.";
            return false; // Prevent form submission
        }

        return true; // Allow form submission
    }
</script>

<?php include('layouts/footer.php'); ?>
