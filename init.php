<?php

require_once('includes/config.php');


$secretKey = 'blackpink';


if (!isset($_GET['key']) || $_GET['key'] !== $secretKey) {
    echo json_encode(array("status" => "error", "message" => "Unauthorized access", "process" => "auth_check"));
    exit;
}

header('Content-Type: application/json');

try {

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $stmt = $pdo->prepare('SELECT COUNT(*) FROM tbl_Users WHERE username = ?');
    $stmt->execute(['admin']);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        $response = array("status" => "success", "process" => "check_admin", "message" => "Admin account already exists");
    } else {

        $adminPassword = 'adminpass';
        $hashedPassword = password_hash($adminPassword, PASSWORD_BCRYPT);


        $sql = "INSERT INTO tbl_Users (user_id, role_id, username, password, change_password)
                VALUES (:user_id, :role_id, :username, :password, :change_password);";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':user_id', 1, PDO::PARAM_INT);
        $stmt->bindValue(':role_id', 1, PDO::PARAM_INT);
        $stmt->bindValue(':username', 'admin', PDO::PARAM_STR);
        $stmt->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        $stmt->bindValue(':change_password', 1, PDO::PARAM_INT);

        $stmt->execute();

        $response = array("status" => "success", "process" => "create_admin", "message" => "Admin account created successfully");
    }


    echo json_encode($response);
    exit;

} catch (PDOException $e) {

    $response = array(
        "status" => "error",
        "message" => $e->getMessage(),
        "process" => "db_error",
        "report" => "catch reached"
    );
    echo json_encode($response);
    exit;
}
?>
