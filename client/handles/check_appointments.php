<?php

require_once('../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $service_id = $_GET['service_id'];
    $appointment_date = $_GET['appointment_date'];

    $sql = "SELECT appointment_time
            FROM tbl_Appointments
            WHERE service_id = :service_id
            AND appointment_date = :appointment_date;";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    $stmt->bindParam(':appointment_date', $appointment_date, PDO::PARAM_STR);
    $stmt->execute();
    $appointments = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Return array of existing appointment times for the selected date
    echo json_encode(array("status" => "success", "appointments" => $appointments));

} catch (PDOException $e) {
    echo json_encode(array(
        'status' => 'error',
        'message' => $e->getMessage()
    ));
}
?>
