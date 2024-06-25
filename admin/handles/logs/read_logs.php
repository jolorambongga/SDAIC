<?php

require_once('../../../includes/config.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$sql = "SELECT *, u.first_name, u.last_name
			FROM tbl_Logs as l
			LEFT JOIN tbl_Users as u
			ON l.user_id = u.user_id;";

	$stmt = $pdo->query($sql);

	$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	header('Content-Type: application/json');

	echo json_encode(array("status" => "success", "process" => "read logs", "data" => $data));

} catch (PDOException $e) {
	echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "read logs", "report" => "catch reached"));
}
?>
