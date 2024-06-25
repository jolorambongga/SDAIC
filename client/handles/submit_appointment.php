<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once('../../includes/config.php');
    require_once('../../admin/mail/mail_script.php');
    require_once('../../PHPMailer/src/Exception.php');
    require_once('../../PHPMailer/src/PHPMailer.php');
    require_once('../../PHPMailer/src/SMTP.php');

    $user_id = $_POST['user_id'];
    $service_id = $_POST['service_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $request_image = file_get_contents($_FILES['request_image']['tmp_name']);
    $base_64 = base64_encode($request_image);

    $appointment_datetime = $appointment_date . ' ' . $appointment_time;
    $date = new DateTime($appointment_datetime);


    $formatted_date = $date->format('F j, Y (l)');
    $appointment_time_formatted = date('h:i A', strtotime($appointment_datetime));
    $current_datetime = date('F j, Y \a\t h:i A');


    $full_name = $_POST['full_name'];
    $service_name = $_POST['service_name'];

    $email = $_POST['email'];

    $subject = "New Appointment Notice! - " . date('F j, Y');
    $message = "
    <html>
    <head>
    <style>
    .container {
        font-family: 'Arial', sans-serif;
        color: #333;
        line-height: 1.6;
        max-width: 600px;
        margin: 0 auto;
        border: 1px solid #ddd;
        border-radius: 5px;
        overflow: hidden;
    }
    .header {
        background-color: #f8f9fa;
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    .content {
        padding: 20px;
    }
    .content p {
        margin: 0 0 15px;
        line-height: 1.8;
    }
    .highlight {
        color: #007bff;
        font-weight: bold;
    }
    .footer {
        background-color: #f8f9fa;
        padding: 10px;
        text-align: center;
        border-top: 1px solid #ddd;
        font-size: 12px;
        color: #666;
    }
    .appointment-details {
        background-color: #f0f0f0;
        padding: 15px;
        margin-top: 20px;
        border-top: 1px solid #ddd;
    }
    .appointment-details p {
        margin: 5px 0;
    }
    </style>
    </head>
    <body>
    <div class='container'>
    <div class='header'>
    <h2>New Appointment Confirmation</h2>
    </div>
    <div class='content'>
    <p>Good Day <span class='highlight'>$full_name</span>!</p>
    <p>You have successfully created an appointment:</p>
    <div class='appointment-details'>
    <p><strong>Procedure:</strong> <span class='highlight'>$service_name</span></p>
    <p><strong>Appointment Date:</strong> $formatted_date</p>
    <p><strong>Appointment Time:</strong> $appointment_time_formatted</p>
    </div>
    <hr>
    <br>
    <p><b><center>Thank you for choosing our services. We look forward to serving you.</center></b></p>
    <p><center><i>Please wait for the next email or website notification for the status update regarding your appointment.</i></center></p>
    </div>
    <div class='footer'>
    &copy; " . date('Y') . " Sta Maria Diagnostic Clinic. All rights reserved.
    <p><i>Email sent on: $current_datetime</i></p>
    </div>
    </div>
    </body>
    </html>
    ";

    sendMail($email, $subject, $message);

    try {
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "INSERT INTO tbl_appointments
        (user_id, service_id, appointment_date, appointment_time, request_image)
        VALUES (:user_id, :service_id, :appointment_date, :appointment_time, :request_image);";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':service_id', $service_id, PDO::PARAM_STR);
        $stmt->bindParam(':appointment_date', $appointment_date, PDO::PARAM_STR);
        $stmt->bindParam(':appointment_time', $appointment_time, PDO::PARAM_STR);
        $stmt->bindParam(':request_image', $request_image, PDO::PARAM_LOB);

        if ($stmt->execute()) {
            echo json_encode(array("status" => "success", "process" => "create appointment"));
        } else {
            echo json_encode(array("message" => "else statement reached"));
        }
    } catch (PDOException $e) {
        echo json_encode(array("status" => "error" . $e->getMessage()));
    }
}
?>