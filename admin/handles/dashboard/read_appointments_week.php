<?php
require_once('../../../includes/config.php');

try {
    if (!isset($pdo)) {
        throw new PDOException("PDO connection is not set.");
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $timezone = new DateTimeZone('Asia/Manila');
    $now = new DateTime('now', $timezone);
    $current_date = $now->format('Y-m-d');

    // Get the start and end date of the current week
    $start_of_week = $now->modify('this week')->format('Y-m-d');
    $end_of_week = $now->modify('this week +6 days')->format('Y-m-d');

    $sql = "SELECT COUNT(*) as appointment_count
            FROM tbl_Appointments as a
            WHERE DATE(CONVERT_TZ(a.appointment_date, '+00:00', '+08:00')) >= :start_of_week
              AND DATE(CONVERT_TZ(a.appointment_date, '+00:00', '+08:00')) <= :end_of_week
              AND a.status = 'APPROVED';";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':start_of_week', $start_of_week, PDO::PARAM_STR);
    $stmt->bindValue(':end_of_week', $end_of_week, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $appointment_count = $result['appointment_count'];

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "count appointments for week", "appointment_count" => $appointment_count, "start_of_week" => $start_of_week, "end_of_week" => $end_of_week, "SQL" => $sql, "week_count"));

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "count appointments for week"));
}
?>
