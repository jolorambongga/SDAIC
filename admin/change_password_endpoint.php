<?php
session_start();
require_once('../../includes/config.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['change_password']) || $_SESSION['change_password'] !== true) {
    echo json_encode(array("message" => "Unauthorized access", "status" => "error"));
    exit;
}

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $currentPassword = isset($_POST['currentPassword']) ? $_POST['currentPassword'] : '';
    $newPassword = isset($_POST['newPassword']) ? $_POST['newPassword'] : '';

    if (empty($currentPassword) || empty($newPassword)) {
        echo json_encode(array("message" => "Current password and new password are required.", "status" => "error"));
        exit;
    }

    $userId = $_SESSION['user_id'];
    $stmt = $pdo->prepare('SELECT * FROM tbl_Users WHERE user_id = :user_id');
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(array("message" => "User not found.", "status" => "error"));
        exit;
    }

    if (!password_verify($currentPassword, $user['password'])) {
        echo json_encode(array("message" => "Current password is incorrect.", "status" => "error"));
        exit;
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    $updateStmt = $pdo->prepare('UPDATE tbl_Users SET password = :password, change_password = 0 WHERE user_id = :user_id');
    $updateStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $updateStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $updateStmt->execute();

    unset($_SESSION['change_password']);

    echo json_encode(array("message" => "Password changed successfully.", "status" => "success", "redirect" => "admin_dashboard.php"));

} catch (PDOException $e) {
    // Database error
    echo json_encode(array("message" => "Error: " . $e->getMessage(), "status" => "error"));
}
?>
