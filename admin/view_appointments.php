<?php
$title = "Admin - Appointments";
$active_appointments = "active";
include_once "header.php";
// echo date_default_timezone_get();
?>

<body>

  <div class="my-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-4">
          <h1>View Appointments</h1>
        </div>
      </div>
      <!-- add button -->
      <div class="row">
        <div class="col-12">
          <!-- <button type="button" class="btn btn-mydark mt-3 mb-3 float-end btn-sm">IDK YET</button> -->
        </div>
      </div>
      <div class="row bg-">
        <div class="col-10 bg-">

        </div>
        <div class="col-2 text-end">          
          <strong>For Today</strong>
          <input type="checkbox" id="cbxToday" class="text-align-center text-center justify-content-middle justify-content-center">
        </div>
      </div>
      <!-- end button -->
      <!-- table -->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-striped text-end">
            <thead>              
              <span class="filters">
                <ul class="nav nav-tabs" id="my_nav">

                </ul>
              </span>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Patient</th>
                <th scope="col">Procedure</th>
                <th scope="col">Date</th>
                <th scope="col">Time</th>
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
      <!-- start approve modal -->
      <div class="modal fade" id="mod_Approve" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod_ApproveLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="mod_ApproveLabel">Approve this appointment?</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <label for="approve_user_input" class="form-label">Type <b>APPROVE</b> to approve <span id="approvePatientName"></span>'s <span id="approveAppointmentName"></span> appointment.</label>
              <input type="text" id="approve_user_input" class="form-control" required="">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button id="btnApprove" data-appointment-id="" type="button" class="btn btn-success">Approve</button>
            </div>
          </div>
        </div>
      </div>
      <!-- end approve modal -->

      <!-- start reject modal -->
      <div class="modal fade" id="mod_Reject" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod_RejectLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="mod_RejectLabel">Reject this appointment?</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <label for="reject_user_input" class="form-label">Type <b>REJECT</b> to reject <span id="rejectPatientName"></span>'s <span id="rejectAppointmentName"></span> appointment.</label>
              <input type="text" id="reject_user_input" class="form-control" required><pre></pre>

              <label for="reject_reason" class="form-label">Reason of Rejection</label>
              <input type="text" id="reject_reason" class="form-control" required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button id="btnReject" data-appointment-id="" type="button" class="btn btn-danger">Reject</button>
            </div>
          </div>
        </div>
      </div>
      <!-- end reject modal -->
      <?php
      include_once('modals/receipt_modal.php');
      ?>
      <?php
      include_once('modals/result_modal.php');
      ?>
      <input type="hidden" id="activeServiceId" value="All">
    </div>
  </div>

  <script>
    $(document).ready(function() {

      // loadAppointments();
      loadFilters();
      function loadFilters() {
        $.ajax({
          type: 'GET',
          url: 'handles/services/read_services.php',
          dataType: 'JSON',
          success: function(response) {
            console.log("SUCCESS IN FILTER FUNCTION", response);
            $('#my_nav').empty();
            $('#my_nav').append('<li class="nav-item"><a class="nav-link active" data-service-id="All" href="#">All</a></li>');
            response.data.forEach(function(service) {
              $('#my_nav').append(`<li class="nav-item"><a class="nav-link" data-service-id="${service.service_id}" href="#">${service.service_name}</a></li>`);
            });
            $('#my_nav a').on('click', function(event) {
              event.preventDefault();
              $(this).tab('show');
              var service_id = $(this).data('service-id');
              $('#activeServiceId').val(service_id);

              // event.preventDefault();
              // $(this).tab('show');
              var activeServiceId = $('#activeServiceId').val();
              var todayChecked = $('#cbxToday').is(':checked');
              loadFilteredAppointments(activeServiceId, todayChecked);
              console.log('nav link', activeServiceId, todayChecked);
            });
          },
          error: function(error) {
            console.log("ERROR IN LOAD FILTERS FUNCTION", error);
          }
        });
      }

      // Function to load initial appointments
      function loadInitialAppointments() {
        var activeServiceId = $('#activeServiceId').val();
        var todayChecked = $('#cbxToday').is(':checked');
        loadFilteredAppointments(activeServiceId, todayChecked);
      }

    // Function to load appointments based on service_id and today flag
      function loadFilteredAppointments(service_id, today) {
        var url = 'handles/appointments/read_appointments.php';
        // var service_id = $('#activeServiceId').val();
        var data = { service_id: service_id };
        if (today) {
          data.today = 'true';
          console.log('filtered function', service_id, today);
        }

        $.ajax({
          type: 'GET',
          url: url,
          dataType: 'json',
          data: data,
          success: function(response) {
            console.log(response);
            $('#tbodyAppointments').empty();
            var appointment_body = $('#tbodyAppointments');
            var hasAppointments = false;
            response.data.forEach(function(data) {
              let statusColor = getStatusColor(data.status);
              let completedColor = getCompletedColor(data.completed);
              const isChecked = data.completed === 'YES' ? 'checked' : '';
              const read_appointments_html = `
              <tr>
              <th scope="row"><small>${data.appointment_id}</small></th>
              <td><small>${data.first_name} ${data.last_name}</small></td>
              <td><small>${data.service_name}</small></td>
              <td><small>${data.formatted_date}</small></td>
              <td><small>${data.formatted_time}</small></td>
              <td data-appointment-id='${data.appointment_id}'>
              <button id='callReqImg' type='button' class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#mod_ReqImg'>View Image</button>
              </td>
              <td style='color: ${statusColor};'><small>${data.status}</small></td>
              <td data-appointment-id='${data.appointment_id}' style='color: ${completedColor};'>
              <small class="completed-text">${data.completed}</small>
              <input type="checkbox" class="cbxCompleted" data-completed-yes="YES" data-completed-no="NO" ${isChecked} />
              </td>
              <td data-appointment-id='${data.appointment_id}' data-email="${data.email}" data-full-name="${data.first_name} ${data.last_name}" data-appointment-name="${data.service_name}" data-appointment-date="${data.formatted_date}" data-appointment-time="${data.formatted_time}" data-price="${data.cost}">
              <div class="d-grid gap-2 d-md-flex justify-content-md-end text-center">

              <button id='callReject' data-bs-toggle="modal" data-bs-target="#mod_Reject" type='button' class='btn btn-mymedium btn-sm'>
              <i class="fas fa-thumbs-down"></i>
              </button>

              <button id='callApprove' data-bs-toggle="modal" data-bs-target="#mod_Approve" type='button' class='btn btn-myshadow btn-sm'>
              <i class="fas fa-thumbs-up"></i>
              </button>

              <button id='callReceipt' data-bs-toggle="modal" data-bs-target="#mod_Input" type='button' class='btn btn-mydark btn-sm'>
              <i class="fas fa-receipt"></i>
              </button>

              <button id='callResult' data-bs-toggle="modal" data-bs-target="#mod_Result" type='button' class='btn btn-mylight btn-sm'>
              <i class="fas fa-folder"></i>
              </button>

              </div>
              </td>
              </tr>
              `;
              appointment_body.append(read_appointments_html);
              hasAppointments = true;
            });

            if (!hasAppointments) {
              appointment_body.append(`
                <tr>
                <td colspan="9" class="text-center"><i>No data available</i></td>
                </tr>
                `);
            }
          },
          error: function(error) {
            console.log("ERROR", error);
          }
        });
      }


      loadInitialAppointments();

    // Event handler for checkbox change
      $('#cbxToday').change(function() {
        var activeServiceId = $('#activeServiceId').val();
        var todayChecked = $(this).is(':checked');
        loadFilteredAppointments(activeServiceId, todayChecked);
        console.log('checkbox changed', activeServiceId, todayChecked);
      });


    // Helper functions for status and completed colors
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

      // GET IMAGE
      $('#tbodyAppointments').on('click', '#callReqImg', function() {
        var appointment_id = $(this).closest("td").data('appointment-id');
        console.log("console click", appointment_id);
        $.ajax({
          type: 'GET',
          url: 'handles/appointments/get_image.php',
          dataType: 'JSON',
          data: { appointment_id: appointment_id },
          success: function(response) {
            if (response.status === "success") {
              $('#imgBody').html(`<img src="data:image/png;base64,${response.data.request_image}" class="img-fluid" alt="Request Image">`);
              $('#mod_ReqImg .modal-title').text(`Request Image - ${response.data.first_name} ${response.data.last_name} (${response.data.service_name})`);
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

// COMPLETED CHECK FUNCTION
      $('#tbodyAppointments').on('change', '.cbxCompleted', function() {
        console.log("CHANGED");
        var checkbox = $(this);
        var isChecked = checkbox.is(':checked');
        var appointment_id = checkbox.closest('td').data('appointment-id');
        var completedYes = checkbox.data('completed-yes');
        var completedNo = checkbox.data('completed-no');
        var completed = isChecked ? completedYes : completedNo;

        $.ajax({
          type: 'POST',
          url: 'handles/appointments/set_completed_appointment.php',
          data: { completed: completed, appointment_id: appointment_id },
          dataType: 'JSON',
          success: function(response) {
            if (response.status === 'success') {
              console.log("SUCCESS CHECKBOX:", response);
              var updatedRow = response.data;
// Update the completed text and color
              var completedCell = checkbox.closest('td');
              completedCell.find('.completed-text').text(updatedRow.completed);
              completedCell.css('color', updatedRow.completed === 'YES' ? '#009933' : '#ff0000');
              checkbox.prop('checked', updatedRow.completed === 'YES');
            } else {
              console.log("ERROR CHECKBOX:", response);
            }
          },
          error: function(error) {
            console.log("ERROR CHECKBOX:", error);
          }
        });
      });

// APPROVE APPOINTMENT
      $(document).on('click', '#callApprove', function() {
        var appointment_id = $(this).closest('td').data('appointment-id');
        var patient_name = $(this).closest('td').data('full-name');
        var appointment_name = $(this).closest('td').data('appointment-name');
        console.log(appointment_id, patient_name, appointment_name);

        $('#approvePatientName').text(patient_name);
        $('#approveAppointmentName').text(appointment_name);

        $('#btnApprove').data('appointment-id', appointment_id);

        var approveBTN = $('#btnApprove').data('appointment-id');
        console.log("APPROVE BUTTIN ID!!!!", approveBTN);

      });

      $(document).on('click', '#btnApprove', function() {
        var appointment_id = $(this).data('appointment-id');
        var status = "APPROVED";
        var user_input = $('#approve_user_input').val();

        var data = {
          appointment_id: appointment_id,
          status: status,
          user_input: user_input
        }

        console.log(appointment_id, user_input);

        if (user_input !== 'APPROVE') {
          alert('Please type APPROVE to approve.');
          console.log(appointment_id);
          return;
        }
        $.ajax({
          type: 'POST',
          url: 'handles/appointments/approve_reject_appointment.php',
          data: data,
          dataType: 'JSON',
          success: function(response) {
            console.log("SUCESS APPROVE BTN CLICK",response);
            loadFilteredAppointments();
            $('#mod_Approve').modal('hide');
            $('#approve_user_input').val('');
            console.log(response.data.full_name);
            console.log(response.data.service_name);
            console.log(response.data.appointment_date);
            console.log(response.data.appointment_time);
          },
          error: function(error) {
            console.log("ERROR APPROVE BTN CLICK",error);
          }
        });
      });

// REJECT APPOINTMENT
      $(document).on('click', '#callReject', function() {
        var appointment_id = $(this).closest('td').data('appointment-id');
        var patient_name = $(this).closest('td').data('full-name');
        var appointment_name = $(this).closest('td').data('appointment-name');
        console.log(appointment_id, patient_name, appointment_name);

        $('#rejectPatientName').text(patient_name);
        $('#rejectAppointmentName').text(appointment_name);

        $('#btnReject').data('appointment-id', appointment_id);

        var rejectBTN = $('#btnReject').data('appointment-id');
        console.log("REJECT BUTTIN ID!!!!", rejectBTN);

      });

      $(document).on('click', '#btnReject', function() {
        var appointment_id = $(this).data('appointment-id');
        var status = "REJECTED";
        var user_input = $('#reject_user_input').val();
        var reason = $('#reject_reason').val();

        var data = {
          appointment_id: appointment_id,
          status: status,
          user_input: user_input,
          reason: reason
        }

        console.log(appointment_id, user_input);

        if (user_input !== 'REJECT') {
          alert('Please type REJECT to reject.');
          console.log(appointment_id);
          return;
        }

        if (!reason) {
          alert('Please type the reason of rejection');
          return;
        }
        $.ajax({
          type: 'POST',
          url: 'handles/appointments/approve_reject_appointment.php',
          data: data,
          dataType: 'JSON',
          success: function(response) {
            console.log("SUCESS REJECT BTN CLICK",response);
            console.log(response);
            loadFilteredAppointments();
            $('#mod_Reject').modal('hide');
            $('#reject_user_input').val('');
            $('#reject_reason').val('');
            console.log(response.data.full_name);
            console.log(response.data.service_name);
            console.log(response.data.appointment_date);
            console.log(response.data.appointment_time);
          },
          error: function(error) {
            console.log("ERROR REJECT BTN CLICK",error);
          }
        });
      });


    });
  </script>

  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>
</body>

</html>