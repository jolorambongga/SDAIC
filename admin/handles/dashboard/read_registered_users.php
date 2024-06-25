<?php
require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT COUNT(*) AS user_count
            FROM tbl_Users
            WHERE role_id = 2;";
    
    $stmt = $pdo->query($sql);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_count = $result['user_count'];
    
    header('Content-Type: application/json');
    
    echo json_encode(array("status" => "success", "process" => "count users where role_id = 2", "user_count" => $user_count));
    
} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "count users where role_id = 2"));
}
?>