<?php
require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $appointment_id = $_GET['appointment_id'];


    $sql = "SELECT a.request_image, a.user_id, u.first_name, u.last_name, s.service_id, s.service_name 
            FROM tbl_Appointments a
            INNER JOIN tbl_Users u ON a.user_id = u.user_id
            INNER JOIN tbl_Services s ON a.service_id = s.service_id
            WHERE a.appointment_id = :appointment_id";
            
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':appointment_id', $appointment_id);
    $stmt->execute();


    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    if ($result && isset($result['request_image'])) {
        $data['request_image'] = base64_encode($result['request_image']);
        $data['user_id'] = $result['user_id'];
        $data['first_name'] = $result['first_name'];
        $data['last_name'] = $result['last_name'];
        $data['service_id'] = $result['service_id'];
        $data['service_name'] = $result['service_name'];

        header('Content-Type: application/json');
        echo json_encode(array("status" => "success", "data" => $data));
    } else {
        echo json_encode(array("status" => "error", "message" => "Image not found for appointment ID: $appointment_id"));
    }

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage()));
}
?>
