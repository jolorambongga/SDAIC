<?php
session_start();
require_once('../../includes/config.php');

header('Content-Type: application/json');

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve and validate input
    $login = isset($_POST['login']) ? $_POST['login'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if (empty($login) || empty($password)) {
        echo json_encode(array("message" => "Login and password are required!", "status" => "error", "isEmpty" => "true"));
        exit;
    }

    // Prepare SQL statement to fetch user details by email or username
    $sql = "SELECT * FROM tbl_Users WHERE email = :login OR username = :login";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login', $login, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password correct, set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['contact'] = $user['contact'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['middle_name'] = $user['middle_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role_id'] = $user['role_id'];

            // Check if password change is required
            if ($user['change_password'] == 1) {
                // Set session variable to prompt password change
                $_SESSION['change_password'] = true;
                // Redirect to change password page
                echo json_encode(array(
                    "message" => "Password change required. Please change your password.",
                    "status" => "success",
                    "change_password" => true,
                    "redirect" => "../admin/change_password.php"
                ));
                exit;
            }

            // Check user role and redirect accordingly
            if ($user['role_id'] == 1) {
                // Admin role
                echo json_encode(array(
                    "message" => "Admin login successful. Welcome, " . $user['first_name'] . " " . $user['last_name'],
                    "status" => "success",
                    "data" => $user,
                    "role_id" => 1,
                    "redirect" => "../admin/admin_dashboard.php"
                ));
            } else {
                // User role
                echo json_encode(array(
                    "message" => "User login successful. Welcome, " . $user['first_name'] . " " . $user['last_name'],
                    "status" => "success",
                    "data" => $user,
                    "role_id" => 2,
                    "redirect" => "new_appointment.php"
                ));
            }
        } else {
            // Password incorrect
            echo json_encode(array("message" => "Wrong email/username or password!", "status" => "error", "isWrong" => "true"));
        }
    } else {
        // User not found
        echo json_encode(array("message" => "Wrong email/username or password!", "status" => "error", "isNotFound" => "true"));
    }
} catch (PDOException $e) {
    // Database error
    echo json_encode(array("message" => "Error: " . $e->getMessage(), "status" => "error"));
}
?>
