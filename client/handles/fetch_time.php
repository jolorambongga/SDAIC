<?php

require_once('../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $service_id = $_GET['service_id'];
    $day_of_week = $_GET['day_of_week'];

    // Check in tbl_ServiceSched first
    $sql = "SELECT s.duration, ss.start_time, ss.end_time
            FROM tbl_Services AS s
            LEFT JOIN tbl_ServiceSched AS ss ON s.service_id = ss.service_id
            WHERE s.service_id = :service_id AND ss.day_of_week = :day_of_week;";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
    $stmt->bindParam(':day_of_week', $day_of_week, PDO::PARAM_STR);
    $stmt->execute();
    $time_details = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$time_details) {
        // If not found in tbl_ServiceSched, check in tbl_DoctorSched
        $sql = "SELECT s.duration, ds.start_time, ds.end_time
                FROM tbl_Services AS s
                LEFT JOIN tbl_DoctorSched AS ds ON s.doctor_id = ds.doctor_id
                WHERE s.service_id = :service_id AND ds.day_of_week = :day_of_week;";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
        $stmt->bindParam(':day_of_week', $day_of_week, PDO::PARAM_STR);
        $stmt->execute();
        $time_details = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if ($time_details) {
        $start_time = $time_details['start_time'];
        $end_time = $time_details['end_time'];
        $duration = $time_details['duration'];

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'start_time' => $start_time,
            'end_time' => $end_time,
            'duration' => $duration
        ));
    } else {
        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'error',
            'message' => 'No time details found for the provided service_id and day_of_week'
        ));
    }

} catch (PDOException $e) {
    echo json_encode(array(
        'status' => 'error',
        'message' => $e->getMessage()
    ));
}
?>
