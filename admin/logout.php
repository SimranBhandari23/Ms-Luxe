<?php

session_start();

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
  if (isset($_SESSION['admin_logged_in'])) {
    // clear cart session data for this user
    unset($_SESSION['cart'], $_SESSION['total'], $_SESSION['quantity']);

    // clear admin session data
    unset($_SESSION['admin_logged_in']);
    unset($_SESSION['admin_email']);
    unset($_SESSION['admin_name']);

    // destroy session securely
    session_regenerate_id(true);
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');

    header('location:login.php');
    exit;
  }
}