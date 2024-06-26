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
                  <th>Completed</th>
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
      function getStatusColor(status) {
        switch (status) {
          case 'PENDING':
            return '#3399ff';
          case 'CANCELLED':
            return '#ff9900';
          case 'REJECTED':
            return '#ff0000';
          case 'APPROVED':
            return '#009933';
          case 'undefined':
            return '#FFC0CB';
          default:
            return '#000000';
        }
      }

      function getCompletedColor(completed) {
        switch (completed) {
          case 'NO':
            return '#ff0000';
          case 'YES':
            return '#009933';
          default:
            return '#000000';
        }
      }

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
              console.log('QUEUE', response);
              var appointments = response.data;
              var currentSched = $('#currentSched');
              currentSched.empty();

              var currentQueueNumber = 0;
              var hasAppointments = false;

              appointments.forEach(function(appointment, index) {
                // console.log("REACH FOREACH");
                if (appointment.status === 'APPROVED') {
                  let statusColor = getStatusColor(appointment.status);
                  let completedColor = getCompletedColor(appointment.completed);
                  const isChecked = appointment.completed === 'YES' ? 'checked' : '';

                  currentSched.append(`
                    <tr>
                      <td>${currentQueueNumber + 1}</td>
                      <td>${appointment.first_name} ${appointment.last_name}</td>
                      <td>${appointment.service_name}</td>
                      <td>${appointment.formatted_time}</td>
                      <td style="color: ${statusColor};">${appointment.status}</td>
                      <td style="color: ${completedColor};">
                        <small class="completed-text">${appointment.completed}</small>
                        <input type="checkbox" class="complete-checkbox" data-id="${appointment.appointment_id}" ${isChecked}>
                      </td>
                    </tr>
                  `);
                  currentQueueNumber++;
                  hasAppointments = true;
                }
              });

              if (!hasAppointments) {
                currentSched.append(`
                  <tr>
                    <td colspan="6" class="text-center"><i>No data available</i></td>
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

      // Function to update appointment status
      function updateAppointmentCompleted(appointmentId, completed) {
        $.ajax({
          url: 'handles/appointments/set_completed_appointment.php',
          type: 'POST',
          data: {
            appointment_id: appointmentId,
            completed: completed
          },
          success: function(response) {
            if (response.status === 'success') {
              loadAppointments(); // Reload the appointments to reflect the changes
            } else {
              console.error('Error updating appointment:', response.message);
            }
          },
          error: function(error) {
            console.error('AJAX Error:', error);
          }
        });
      }

      // Event listener for the checkboxes
      $(document).on('change', '.complete-checkbox', function() {
          var checkbox = $(this);
          var isChecked = checkbox.is(':checked');
          var appointmentId = checkbox.data('id');
          var completed = isChecked ? 'YES' : 'NO';
          updateAppointmentCompleted(appointmentId, completed);
      });

      loadAppointments();
      setInterval(loadAppointments, 30000);
    });
  </script>
</body>
</html>