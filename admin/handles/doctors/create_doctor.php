<?php

require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $contact = $_POST['contact'];
    

    $sql = "INSERT INTO tbl_Doctors (first_name, middle_name, last_name, contact)
            VALUES (:first_name, :middle_name, :last_name, :contact);";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindParam('middle_name', $middle_name, PDO::PARAM_STR);
    $stmt->bindParam('last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindParam('contact', $contact, PDO::PARAM_STR);

    $stmt->execute();

    $doctor_id = $pdo->lastInsertId();

    $doctor_sched = json_decode($_POST['doctor_sched'], true);

    foreach ($doctor_sched as $schedule) {
        
        $day_of_week = $schedule['day_of_week'];
        $start_time = $schedule['start_time'];
        $end_time = $schedule['end_time'];

        $sql = "INSERT INTO tbl_DoctorSched (doctor_id, day_of_week, start_time, end_time)
                    VALUES (:doctor_id, :day_of_week, :start_time, :end_time);";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
        $stmt->bindParam(':day_of_week', $day_of_week, PDO::PARAM_STR);
        $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);

        $stmt->execute();
    }

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "create_doctor_and_sched", "doctor_sched" => $doctor_sched, "doctor_data" => $data));

} catch (PDOException $e) {

    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "add_doctor_and_sched", "doctor_sched" => $doctor_sched, "report" => "catch_reached"));

}
