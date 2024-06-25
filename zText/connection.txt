<?php

require_once('../../../includes/config.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	header('Content-Type: application/json');

	echo json_encode(array("status" => "success", "process" => "PROCESS", "data" => $data));

} catch (PDOException $e) {
	echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "PROCESS", "report" => "catch reached"));
}
?>
