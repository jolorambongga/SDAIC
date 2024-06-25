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
<script src="../admin/script/log_script.js"></script>
<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>