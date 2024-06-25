<?php

require_once('../../../includes/config.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


	$sql = "SELECT doctor_id, contact, 
			CONCAT(first_name, ' ', COALESCE(middle_name, ' '), ' ', last_name) AS full_name
			FROM tbl_Doctors;";

	$stmt = $pdo->query($sql);

	$doctors = array();

	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$doctors[] = array(
			'doctor_id' => $row['doctor_id'],
			'full_name' => $row['full_name'],
		);
	}

	header('Content-Type: application/json');

	echo json_encode(array("status" => "success", "process" => "get_doctor", "data" => $doctors));


} catch (PDOException $e) {
	echo json_encode(array("status" => "error", "message" => $e->getMessage(), "report" => "catch_reached", "process" => "get_doctor"));
}