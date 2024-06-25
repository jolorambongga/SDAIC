<?php
require_once('../../../includes/config.php');

try {
    if (!isset($pdo)) {
        throw new PDOException("PDO connection is not set.");
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $service_id = isset($_GET['service_id']) ? $_GET['service_id'] : 'All';
    $today = isset($_GET['today']) && $_GET['today'] === 'true' ? true : false;

    $timezone = new DateTimeZone('Asia/Manila');
    $now = new DateTime('now', $timezone);
    $current_date = $now->format('Y-m-d');

    $sql = "SELECT 
                a.appointment_id,
                u.first_name,
                u.last_name,
                s.service_name,
                DATE_FORMAT(CONVERT_TZ(a.appointment_date, '+00:00', '+08:00'), '%M %d, %Y (%W)') as formatted_date,
                TIME_FORMAT(a.appointment_time, '%h:%i %p') as formatted_time,
                a.notes,
                a.request_image,
                a.status,
                a.completed
            FROM tbl_Appointments as a
            JOIN tbl_Services as s ON a.service_id = s.service_id
            LEFT JOIN tbl_Users as u ON a.user_id = u.user_id";


    $whereClauses = [];
    $bindParams = [];


    if ($service_id !== 'All') {
        $whereClauses[] = "a.service_id = :service_id";
        $bindParams['service_id'] = $service_id;
    }


    if ($today) {
        $whereClauses[] = "a.appointment_date = '$current_date'";
    }

    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    $stmt = $pdo->prepare($sql);

    foreach ($bindParams as $paramName => $paramValue) {
        $stmt->bindParam(':' . $paramName, $paramValue, PDO::PARAM_STR);
    }

    $stmt->execute();

    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($appointments as &$appointment) {
        if ($appointment['request_image'] !== null) {
            $appointment['request_image'] = base64_encode($appointment['request_image']);
        }
    }

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "read appointments", "data" => $appointments, "today" => $today, "service" => $service_id, "current date" => $current_date, "SQL KO" => $sql));

} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "read appointments"));
}
?>
