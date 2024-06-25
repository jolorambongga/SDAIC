<footer class="footer bg-dark text-light fixed-bottom">
	<div class="container-fluid ms-0 me-0">
		<div class="row mb-0 mt-0 ms-0 me-0 bg-">
			<div class="col md-6 mt-1 text-start ms-0 me-0 bg-">
				<h5 class="mb-0">Contact Us!</h5>
				<p class="mb-0">Email: spiralctscan2009@gmail.com | Phone: +123 456 7890</p>
			</div>
			<div class="col md-6 mt-1 text-end ms-0 me-0">
				<a href="https://www.facebook.com/spiralctscan2009" class="mb-0" target="_blank"
				style="text-decoration: none; color: #0865FF;">
				<img src="https://upload.wikimedia.org/wikipedia/commons/0/05/Facebook_Logo_(2019).png"
				alt="Facebook Page" width="18" height="atuo" class="mb-0">
				Sta. Maria Diagnostic and Imaging Center Facebook Page
			</a>
			<p class="mb-0">©
				<?php echo date("Y."); ?> Sta. Maria Diagnostic and Imaging Center
			</p>
		</div>
	</div>
</div>
</footer>

<script>
	$(document).ready(function () {
		// $('#btnRegister').click(function () {
		// 	window.location.href="register.php";
		// });
		// $('#btnLogin').click(function() {
		// 	window.location.href="login.php";
		// });

		$('#btnBookNow').click(function () {
			window.location.href="new_appointment.php";
		});
		$('#btnSeeServices').click(function() {
			window.location.href="#sectionService";
		});
		$('#btnNewAppointment').click(function() {
			window.location.href="new_appointment.php";
		});
	});
</script>

<script>
	function openNav() {
		document.getElementById("mySidebar").style.left = "0";
		document.getElementById("main").style.marginLeft = "250px";
	}

	function closeNav() {
		document.getElementById("mySidebar").style.left = "-250px";
		document.getElementById("main").style.marginLeft= "0";
	}

	$(document).ready(function () {
    $(document).on('click', '#btnLogout', function () {
        $.ajax({
            type: "GET",
            url: "handles/logout_endpoint.php",
            dataType: 'JSON',
            success: function(response) {
                console.log("LOGOUT RESPONSE", response);
                if (response.status === "success") {
                    var user_id = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;
                    var category = "USER";
                    var action = "LOG OUT";
                    var affected_data = "NONE";
                    
                    logAction(user_id, category, action, affected_data, function() {
                        window.location.href = response.redirect_user;
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

<?php
include_once('footer_script.php');
?>

</body>

</html>