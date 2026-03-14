<?php

session_start();
include('server/connection.php');

if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    // Clear cart data from session
    unset($_SESSION['cart'], $_SESSION['total'], $_SESSION['quantity']);

    // Remove persisted cart data in DB for current user if exists
    if (isset($_SESSION['user_id'])) {
        $user_id = (int) $_SESSION['user_id'];
        if ($stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?")) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->close();
        }
        if ($stmt = $conn->prepare("DELETE FROM cart_items WHERE user_id = ?")) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Remove login flags
    unset($_SESSION['logged_in'], $_SESSION['user_email'], $_SESSION['user_name'], $_SESSION['user_id']);

    // Secure session destroy
    session_regenerate_id(true);
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/');

    header('Location: login.php');
    exit;
}

header('Location: login.php');
exit;
