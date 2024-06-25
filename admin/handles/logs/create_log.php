<?php
require_once('../../../includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract POST data
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $category = isset($_POST['category']) ? $_POST['category'] : null;
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $affected_data = isset($_POST['affected_data']) ? $_POST['affected_data'] : null;
    $latitude = isset($_POST['latitude']) ? $_POST['latitude'] : null;
    $longitude = isset($_POST['longitude']) ? $_POST['longitude'] : null;
    $location = isset($_POST['location']) ? $_POST['location'] : null;
    $device = isset($_POST['device']) ? $_POST['device'] : null;
    $browser = isset($_POST['browser']) ? $_POST['browser'] : null;
    $ip_address = isset($_POST['ip_address']) ? $_POST['ip_address'] : null;
    // $time_stamp = isset($_POST['time_stamp']) ? $_POST['time_stamp'] : date('Y-m-d H:i:s');

    try {
        // Establish database connection
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement
        $sql = "INSERT INTO tbl_Logs (user_id, category, action, affected_data, latitude, longitude, location, device, browser, ip_address)
                VALUES (:user_id, :category, :action, :affected_data, :latitude, :longitude, :location, :device, :browser, :ip_address)";
        
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':action', $action, PDO::PARAM_STR);
        $stmt->bindParam(':affected_data', $affected_data, PDO::PARAM_STR);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':device', $device, PDO::PARAM_STR);
        $stmt->bindParam(':browser', $browser, PDO::PARAM_STR);
        $stmt->bindParam(':ip_address', $ip_address, PDO::PARAM_STR);
        // $stmt->bindParam(':time_stamp', $time_stamp, PDO::PARAM_STR);
        
        // Execute SQL statement
        $stmt->execute();

        $log_id = $pdo->lastInsertId();

        // Fetch the inserted log entry
        $sql = "SELECT * FROM tbl_Logs WHERE log_id = :log_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':log_id', $log_id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(array('status' => 'success', 'data' => $data));
    } catch (PDOException $e) {
        echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Method not allowed'));
}
?>
