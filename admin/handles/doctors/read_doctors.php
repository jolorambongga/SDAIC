<?php

require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT d.doctor_id, 
            CONCAT(d.first_name, ' ', d.middle_name, ' ', d.last_name) AS full_name,
            GROUP_CONCAT(CONCAT(s.day_of_week, ': ', 
                TIME_FORMAT(s.start_time, '%h:%i %p'), ' - ', 
                TIME_FORMAT(s.end_time, '%h:%i %p'))
            ORDER BY s.day_of_week ASC SEPARATOR '<br>') AS schedule,
            d.contact
            FROM tbl_Doctors AS d
            LEFT JOIN tbl_DoctorSched AS s ON d.doctor_id = s.doctor_id
            GROUP BY d.doctor_id, d.first_name, d.middle_name, d.last_name, d.contact
            ORDER BY d.doctor_id ASC";

    $stmt = $pdo->query($sql);

    $doctor_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');

    if (empty($doctor_data)) {
        echo json_encode(array("status" => "success", "process" => "read_doctors", "message" => "No doctors found", "doctor_data" => []));
    } else {
        echo json_encode(array("status" => "success", "process" => "read_doctors", "doctor_data" => $doctor_data));
    }

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "read_doctors", "report" => "catch_reached"));
}

?>
