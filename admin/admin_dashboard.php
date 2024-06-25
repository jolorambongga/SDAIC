<?php
$title = 'Admin - Dashboard';
$active_dashboard = 'active';
include_once('header.php');
?>

<link rel="stylesheet" href="../includes/css/admin_dashboard.css"/>

<body>

  <div class="my-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-4">
          <h1>Admin Dashboard</h1>
        </div>
      </div>
    </div>
  </div>
  
  <div class="container dashboard-grid">
    <div class="big-box light-box">
      <h3>Appointment Today</h3>
      <p id="appointment_today" style="font-size: 50px; float: right;">Content for appointment today</p>
    </div>
    <div class="big-box medium-box">
      <h3>Appointment this Week</h3>
      <p id="appointment_week" style="font-size: 50px; float: right;">Content for appointment this week</p>
    </div>  
    <div class="big-box shadow-box">
      <h3>Registered Users</h3>
      <p id="registered_users" style="font-size: 50px; float: right;">Info 1 details here</p>
    </div>
    <div class="big-box dark-box">
      <h2>Pending Today</h2>
      <p id="pending_today" style="font-size: 50px; float: right;">Info 2 details here</p>
    </div>
    <div class="big-box dark-box">
      <h2>Pending this Week</h2>
      <p id="pending_week" style="font-size: 50px; float: right;">Info 2 details here</p>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      loadToday();
      loadWeek();
      loadPendingToday();
      loadPendingWeek();
      loadRegisteredUsers();

      function loadToday() {
        $.ajax({
          type: 'GET',
          dataType: 'JSON',
          url: 'handles/dashboard/read_appointments_today.php',
          success: function(response) {
            var count = response.appointment_count;
            $('#appointment_today').empty().append(count);
          },
          error: function(error) {
            console.log(error);
          }
        });
      }

      function loadWeek() {
        $.ajax({
          type: 'GET',
          dataType: 'JSON',
          url: 'handles/dashboard/read_appointments_week.php',
          success: function(response) {
            var count = response.appointment_count;
            $('#appointment_week').empty().append(count);
          },
          error: function(error) {
            console.log(error);
          }
        });
      }

      function loadPendingToday() {
        $.ajax({
          type: 'GET',
          dataType: 'JSON',
          url: 'handles/dashboard/read_pending_appointments_today.php',
          success: function(response) {
            var count = response.appointment_count;
            $('#pending_today').empty().append(count);
          },
          error: function(error) {
            console.log(error);
          }
        });
      }

      function loadPendingWeek() {
        $.ajax({
          type: 'GET',
          dataType: 'JSON',
          url: 'handles/dashboard/read_pending_appointments_week.php',
          success: function(response) {
            var count = response.appointment_count;
            $('#pending_week').empty().append(count);
          },
          error: function(error) {
            console.log(error);
          }
        });
      }

      function loadRegisteredUsers() {
        $.ajax({
          type: 'GET',
          dataType: 'JSON',
          url: 'handles/dashboard/read_registered_users.php',
          success: function(response) {
            console.log(response);
            var count = response.user_count;
            $('#registered_users').empty();
            $('#registered_users').append(count);
          },
          error: function(error) {
            console.log(error);
          }
        });
      }


    });

  </script>

  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>
</body>

</html>
