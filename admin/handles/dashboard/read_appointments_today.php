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

    $sql = "SELECT COUNT(*) as appointment_count
            FROM tbl_Appointments as a
            WHERE DATE(CONVERT_TZ(a.appointment_date, '+00:00', '+08:00')) = :current_date
            AND a.status = 'APPROVED';";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':current_date', $current_date, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $appointment_count = $result['appointment_count'];

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "count appointments", "appointment_count" => $appointment_count, "current_date" => $current_date, "SQL" => $sql));

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "count appointments"));
}
?>
