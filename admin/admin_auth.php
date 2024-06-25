<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or somewhere else
    header("Location: ../client/login.php");
    exit;
}

// Check if user is admin
if ($_SESSION['role_id'] != 1) {
    // Redirect to user dashboard or show unauthorized message
    header("Location: ../client/index.php");
    exit;
}