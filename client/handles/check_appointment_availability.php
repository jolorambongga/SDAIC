<?php

require_once('../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $service_id = $_GET['service_id'];
    $date = $_GET['date'];
    $day_of_week = date('N', strtotime($date));

    $sql = "SELECT appointment_time FROM tbl_Appointments
            WHERE service_id = :service_id AND appointment_date = :appointment_date AND day_of_week = :day_of_week";

    $stmt = $pdo->prepare($sql);

    $stmt->bindValue(':service_id', $service_id, PDO::PARAM_INT);
    $stmt->bindValue(':appointment_date', $date, PDO::PARAM_STR);
    $stmt->bindValue(':day_of_week', $day_of_week, PDO::PARAM_INT);

    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $times = array_column($result, 'appointment_time');

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "check_availability", "data" => $times));

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "check_availability", "report" => "catch_reached"));
}
?>
