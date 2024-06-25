<?php
try {
    session_start();

    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    $redirect_user = "../client/index.php";

    $redirect_admin = "../client/login.php";

    // Return success response
    echo json_encode(array("status" => "success", "redirect_user" => $redirect_user, "redirect_admin" => $redirect_admin));
} catch (Exception $e) {
    // Return error response
    echo json_encode(array("status" => "error", "message" => $e->getMessage()));
}
?>