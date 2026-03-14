<?php

include('header.php');
include('../server/connection.php');

if (isset($_SESSION['admin_logged_in'])) {
  header('location: index.php');
  exit;
}

$error_message = "";

if (isset($_POST['login_btn'])) {
  $email = $_POST['email'];
  $password = md5($_POST['password']);

  // Email validation: No starting number/special char, not only numbers before '@'
  if (!preg_match('/^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
    $error_message = "Invalid email format. Email should not start with a number or special character.";
  } else {
    // First verify if the email exists
    $stmt = $conn->prepare("SELECT admin_id, admin_name, admin_email, admin_password FROM admins WHERE admin_email = ? LIMIT 1");
    $stmt->bind_param('s', $email);

    if ($stmt->execute()) {
      $stmt->store_result();
      if ($stmt->num_rows() === 1) {
        $stmt->bind_result($admin_id, $admin_name, $admin_email, $admin_password);
        $stmt->fetch();

        // Support both md5 hashed passwords and raw passwords (depending on existing DB state)
        if ($admin_password === $password || $admin_password === $_POST['password']) {
          $_SESSION['admin_id'] = $admin_id;
          $_SESSION['admin_name'] = $admin_name;
          $_SESSION['admin_email'] = $admin_email;
          $_SESSION['admin_logged_in'] = true;

          header('location: index.php?login_success=logged in Successfully');
          exit;
        } else {
          $error_message = "Incorrect password.";
        }
      } else {
        $error_message = "Admin account not found.";
      }
    } else {
      $error_message = "Something went wrong. Please try again.";
    }
  }
}

?>

<main class="container-fluid">
  <div class="" style="min-height:1000px">
    <main class="col-md-6 mx-auto col-lg-6 px-md-4 text-center">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items">
        <h1 class="h2"></h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="btn-group me-2"></div>
        </div>
      </div>

      <h2>Login</h2>
      <div class="table-responsive"></div>

      <div class="mx-auto container" style="width: 50%;">
        <form id="login-form" enctype="multipart/form-data" method="POST" action="login.php" onsubmit="return validateLoginForm()">
          <p id="error-message" style="color:red" class="text-center"><?php if (isset($error_message)) { echo $error_message; } ?></p>
          <div class="form-group mt-2">
            <label for="email" style="margin-right: 100%;">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="email" required>
          </div>
          <div class="form-group mt-2">
            <label style="margin-right: 100%;">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="password" required>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-primary" name="login_btn" value="Login">
          </div>
        </form>
      </div>
    </main>
  </div>
</main>

<script>
function validateLoginForm() {
    let email = document.getElementById("email").value.trim();
    let errorMessage = document.getElementById("error-message");

    // Clear previous error messages
    errorMessage.innerHTML = "";

    // Email validation: No starting number/special char, not only numbers before '@'
    let emailPattern = /^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        errorMessage.innerHTML = "Invalid email format. Email should not start with a number or special character.";
        return false;
    }

    return true; // Allow form submission
}
</script>

<?php include('footer.php'); ?>
