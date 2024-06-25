<?php

require_once('../../../includes/config.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$doctor_id = $_POST['doctor_id'];
	$user_input = $_POST['user_input'];

	if ($user_input == 'DELETE') {

		// $sql = "DELETE FROM tbl_DoctorSched WHERE doctor_id = :doctor_id;";

		// $stmt = $pdo->prepare($sql);

		// $stmt->bindParam('doctor_id', $doctor_id, PDO::PARAM_INT);

		// $stmt->execute();

		$sql = "DELETE FROM tbl_Doctors WHERE doctor_id = :doctor_id;";

		$stmt = $pdo->prepare($sql);

		$stmt->bindParam(':doctor_id', $doctor_id, PDO::PARAM_INT);

		$stmt->execute();

		header('Content-Type: application/json');

		echo json_encode(array("status" => "success", "process" => "delete_doctor_if_statement", "user input is: " => $user_input));

	} else {
		echo json_encode(array("status" => "sucess", "process" => "delete_doctor_else_statement", "user input is: " => $user_input));
	}

} catch (PDOException $e) {
	echo json_encode(array("status" => "error", "message" => $e->getMessage(), "report" => "del catch reached"));
}