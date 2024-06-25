<?php

require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $service_id = $_GET['service_id'];
    $doctor_id = $_GET['doctor_id'];

    // First query to get the service and doctor details
    $sql = "SELECT s.*, 
            CONCAT(d.first_name, ' ', d.middle_name, ' ', d.last_name) AS full_name
            FROM tbl_Services as s
            LEFT JOIN tbl_Doctors as d ON s.doctor_id = d.doctor_id
            WHERE s.service_id = :service_id
            GROUP BY s.service_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    $stmt->execute();

    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    // Second query to get the doctor's schedule
    $doctor_sched_sql = "SELECT ds.day_of_week, 
                                TIME_FORMAT(ds.start_time, '%H:%i') AS start_time, 
                                TIME_FORMAT(ds.end_time, '%H:%i') AS end_time 
                         FROM tbl_DoctorSched ds
                         WHERE ds.doctor_id = :doctor_id";
    $doctor_sched_stmt = $pdo->prepare($doctor_sched_sql);
    $doctor_sched_stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $doctor_sched_stmt->execute();
    $doctor_sched = $doctor_sched_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Third query to get the service's schedule
    $service_sched_sql = "SELECT ss.day_of_week, 
                                 TIME_FORMAT(ss.start_time, '%H:%i') AS start_time, 
                                 TIME_FORMAT(ss.end_time, '%H:%i') AS end_time 
                          FROM tbl_ServiceSched ss
                          WHERE ss.service_id = :service_id";
    $service_sched_stmt = $pdo->prepare($service_sched_sql);
    $service_sched_stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    $service_sched_stmt->execute();
    $service_sched = $service_sched_stmt->fetchAll(PDO::FETCH_ASSOC);

    $service['doctor_sched'] = $doctor_sched;
    $service['service_sched'] = $service_sched;

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "get_service_for_edit", "data" => [$service]));

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "report" => "catch_reached", "process" => "get_service_for_edit"));
}
?>