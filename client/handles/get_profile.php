<?php

require_once('../../includes/config.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$user_id = $_GET['user_id'];

	$sql = "SELECT * FROM tbl_Users WHERE user_id = :user_id;";

	$stmt = $pdo->prepare($sql);

	$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

	$stmt->execute();

	$data = $stmt->fetch(PDO::FETCH_ASSOC);

	header('Content-Type: application/json');

	echo json_encode(array("status" => "success", "process" => "read profile", "data" => $data));

} catch (PDOException $e) {
	echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "read profile", "report" => "catch reached"));
}
?>
