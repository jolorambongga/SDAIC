<?php
$title = "SDAIC - Registration";
$active_index = "";
$active_profile = "";
$active_your_appointments = "";
$active_new_appointment = "";
include_once('header.php');
include_once('handles/auth.php');
checkLoggedIn();
?>  

<link rel="stylesheet" href="../includes/css/my_register.css">
<div class="my-wrapper">
  <div class="register-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <h1>Register</h1>
          <form id="frm_register">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" id="firstName" name="firstName" aria-describedby="firstName" required>
              </div>
              <div class="col-md-4 mb-3">
                <label for="middleName" class="form-label">Middle Name</label>
                <input type="text" class="form-control" id="middleName" name="middleName" aria-describedby="middleName">
              </div>
              <div class="col-md-4 mb-3">
                <label for="surname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="lastName" name="lastName" aria-describedby="surname" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="contact" class="form-label">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact" aria-describedby="contact" maxlength="11" pattern="[0-9]*" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="birthday" class="form-label">Birthday</label>
                <input type="date" class="form-control" id="birthday" name="birthday" aria-describedby="birthday" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="email" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" aria-describedby="username" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" aria-describedby="password" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" aria-describedby="confirmPassword" required>
              </div>
            </div>
            <button type="submit" class="btn btn-mydark float-end">Submit</button>
          </form>
          <pre></pre>
          <pre></pre>
          <pre></pre>
          <pre></pre>
          <p class="text-center mt-3">Already have an account? <a href="login.php">Log in</a></p>
        </div>
      </div>
    </div>
  </div>
</div>






<script>
  $(document).ready(function(){
    // Function to validate username on keypress
    $("#username").on("keypress", function(event) {
      var regex = /^[a-z]+$/;
      var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
      if (!regex.test(key)) {
        event.preventDefault();
        return false;
      }
    });

    $("#contact").on("keypress", function(event) {
      var keyCode = event.which ? event.which : event.keyCode;
      if (keyCode < 48 || keyCode > 57) { // Allow only 0-9
        event.preventDefault();
      }
    });

    $("#username").on("input", function() {
      $(this).val(function(_, val) {
        return val.toLowerCase();
      });
    });

    $("#contact").on("input", function() {
      $(this).val(function(_, val) {
        return val.replace(/\s/g, ''); // Remove spaces
      });
    });

    $("#frm_register").on("submit", function(event){
      event.preventDefault();

      var password = $("#password").val().trim();
      var confirmPassword = $("#confirmPassword").val().trim();

      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return;
      }

      var username = $("#username").val().trim();
      if (!/^[a-z]+$/.test(username)) {
        alert("Username should contain only lowercase letters.");
        return;
      }

      $.ajax({
        url: "handles/register_endpoint.php",
        type: "POST",
        data: $(this).serialize(),
        success: function(response){
          if (response.isTaken === "true") {
            alert("Username or email is already taken.");
          } else if (response.status === "success") {
            console.log(response);
            window.location.href = "new_appointment.php";
          } else {
            console.log(response);
          // window.location.href="index.php";
          }
        },
        error: function(error){
          console.log("Error: ", error);
        }
      });
    });
  });
</script>


<?php
include_once('footer.php');
?>  