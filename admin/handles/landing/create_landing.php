<?php

require_once('../../../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Extract POST data
        $about_us = isset($_POST['about_us']) ? $_POST['about_us'] : '';
        $about_us_image = isset($_POST['about_us_image']) ? $_POST['about_us_image'] : '';
        $main_image = isset($_POST['main_image']) ? $_POST['main_image'] : '';
        $landing_id = 1; // Adjust as necessary

        // Prepare and execute SQL statement for UPDATE
        $sql = "UPDATE tbl_Landing 
                SET about_us = :about_us, about_us_image = :about_us_image, main_image = :main_image
                WHERE landing_id = :landing_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':about_us', $about_us, PDO::PARAM_STR);
        $stmt->bindParam(':about_us_image', $about_us_image, PDO::PARAM_STR);
        $stmt->bindParam(':main_image', $main_image, PDO::PARAM_STR);
        $stmt->bindParam(':landing_id', $landing_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the updated data
        $sql_select = "SELECT * FROM tbl_Landing WHERE landing_id = :landing_id";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->bindParam(':landing_id', $landing_id, PDO::PARAM_INT);
        $stmt_select->execute();
        $updated_data = $stmt_select->fetch(PDO::FETCH_ASSOC);

        // Prepare response with updated data
        $response = [
            "status" => "success",
            "process" => "update landing",
            "data" => $updated_data
        ];

        header('Content-Type: application/json');
        echo json_encode($response);

    } catch (PDOException $e) {
        // Error handling
        $response = [
            "status" => "error",
            "message" => $e->getMessage(),
            "process" => "update landing"
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // Invalid request method
    $response = [
        "status" => "error",
        "message" => "Invalid request method",
        "process" => "update landing"
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}

?>
