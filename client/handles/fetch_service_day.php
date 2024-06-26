<?php

require_once('../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $service_id = $_GET['service_id'];

    // Retrieve service details including doctor_id
    $sql = "SELECT *, IFNULL(doctor_id, 0) AS doctor_id_check FROM tbl_Services WHERE service_id = :service_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo json_encode(array("status" => "error", "message" => "Service not found", "process" => "fetch_service_day"));
        exit;
    }

    // Determine which schedule to fetch based on doctor_id presence
    if ($data['doctor_id_check'] != 0) {
        // Fetch schedule from tbl_DoctorSched
        $sql_schedule = "SELECT * FROM tbl_DoctorSched WHERE doctor_id = :doctor_id";
        $stmt_schedule = $pdo->prepare($sql_schedule);
        $stmt_schedule->bindParam(':doctor_id', $data['doctor_id'], PDO::PARAM_INT);
    } else {
        // Fetch schedule from tbl_ServiceSched
        $sql_schedule = "SELECT * FROM tbl_ServiceSched WHERE service_id = :service_id";
        $stmt_schedule = $pdo->prepare($sql_schedule);
        $stmt_schedule->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    }

    $stmt_schedule->execute();
    $schedule_data = $stmt_schedule->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "fetch_service_day", "service_data" => $data, "schedule_data" => $schedule_data));

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "fetch_service_day"));
}
?>
