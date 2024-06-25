<?php
// Include the config.php file
require_once('../../../includes/config.php');

// Set timezone if needed (adjust 'Your/Timezone' to your correct timezone)
// date_default_timezone_set('Your/Timezone');

// Function to get the number of users registered in the last 7 days
function getNewUsersInLastWeek() {
    global $pdo;

    // Calculate the start and end dates
    $startDate = date('Y-m-d 00:00:00', strtotime('-7 days')); // Start of the day 7 days ago
    $endDate = date('Y-m-d 23:59:59'); // End of today

    try {
        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare("
            SELECT COUNT(*) as new_users
            FROM tbl_Users
            WHERE user_created BETWEEN :start_date AND :end_date
        ");
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Log the result for debugging
        error_log("Start Date: $startDate, End Date: $endDate, Result: " . json_encode($result));

        return $result['new_users'];
    } catch (PDOException $e) {
        // Handle any errors
        error_log("Error fetching new users: " . $e->getMessage());
        return 0; // Return 0 or handle the error as needed
    }
}

// Get the new users in the last 7 days
$newUsers = getNewUsersInLastWeek();

// Prepare the response data
$response = [
    'newUsersLast7Days' => $newUsers
];

// Set the appropriate headers and echo the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
