<?php

require_once('../../../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Extract GET data
        $landing_id = isset($_GET['landing_id']) ? $_GET['landing_id'] : null;

        if (!$landing_id) {
            throw new Exception('Invalid landing_id');
        }

        // Fetch landing data
        $sql_select = "SELECT * FROM tbl_Landing WHERE landing_id = :landing_id";
        $stmt_select = $pdo->prepare($sql_select);
        $stmt_select->bindParam(':landing_id', $landing_id, PDO::PARAM_INT);
        $stmt_select->execute();
        $landing_data = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if (!$landing_data) {
            throw new Exception('Landing not found');
        }

        // Modify data fields as needed
        $landing_data['about_us_image'] = processImageField($landing_data['about_us_image']);
        $landing_data['main_image'] = processImageField($landing_data['main_image']);

        // Prepare response
        $response = [
            "status" => "success",
            "process" => "get landing",
            "data" => $landing_data
        ];

        header('Content-Type: application/json');
        echo json_encode($response);

    } catch (Exception $e) {
        // Error handling
        $response = [
            "status" => "error",
            "message" => $e->getMessage(),
            "process" => "get landing"
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
} else {
    // Invalid request method
    $response = [
        "status" => "error",
        "message" => "Invalid request method",
        "process" => "get landing"
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
}

// Function to process image or URL field
function processImageField($field_value) {
    // Check if it's a long blob (assumed to be an image in this case)
    if (is_long_blob($field_value)) {
        // Encode as base64 and prepend with data URI scheme
        return 'data:image/png;base64,' . base64_encode($field_value);
    } else {
        // Assume it's a URL link, return as is
        return $field_value;
    }
}

// Function to check if value is a long blob (image in this case)
function is_long_blob($value) {
    // Example check: if the value is larger than a certain threshold, consider it as a long blob (image)
    // Here, you can implement your own logic to identify if $value is an image or not
    return (strlen($value) > 100000); // Adjust the threshold as per your application's needs
}

?>