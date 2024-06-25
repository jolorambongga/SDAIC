<?php
require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "SELECT *, 
                   DATE_FORMAT(user_created, '%M %e, %Y <pre> %W <pre> (%h:%i %p)') AS formatted_user_created,
                   DATE_FORMAT(birthday, '%M %e, %Y <pre> (%W)') AS formatted_birthday,
                   TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age
            FROM tbl_Users;";
    
    $stmt = $pdo->query($sql);
    
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($data as &$row) {
        // Convert birthday to date format (if not already done in the SQL query)
        $row['birthday'] = date('Y-m-d', strtotime($row['birthday']));
    }
    
    header('Content-Type: application/json');
    
    echo json_encode(array("status" => "success", "process" => "read users", "data" => $data));
    
} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "read users"));
}
?>
