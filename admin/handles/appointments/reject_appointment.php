<?php

require_once('../../../includes/config.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$appointment_id = $_POST['appointment_id'];
	$status = $_POST['status'];
	$user_input = $_POST['user_input'];

	$sql = "UPDATE tbl_Appointments
			SET status = :status
			WHERE appointment_id = :appointment_id;";

	$stmt = $pdo->prepare($sql)			;

	$stmt->bindParam(':status', $status, PDO::PARAM_STR);
	$stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);

	$stmt->execute();

	$sql = "SELECT * FROM tbl_Appointments
			WHERE appointment_id = :appointment_id;";

	$stmt = $pdo->prepare($sql);

	$stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);

	$stmt->execute();

	$data = $stmt->fetch(PDO::FETCH_ASSOC);

	$data['request_image'] = base64_encode($data['request_image']);

	header('Content-Type: application/json');

	echo json_encode(array("status" => "success", "process" => "reject appointment", "data" => $data));

} catch (PDOException $e) {
	echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "reject appointment", "report" => "catch reached"));
}
?>
