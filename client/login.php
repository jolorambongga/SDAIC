<?php
$title = "SDAIC - Log In";
$active_index = "";
$active_profile = "";
$active_your_appointments = "";
$active_new_appointment = "";
$active_login="active";
include_once('header.php');
include_once('handles/auth.php');
checkLoggedIn();
?>

<link rel="stylesheet" href="../includes/css/my_login.css">

<div class="my-wrapper">
  <div class="login-wrapper">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-md-4">
          <div class="login-box">
            <h1 class="text-center mb-4">Log In</h1>
            <form method="post" id="frm_login">
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="login" name="login" required>
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
              </div>
              <div class="mb-5">
                <div class="form-group">
                  <input value="Log In" id="btnLogin" type="submit" class="btn btn-mydark float-end btn-block">
                </div>
              </div>
            </form>
            <pre></pre>
            <pre></pre>
            <p class="text-center">Don't have an account? <a href="register.php">Sign up</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    $('#frm_login').submit(function (e) {
        e.preventDefault();

        var login = $('#login').val();
        var password = $('#password').val();

        $.ajax({
            type: 'POST',
            url: 'handles/login_endpoint.php',
            data: { login: login, password: password },
            dataType: 'JSON',
            success: function(response) {
                console.log(response);
                if (response.isWrong === "true") {
                    alert("Wrong Email or Password!");
                } else if (response.isNotFound === "true") {
                    alert("User NOT Found!");
                } else if (response.change_password === "true") {
                    window.location.href = response.redirect;
                } else if (response.status === "success") {
                    var user_id = response.user_id;
                    var category = response.role_id === 1 ? "ADMIN" : "USER";
                    var action = "LOG IN";
                    var affected_data = "NONE";

                    logAction(user_id, category, action, affected_data, function(logSuccess) {
                        if(logSuccess) {
                            window.location.href = response.role_id === 1 ? response.redirect : 'new_appointment.php';
                        } else {
                            alert("Error logging in!");
                        }
                    });
                } else {
                    console.error("Login failed:", response.message);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    });
});

</script>


<?php
include_once('footer.php');
?>
