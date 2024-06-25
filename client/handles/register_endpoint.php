<?php
// register_endpoint.php

header('Content-Type: application/json');

session_start();

require_once('../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $first_name = $_POST['firstName'];
        $middle_name = $_POST['middleName'];
        $last_name = $_POST['lastName'];
        $contact = $_POST['contact'];
        $address = $_POST['address'];
        $birthday = $_POST['birthday'];

        // Check if username or email is already taken
        $checkStmt = $pdo->prepare('SELECT COUNT(*) FROM tbl_Users WHERE username = :username OR email = :email');
        $checkStmt->bindParam(':username', $username);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            echo json_encode(array("status" => "error", "message" => "Username or email already taken", "isTaken" => "true"));
            exit;
        }

        // If username and email are available, proceed with the registration
        $stmt = $pdo->prepare('INSERT INTO tbl_Users (username, email, password, first_name, middle_name, last_name, contact, address, birthday) VALUES (:username, :email, :password, :first_name, :middle_name, :last_name, :contact, :address, :birthday);');

        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':middle_name', $middle_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':birthday', $birthday);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['email'] = $email;
            $_SESSION['contact'] = $contact;
            $_SESSION['first_name'] = $first_name;
            $_SESSION['middle_name'] = $middle_name;
            $_SESSION['last_name'] = $last_name;
            echo json_encode(array("status" => "success", "message" => "Registration successful", "isTaken" => "false", "user_id" => $_SESSION['user_id']));
            exit;
        } else {
            echo json_encode(array("status" => "error", "message" => "Registration failed. Please try again.", "isTaken" => "false"));
            exit;
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Method not allowed", "isTaken" => "false"));
        exit;
    }
} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "isTaken" => "false"));
    exit;
}
?>
