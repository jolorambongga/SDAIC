<?php date_default_timezone_set('Asia/Manila'); ?>
<?php include_once('admin_auth.php')?>
<?php include_once('change_pass_auth.php')?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css'/>
	<link rel='stylesheet' href='../includes/css/my_css.css'/>
	<link rel='stylesheet' href='../includes/css/my_radio.css'/>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

	<script src="https://cdn.jsdelivr.net/npm/bowser"></script>
	<script src="script/log_script.js"></script>
</head>

<body>
	<!-- nav bar -->
	<nav class="navbar navbar-expand-lg sticky-md-top shadow-sm" style="background-color: #FFD95A;">
		<div class="container-fluid">
			<a class="navbar-brand" href="admin_dashboard.php" style="color: black;">
				<img src="https://www.logolynx.com/images/logolynx/2a/2ad00c896e94f1f42c33c5a71090ad5e.png" alt="Logo"
				width="30" height="auto" class="d-inline-block align-text-top">
				STA. MARIA DIAGNOSTIC AND IMAGING CENTER
			</a>
		</div>
	</nav>
	<!-- end nav bar -->

	<!-- header -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-10 d-flex align-items-end" style="background-color: #C07F00;">

				<!-- dash, profile, appointments, doctors, services, users, logs -->

				<!-- dashboard -->
				<ul class="nav nav-tabs my_nav">
					<li class="nav-item">
						<a class="nav-link <?php echo $active_dashboard ?>" href="admin_dashboard.php">Dashboard</a>
					</li>
				</ul>

				<!-- profie admin -->
				<ul class="nav nav-tabs my_nav">
					<li class="nav-item">
						<a class="nav-link <?php echo $active_profile ?>" href="admin_profile.php">Profile</a>
					</li>
				</ul>

				<!-- appointments | view appointments -->
				<ul class="nav nav-tabs my_nav">
					<li class="nav-item">
						<a class="nav-link <?php echo $active_appointments ?>" href="view_appointments.php">Appointments</a>
					</li>
				</ul>

				<!-- doctors | edit doctors -->
				<ul class="nav nav-tabs my_nav">
					<li class="nav-item">
						<a class="nav-link <?php echo $active_doctors ?>" href="edit_doctors.php">Doctors</a>
					</li>
				</ul>

				<!-- services | edit services -->
				<ul class="nav nav-tabs my_nav">
					<li class="nav-item">
						<a class="nav-link <?php echo $active_services ?>" href="edit_services.php">Services</a>
					</li>
				</ul>

				<!-- users | edit users -->
				<ul class="nav nav-tabs my_nav">
					<li class="nav-item">
						<a class="nav-link <?php echo $active_users ?>" href="edit_users.php">Users</a>
					</li>
				</ul>

				<!-- logs -->
				<ul class="nav nav-tabs my_nav">
					<li class="nav-item">
						<a class="nav-link <?php echo $active_logs ?>" href="logs.php">Logs</a>
					</li>
				</ul>

				<!-- landing -->
				<ul class="nav nav-tabs my_nav">
					<li class="nav-item">
						<a class="nav-link <?php echo $active_landing ?>" href="landing.php">Landing</a>
					</li>
				</ul>

				<!-- queue -->
				<ul class="nav nav-tabs my_nav">
					<li class="nav-item">
						<a class="nav-link <?php echo $active_queue ?>" href="queue.php">Queue</a>
					</li>
				</ul>


			</div>
			<div class="col-md-2 d-flex justify-content-end" style="background-color: #C07F00;">
				<!-- IF SET CONDITION FOR BUTTONS -->
				<button id="btnLogout" type="button" class="btn btn-mydark me-2 mt-2 mb-2">Log-Out</button>
			</div>
		</div>
	</div>

	<!-- end header -->

	<script>
		$(document).ready(function () {
			$(document).on('click', '#btnLogout', function () {
				$.ajax({
					type: "GET",
					url: "../client/handles/logout_endpoint.php",
					dataType: 'JSON',
					success: function(response) {
						console.log("LOGOUT RESPONSE", response);
						if(response.status === "success") {
							var user_id = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;
							var category = "ADMIN";
							var action = "LOG OUT";
							var affected_data = "NONE";
							logAction(user_id, category, action, affected_data, function() {
								window.location.href = response.redirect_admin;
							});
							
						} else {
							console.error("Logout failed:", response.message);
						}
					},
					error: function(error) {
						console.log("LOGOUT ERROR", error);
						alert("ERROR TRYING TO LOGOUT!");
					}
				});
			});
		});
	</script>