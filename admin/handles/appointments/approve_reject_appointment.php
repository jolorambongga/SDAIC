<?php

require_once('../../../includes/config.php');
require_once('../../mail/mail_script.php');
require_once('../../../PHPMailer/src/Exception.php');
require_once('../../../PHPMailer/src/PHPMailer.php');
require_once('../../../PHPMailer/src/SMTP.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$appointment_id = $_POST['appointment_id'];
	$status = $_POST['status'];
	$user_input = $_POST['user_input'];

	$sql = "UPDATE tbl_Appointments
	SET status = :status
	WHERE appointment_id = :appointment_id;";

	$stmt = $pdo->prepare($sql)			;

	$stmt->bindParam(':status', $status, PDO::PARAM_STR);
	$stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);

	$stmt->execute();

	$sql = "SELECT *, CONCAT(u.first_name, ' ', u.last_name) as full_name, s.service_name
	FROM tbl_Appointments as a
	LEFT JOIN tbl_Users as u
	ON a.user_id = u.user_id
	LEFT JOIN tbl_Services as s
	ON a.service_id = s.service_id
	WHERE appointment_id = :appointment_id;";

	$stmt = $pdo->prepare($sql);

	$stmt->bindParam(':appointment_id', $appointment_id, PDO::PARAM_INT);

	$stmt->execute();

	$data = $stmt->fetch(PDO::FETCH_ASSOC);

	$data['request_image'] = base64_encode($data['request_image']);



	$appointment_date = $data['appointment_date'];
	$appointment_time = $data['appointment_time'];

	if (is_string($appointment_date)) {
		$appointment_date = new DateTime($appointment_date);
	}

	$formatted_date = $appointment_date->format('F j, Y (l)');
	$appointment_time_formatted = date('h:i A', strtotime($appointment_time));
	$current_datetime = date('F j, Y \a\t h:i A');


	$full_name = $data['full_name'];
	$service_name = $data['service_name'];

	$email = $data['email'];

	$subject = "Appointment Status Update! - " . date('F j, Y');

	$status = $data['status'];

	$approved = "Please come on the date and time provided above. Thank You!";
	$rejected = "See notes for information as to why it has been rejected, if you have further questions you may inquire through our social media and contacts. You may also try re-applying for an appointment. Thank You!";

	if($status === 'APPROVED') {
		$status_message = $approved;
		$color = "green";
	} else if ($status === 'REJECTED') {
		$status_message = $rejected;
		$color = "red";
	}

	$status_title = "<b>Has been <span style='color: $color;'>$status</span></b>";

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
	<h2>Appointment Status Update</h2>
	</div>
	<div class='content'>
	<p>Good Day <span class='highlight'>$full_name</span>!</p>
	<p>Your recently submitted appointment for:</p>
	<div class='appointment-details'>
	<p><strong>Procedure:</strong> <span class='highlight'>$service_name</span></p>
	<p><strong>Appointment Date:</strong> $formatted_date</p>
	<p><strong>Appointment Time:</strong> $appointment_time_formatted</p>
	</div>
	<hr>
	<br>
	<p><bold><center>$status_title!</center></bold></p>
	<p><center>$status_message</center></p>
	</div>
	<div class='footer'>
	<p>&copy; " . date('Y') . " Sta Maria Diagnostic Clinic. All rights reserved.</p>
	<p><i>Email sent on: $current_datetime</i></p>
	</div>
	</div>
	</body>
	</html>
	";

	sendMail($email, $subject, $message);

	header('Content-Type: application/json');

	echo json_encode(array("status" => "success", "process" => "reject appointment", "data" => $data));

} catch (PDOException $e) {
	echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "reject appointment", "report" => "catch reached"));
}
?>
