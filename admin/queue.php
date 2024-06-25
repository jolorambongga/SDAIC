<?php
$title = 'Admin - Queue';
$active_queue = 'active';
include_once('header.php');
?>

<body>
  <!-- start wrapper -->
  <div class="my-wrapper">
    <!-- start container fluid -->
    <div class="container-fluid">
      <!-- start label -->
      <div class="row">
        <div class="col-12">
          <h1>Queue</h1>
          <h2 id="currentQueueNumber" class="display-3"></h2>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Queue Number</th>
                  <th>Patient Name</th>
                  <th>Procedure</th>
                  <th>Time</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody id="currentSched">
                <!-- Appointment rows will be inserted here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- end label -->
    </div>
    <!-- end container fluid -->
  </div>
  <!-- end wrapper -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>

  <script>
    $(document).ready(function() {
      function loadAppointments() {
        console.log('load appointment function');
        $.ajax({
          url: 'handles/appointments/read_appointments.php',
          type: 'GET',
          data: {
            today: 'true'
          },
          dataType: 'json',
          success: function(response) {
            if (response.status === 'success') {
              console.log('success', response);
              var appointments = response.data;
              var currentSched = $('#currentSched');
              currentSched.empty();

              var currentQueueNumber = 0;
              var hasAppointments = false;

              appointments.forEach(function(appointment, index) {
                if (appointment.status === 'APPROVE') {
                  currentSched.append(`
                    <tr>
                      <td>${currentQueueNumber + 1}</td>
                      <td>${appointment.first_name} ${appointment.last_name}</td>
                      <td>${appointment.service_name}</td>
                      <td>${appointment.formatted_time}</td>
                      <td>${appointment.status}</td>
                    </tr>
                  `);
                  currentQueueNumber++;
                  hasAppointments = true;
                }
              });

              if (!hasAppointments) {
                currentSched.append(`
                  <tr>
                    <td colspan="5" class="text-center"><i>No data available</i></td>
                  </tr>
                `);
              }

              $('#currentQueueNumber').text(currentQueueNumber);
            } else {
              console.error('Error loading appointments:', response.message);
            }
          },
          error: function(error) {
            console.error('AJAX Error:', error);
          }
        });
      }

      loadAppointments();
      setInterval(loadAppointments, 30000);
    });
  </script>
</body>
</html>
