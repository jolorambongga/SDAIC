<?php

require_once('../../../includes/config.php');

// Assuming you're receiving POST data from the AJAX request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Assuming you have the landing_id from somewhere
        $landing_id = 1;

        // Fetching and validating input data
        $about_us = isset($_POST['about_us']) ? $_POST['about_us'] : '';
        $about_us_image = isset($_POST['about_us_image']) ? $_POST['about_us_image'] : '';
        $main_image = isset($_POST['main_image']) ? $_POST['main_image'] : '';

        // Handle About Us Image file upload
        if (isset($_FILES['about_us_image']['tmp_name']) && !empty($_FILES['about_us_image']['tmp_name'])) {
            $about_us_image_base64 = base64_encode(file_get_contents($_FILES['about_us_image']['tmp_name']));
        } else {
            $about_us_image_base64 = $about_us_image; // If URL provided, use it directly
        }

        // Handle Main Image file upload
        if (isset($_FILES['main_image']['tmp_name']) && !empty($_FILES['main_image']['tmp_name'])) {
            $main_image_base64 = base64_encode(file_get_contents($_FILES['main_image']['tmp_name']));
        } else {
            $main_image_base64 = $main_image; // If URL provided, use it directly
        }

        // Build the SQL query
        $sql = "UPDATE tbl_Landing 
                SET about_us = :about_us, about_us_image = :about_us_image, main_image = :main_image
                WHERE landing_id = :landing_id";

        // Prepare parameters
        $params = [
            ':about_us' => $about_us,
            ':about_us_image' => $about_us_image_base64,
            ':main_image' => $main_image_base64,
            ':landing_id' => $landing_id
        ];

        // Execute the query
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        // Assuming you have some data to return as a response
        $data = []; // Add relevant data here if needed

        // Return success response
        header('Content-Type: application/json');
        echo json_encode(array("status" => "success", "process" => "update landing", "data" => $data));

    } catch (PDOException $e) {
        // Return error response
        header('Content-Type: application/json');
        echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "update landing", "data" => null));
    }
} else {
    // Handle invalid request method
    header('Content-Type: application/json');
    echo json_encode(array("status" => "error", "message" => "Invalid request method", "process" => "update landing"));
}

?>
