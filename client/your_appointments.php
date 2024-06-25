<?php
$title = "SDAIC - Appointments";
$active_index = "";
$active_profile = "";
$active_your_appointments = "active";
$active_new_appointment = "";
include_once('header.php');
include_once('handles/auth.php');
checkAuth();
?>  

<div class="my-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-4">
        <h1>Your appointments</h1>
      </div>
    </div>
    <!-- add button -->
    <div class="row">
      <div class="col-12">
        <button id="btnAddNewAppointment" type="button" class="btn btn-mydark btn-sm mt-3 mb-3 float-end">ADD NEW APPOINTMENT</button>
      </div>
    </div>
    <!-- end button -->
    <!-- table -->
    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped table-success text-end">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Procedure Name</th>
              <th scope="col">Appointment Date</th>
              <th scope="col">Appointment Time</th>
              <th scope="col">Request Image</th>
              <th scope="col">Status</th>
              <th scope="col">Completed</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody id="tbodyAppointments">

          </tbody>
        </table>
      </div>
    </div>
    <!-- end table -->
    <!-- start image modal -->
    <div class="modal fade" id="mod_ReqImg" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod_ReqImgLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="mod_ReqImgLabel"></h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div id="imgBody" class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- end image modal -->
    <!-- start modal cancel appointment -->
    <div class="modal fade" id="mod_CancelAppointment" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod_CancelAppointmentLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="mod_CancelAppointmentLabel">Cancel this appointment?</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <label for="cancel_user_input" class="form-label">Type <b>CANCEL</b> to cancel your <span id="cancelAppointmentName"></span>'s appointment.</label>
            <input type="text" id="cancel_user_input" class="form-control" required>  
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button id="btnCancel" data-appointment-id="" type="button" class="btn btn-danger">Cancel Appointment</button>
          </div>
        </div>
      </div>
    </div>
    <!-- end modal cancel appointment -->
  </div>
</div>

<script>
  $(document).ready(function() {
    loadAppointments();
    // READ APPOINTMENTS
    function loadAppointments() {
      var user_id = "<?php echo($_SESSION['user_id']);?>";
      console.log("USER ID: (your appointments load)::", user_id);
      
      $.ajax({
        type: 'GET',
        url: 'handles/read_appointments.php',
        data: {user_id: user_id},
        dataType: 'JSON',
        success: function(response) {
          console.log(response);
          $('#tbodyAppointments').empty();

          response.data.forEach(function(data) {
            let statusColor = '';
            switch (data.status) {
            case 'PENDING':
              statusColor = '#3399ff';
              break;
            case 'CANCELLED':
              statusColor = '#ff9900';
              break;
            case 'REJECTED':
              statusColor = '#ff0000';
              break;
            case 'APPROVED':
              statusColor = '#009933';
              break;
            case 'undefined':
              statusColor = '#FFC0CB';
              break;
            default:
              statusColor = '#000000';
            }

            let completedColor = '';
            switch (data.completed) {
            case 'NO':
              completedColor = '#ff0000';
              break;
            case 'YES':
              completedColor = '#009933';
              break;
            case 'undefined':
              completedColor = '#FFC0CB';
              break;
            default:
              completedColor = '#000000';
            }

            // Check if status is CANCELLED to conditionally hide the cancel button
            const cancelButtonHtml = (data.status === 'CANCELLED' || data.status === 'REJECTED') ?
            '' :
            `<div class="d-grid gap-2 d-md-flex justify-content-md-end text-center">
            <button id="callCancel" data-bs-toggle="modal" data-bs-target="#mod_CancelAppointment" type="button" class="btn btn-danger btn-sm">Cancel</button>
            </div>`;

            const read_appointments_html = `
            <tr>
            <th scope="row"><small>${data.appointment_id}</small></th>
            <td><small>${data.service_name}</small></td>
            <td><small>${data.formatted_date}</small></td>
            <td><small>${data.formatted_time}</small></td>
            <td data-appointment-id='${data.appointment_id}'>
            <button id='callReqImg' type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#mod_ReqImg'>View Image</button>
            </td>
            <td style='color: ${statusColor};'><small>${data.status}</small></td>
            <td style='color: ${completedColor};'><small>${data.completed}</small></td>
            <td data-appointment-id='${data.appointment_id}'>
            ${cancelButtonHtml}
            </td>
            </tr>
            `;

            $('#tbodyAppointments').append(read_appointments_html);
          });
        },
        error: function(error) {
          console.log(error);
        }
      });
    } // END FUNCTION

    // GO TO NEW APPOINTMENT
    $('#btnAddNewAppointment').click(function() {
      window.location.href="new_appointment.php";
    });

    // GET IMAGE
    $('#tbodyAppointments').on('click', '#callReqImg', function() {
      var appointment_id = $(this).closest("td").data('appointment-id');
      console.log("console click", appointment_id);
      $.ajax({
        type: 'GET',
        url: '../admin/handles/appointments/get_image.php',
        dataType: 'JSON',
        data: { appointment_id: appointment_id },
        success: function(response) {
          if (response.status === "success") {
            $('#imgBody').html(`<img src="data:image/png;base64,${response.data.request_image}" class="img-fluid" alt="Request Image">`);
            $('#mod_ReqImg .modal-title').text(`Request Image - ${response.data.service_name}`);
            console.log(response);
          } else {
            console.log("Image not found for appointment ID: " + appointment_id);
          }
        },
        error: function(error) {
          console.log(error);
        }
      });
    }); // END GET IMAGE

    // CALL CANCEL APPOINTMENT
    $(document).on('click', '#callCancel', function() {
      var appointment_id = $(this).closest('td').data('appointment-id');
      console.log("APPOINTMENT ID CALL CANCEL BTN:", appointment_id);

      $('#btnCancel').data('appointment-id', appointment_id);

      var idCancel = $('#btnCancel').data('appointment-id');
      console.log(idCancel);
    });

    $(document).on('click', '#btnCancel', function() {
      var appointment_id = $(this).data('appointment-id');
      var user_input = $('#cancel_user_input').val();

      if (user_input !== 'CANCEL') {
        alert('Please type CANCEL to confirm.');
        console.log(appointment_id);
        return;
      }

      $.ajax({
        type: 'POST',
        url: 'handles/cancel_appointment.php',
        data: {appointment_id: appointment_id, user_input: user_input},
        dataType: 'JSON',
        success: function(response) {
          console.log("CANCEL APPOINTMENT RESPONSE:", response);
          loadAppointments();
          $('#mod_CancelAppointment').modal('hide');
        },
        error: function(error) {
          console.log("CANCEL APPOINTMENT ERROR:", error);
        }
      });
    });

  });
</script>

<?php
include_once('footer_script.php');
?>