<?php

require_once('../../../includes/config.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$appointment_id = $_POST['appointment_id'];
	$completed = $_POST['completed'];

	$sql = "UPDATE tbl_Appointments
			SET completed = :completed
			WHERE appointment_id = :appointment_id;";

	$stmt = $pdo->prepare($sql);

	$stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
	$stmt->bindParam(':completed', $completed, PDO::PARAM_STR);

	$stmt->execute();

	$sql = "SELECT * FROM tbl_Appointments WHERE appointment_id = :appointment_id;";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);
	$stmt->execute();
	$updatedRow = $stmt->fetch(PDO::FETCH_ASSOC);

	if (!empty($updatedRow['request_image'])) {
		$updatedRow['request_image'] = base64_encode($updatedRow['request_image']);
	}

	header('Content-Type: application/json');

	echo json_encode(array("status" => "success", "process" => "change completed", "data" => $updatedRow));

} catch (PDOException $e) {
	echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "PROCESS", "report" => "catch reached"));
}
?>