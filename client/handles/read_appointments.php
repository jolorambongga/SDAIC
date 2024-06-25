<?php
require_once('../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $user_id = $_GET['user_id'];
    
    $sql = "SELECT *,
            DATE_FORMAT(a.appointment_date, '%M %d, %Y <br> (%W)') as formatted_date,
            TIME_FORMAT(a.appointment_time, '%h:%i %p') as formatted_time
            FROM tbl_Appointments as a
            LEFT JOIN tbl_Services as s ON s.service_id = a.service_id
            WHERE a.user_id = :user_id";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    
    $stmt->execute();
    
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($data as &$row) {
        if ($row['request_image'] !== null) {
            $row['request_image'] = base64_encode($row['request_image']);
        }
    }
    
    header('Content-Type: application/json');
    
    echo json_encode(array("status" => "success", "process" => "read appointments", "data" => $data));
    
} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "read appointments", "report" => "catch reached"));
}
?>