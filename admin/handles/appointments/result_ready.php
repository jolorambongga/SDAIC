<?php

require_once('../../../includes/config.php');
require_once('../../mail/mail_script.php');
require_once('../../../PHPMailer/src/Exception.php');
require_once('../../../PHPMailer/src/PHPMailer.php');
require_once('../../../PHPMailer/src/SMTP.php');

try {

	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$appointment_id = $_POST['appointment_id'];
	$additional_note = isset($_POST['additional_note']) ? "<b>Note: </b> <i>" . $_POST['additional_note'] . "</i>" : '';


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

	$subject = "Notice for Procedure Results! - " . date('F j, Y');

	$status_title = "Results ready for <span style='color: green;'>CLAIM!</span>";

	$status_message = "Please come by at the clinic at any time during clinic hours, or you may read additional information below for additional notes. Thank you for using our services!";

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
	<h2>Notice on Results</h2>
	</div>
	<div class='content'>
	<p>Good Day <span class='highlight'>$full_name</span>!</p>
	<p>Your recently procedure for:</p>
	<div class='appointment-details'>
	<p><strong>Procedure:</strong> <span class='highlight'>$service_name</span></p>
	<p><strong>Appointment Date:</strong> $formatted_date</p>
	<p><strong>Appointment Time:</strong> $appointment_time_formatted</p>
	</div>
	<hr>
	<br>
	<p><b><center>$status_title!</center></b></p>
	<p><center>$status_message</center></p>
	<p><center>$additional_note</center></p>
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

	echo json_encode(array("status" => "success", "process" => "email_result_ready", "data" => $additional_note));

} catch (PDOException $e) {
	echo json_encode(array("status" => "error", "message" => $e->getMessage(), "process" => "email_result_ready", "report" => "catch_reached"));
}
?>