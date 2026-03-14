<?php
session_start();
include('server/connection.php');

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmpassword']);

    // Validate Name (Only Letters and Spaces)
    if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
        header('location:register.php?error=Name should only contain letters and spaces');
        exit();
    }

    // Validate Email (Should not start with number/special char and not be only numbers before '@')
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || preg_match('/^[^a-zA-Z]/', $email) || preg_match('/^[0-9]+@/', $email)) {
        header('location:register.php?error=Invalid email format. Email should not start with a number or special character.');
        exit();
    }

    // Passwords don't match
    if ($password !== $confirmPassword) {
        header('location:register.php?error=Passwords do not match');
        exit();

    // Password length validation
    } elseif (strlen($password) < 6) {
        header('location:register.php?error=Password must be at least 6 characters');
        exit();
    }

    // Check if the user already exists
    $stmt1 = $conn->prepare("SELECT count(*) FROM users WHERE user_email=?");
    $stmt1->bind_param('s', $email);
    $stmt1->execute();
    $stmt1->bind_result($num_rows);
    $stmt1->store_result();
    $stmt1->fetch();

    if ($num_rows != 0) {
        header('location:register.php?error=User with this email already exists');
        exit();
    } else {
        // Hash password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users(user_name, user_email, user_password) VALUES(?, ?, ?)");
        $stmt->bind_param('sss', $name, $email, $hashed_password);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = $name;
            $_SESSION['logged_in'] = true;
            header('location:account.php?register_success=You registered successfully');
        } else {
            header('location:register.php?error=Could not create an account at the moment');
        }
        exit();
    }
} elseif (isset($_SESSION['logged_in'])) {
    header('location:account.php');
    exit();
}
?>

<?php include('layouts/header.php'); ?>

<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="form-weight-bold">Register</h2>
    </div>
    <div class="mx-auto-container">
        <form action="register.php" id="register-form" method="POST" onsubmit="return validateRegisterForm()">
            <p id="error-message" style="color:red; text-align: center;">
                <?php if(isset($_GET['error'])) {echo $_GET['error'];} ?>
            </p>

            <!-- Name -->
            <div class="form-group">
                <label for="register-name">Name</label>
                <input type="text" name="name" id="register-name" class="form-control" placeholder="Enter your name" required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="register-email">Email</label>
                <input type="email" name="email" id="register-email" class="form-control" placeholder="Enter your email" required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="register-password">Password</label>
                <input type="password" name="password" id="register-password" class="form-control" placeholder="Enter your password" required>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="register-confirm-password">Confirm Password</label>
                <input type="password" name="confirmpassword" id="register-confirm-password" class="form-control" placeholder="Confirm your password" required>
            </div>

            <!-- Register Button -->
            <div class="form-group">
                <input type="submit" id="register-btn" class="btn btn-primary" name="register" value="Register">
            </div>

            <!-- Login Link -->
            <div class="form-group">
                <a href="login.php" id="login-url" class="btn btn-secondary bg-white" style="border: none">
                    Already have an account? Login
                </a>
            </div>
        </form>
    </div>
</section>

<script>
function validateRegisterForm() {
    let name = document.getElementById("register-name").value.trim();
    let email = document.getElementById("register-email").value.trim();
    let password = document.getElementById("register-password").value;
    let confirmPassword = document.getElementById("register-confirm-password").value;
    let errorMessage = document.getElementById("error-message");

    // Clear previous error message
    errorMessage.innerHTML = "";

    // Name validation (Only letters and spaces)
    let namePattern = /^[a-zA-Z ]+$/;
    if (!namePattern.test(name)) {
        errorMessage.innerHTML = "Name should only contain letters and spaces.";
        return false;
    }

    // Email validation (Should not start with number/special character and not be only numbers before '@')
    let emailPattern = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        errorMessage.innerHTML = "Invalid email format. Email should not start with a number or special character.";
        return false;
    }

    // Password length validation
    if (password.length < 6) {
        errorMessage.innerHTML = "Password must be at least 6 characters long.";
        return false;
    }

    // Password match validation
    if (password !== confirmPassword) {
        errorMessage.innerHTML = "Passwords do not match.";
        return false;
    }

    return true; // Allow form submission
}
</script>

<?php include('layouts/footer.php'); ?>
