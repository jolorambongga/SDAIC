<?php
require_once('../../includes/config.php');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $landing_id = 1; // Replace with your actual landing_id or retrieve dynamically

    $sql = "SELECT * FROM tbl_Landing WHERE landing_id = :landing_id;";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':landing_id', $landing_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // Assuming only one record is returned

    if (!$data) {
        echo json_encode(array("status" => "error", "message" => "No data found for landing_id: $landing_id", "process" => "read landing"));
        exit;
    }

    // Check and convert images to base64 data URI if needed
    if ($data['about_us_image'] && !filter_var($data['about_us_image'], FILTER_VALIDATE_URL)) {
        $data['about_us_image'] = 'data:image/png;base64,' . base64_encode($data['about_us_image']);
    }

    if ($data['main_image'] && !filter_var($data['main_image'], FILTER_VALIDATE_URL)) {
        $data['main_image'] = 'data:image/png;base64,' . base64_encode($data['main_image']);
    }
    
    header('Content-Type: application/json');
    echo json_encode(array("status" => "success", "process" => "read landing", "data" => $data));
    
} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "read landing"));
}
?>
