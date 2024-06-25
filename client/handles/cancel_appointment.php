<?php

require_once('../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $appointment_id = $_POST['appointment_id'];
    $user_input = $_POST['user_input'];

    $sql = "UPDATE tbl_Appointments
            SET status = 'CANCELLED'
            WHERE appointment_id = :appointment_id;";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
    $stmt->execute();


    $sql_select = "SELECT * FROM tbl_Appointments WHERE appointment_id = :appointment_id;";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
    $stmt_select->execute();
    $appointment = $stmt_select->fetch(PDO::FETCH_ASSOC);


    if ($appointment && isset($appointment['request_image'])) {
        $appointment['request_image_base64'] = base64_encode($appointment['request_image']);
        unset($appointment['request_image']); // Remove the binary data from the response
    }

    $status = $appointment ? "success" : "error";
    $message = $appointment ? "Appointment successfully cancelled" : "Appointment not found after cancellation";

    header('Content-Type: application/json');
    echo json_encode(compact('status', 'message', 'appointment'));

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage()));
}
?>
