<?php

require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT s.*, CONCAT(d.first_name, ' ', d.middle_name, ' ', d.last_name) AS full_name,
            GROUP_CONCAT(CONCAT(ds.day_of_week, ': ', 
                TIME_FORMAT(ds.start_time, '%h:%i %p'), ' - ', 
                TIME_FORMAT(ds.end_time, '%h:%i %p'))
            ORDER BY ds.day_of_week ASC SEPARATOR '<br>') AS doctor_sched,
            GROUP_CONCAT(CONCAT(ss.day_of_week, ': ', 
                TIME_FORMAT(ss.start_time, '%h:%i %p'), ' - ', 
                TIME_FORMAT(ss.end_time, '%h:%i %p'))
            ORDER BY ss.day_of_week ASC SEPARATOR '<br>') AS service_sched 
            FROM tbl_Services as s
            LEFT JOIN tbl_Doctors as d
            ON s.doctor_id = d.doctor_id
            LEFT JOIN tbl_DoctorSched as ds
            ON d.doctor_id = ds.doctor_id
            LEFT JOIN tbl_ServiceSched as ss
            ON s.service_id = ss.service_id
            GROUP BY s.service_id;";

    $stmt = $pdo->query($sql);

    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "read_services", "data" => $services));

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "report" => "catch_reached"));
}
