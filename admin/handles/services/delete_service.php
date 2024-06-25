<?php

require_once('../../../includes/config.php');

try {
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $service_id = $_POST['service_id'];
  $user_input = $_POST['user_input'];

  if ($user_input == 'DELETE') {

    $sql = "DELETE FROM tbl_ServiceSched WHERE service_id = :service_id;";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':service_id', $service_id, PDO::PARAM_STR);

    $stmt->execute();

    $sql = "DELETE FROM tbl_Services WHERE service_id = :service_id;";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam('service_id', $service_id, PDO::PARAM_STR);

    $stmt->execute();

    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "delete_service_and_sched_IF", "user_input" => $user_input));
  } else {
    echo json_encode(array("status" => "error", "process" => "delete_service_and_sched_ELSE", "user_input" => $user_input));
  }
} catch (PDOException $e) {
  $pdo->rollBack();
  echo json_encode(array("status" => "error", "message" => $e->getMessage(), "report" => "catch_reached", "process" => "delete_service_and_sched"));
}
