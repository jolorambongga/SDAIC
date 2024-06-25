<?php

require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user_id = $_POST['user_id'];
    $username = $_POST['username'];    
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $birthday = $_POST['birthday'];

    // Check if username or email is already taken by another user
    $sql = "SELECT COUNT(*) FROM tbl_Users WHERE (username = :username OR email = :email) AND user_id != :user_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->fetchColumn() > 0) {
        echo json_encode(array("status" => "error", "message" => "Username or email already taken", "process" => "update profile", "isTaken" => "true"));
        exit;
    }

    // Update user information
    $sql = "UPDATE tbl_Users SET username = :username, email = :email, first_name = :first_name, middle_name = :middle_name, 
            last_name = :last_name, contact = :contact, address = :address, birthday = :birthday WHERE user_id = :user_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindParam(':middle_name', $middle_name, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindParam(':contact', $contact, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':birthday', $birthday, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    $stmt->execute();

    // Fetch updated user data
    $sql = "SELECT * FROM tbl_Users WHERE user_id = :user_id;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "update profile", "isTaken" => "false", "data" => $data));

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "update profile", "report" => "catch reached"));
}
?>
