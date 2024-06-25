<?php

require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : NULL;
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];    
    $duration = $_POST['duration'];
    $max = $_POST['max'];    
    $cost = $_POST['cost'];    

    $sql = "INSERT INTO tbl_Services (doctor_id, service_name, description, duration, max, cost)
            VALUES (:doctor_id, :service_name, :description, :duration, :max, :cost);";

    $stmt = $pdo->prepare($sql);


    if ($doctor_id === NULL) {
        $stmt->bindValue(':doctor_id', NULL, PDO::PARAM_NULL);
    } else {
        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    }

    $stmt->bindParam(':service_name', $service_name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':duration', $duration, PDO::PARAM_INT);
    $stmt->bindParam(':max', $max, PDO::PARAM_INT);
    $stmt->bindParam(':cost', $cost, PDO::PARAM_INT);    

    $stmt->execute();

    $service_id = $pdo->lastInsertId();

    $service_sched = json_decode($_POST['service_sched'], true);

    if (!empty($service_sched)) {
        foreach ($service_sched as $schedule) {
            $day_of_week = $schedule['day_of_week'];
            $start_time = $schedule['start_time'];
            $end_time = $schedule['end_time'];

            $sql = "INSERT INTO tbl_ServiceSched (service_id, day_of_week, start_time, end_time)
                    VALUES (:service_id, :day_of_week, :start_time, :end_time);";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':service_id', $service_id, PDO::PARAM_INT);
            $stmt->bindParam(':day_of_week', $day_of_week, PDO::PARAM_STR);
            $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
            $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);

            $stmt->execute();
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "create_service_and_sched", "service_sched" => $service_sched));

} catch (PDOException $e) {

    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "create_service_and_sched", "data" => $service_sched, "report" => "catch_reached"));

}
?>