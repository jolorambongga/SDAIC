<?php

require_once('../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $service_id = $_GET['service_id'];
    $doctor_id = isset($_GET['doctor_id']) ? $_GET['doctor_id'] : NULL;

    $data = array();

    if (!$doctor_id) {
        $sql = "SELECT * FROM tbl_Services as s
                LEFT JOIN tbl_ServiceSched as ss
                ON s.service_id = ss.service_id
                WHERE s.service_id = :service_id;";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    } else {
        $sql = "SELECT *, s.service_id FROM tbl_Services AS s
                LEFT JOIN tbl_ServiceSched AS ss
                ON s.service_id = ss.service_id
                LEFT JOIN tbl_DoctorSched AS ds
                ON s.doctor_id = ds.doctor_id
                WHERE s.doctor_id = :doctor_id;";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    }

    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($schedules as $schedule) {
        // Example of constructing the schedule data
        $schedule_data[] = array(
            'day_of_week' => $schedule['day_of_week'],
            'start_time' => $schedule['start_time'],
            'end_time' => $schedule['end_time']
            // Add more fields as needed
        );
    }

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "fetch_schedule", "service_data" => $schedules, "schedule_data" => $schedule_data));

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "fetch_schedule"));
}
?>
