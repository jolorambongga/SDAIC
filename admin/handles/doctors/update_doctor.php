<?php

require_once('../../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $doctor_id = $_POST['doctor_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $contact = $_POST['contact'];
    $doctor_sched = json_decode($_POST['doctor_sched'], true);

    $sql = "UPDATE tbl_Doctors 
            SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name, contact = :contact
            WHERE doctor_id = :doctor_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $stmt->bindParam(':middle_name', $middle_name, PDO::PARAM_STR);
    $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $stmt->bindParam(':contact', $contact, PDO::PARAM_STR);
    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->execute();

    $sql = "DELETE FROM tbl_DoctorSched WHERE doctor_id = :doctor_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->execute();

    $sql = "INSERT INTO tbl_DoctorSched (doctor_id, day_of_week, start_time, end_time) 
            VALUES (:doctor_id, :day_of_week, :start_time, :end_time)";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);
    $stmt->bindParam(':day_of_week', $day_of_week, PDO::PARAM_STR);
    $stmt->bindParam(':start_time', $start_time, PDO::PARAM_STR);
    $stmt->bindParam(':end_time', $end_time, PDO::PARAM_STR);

    foreach ($doctor_sched as $schedule) {
        $day_of_week = $schedule['day_of_week'];
        $start_time = $schedule['start_time'];
        $end_time = $schedule['end_time'];
        $stmt->execute();
    }

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "update_doctor", "doctor_data" => $data));

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "update_doctor", "report" => "catch_reached"));
}
?>
