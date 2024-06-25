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
          loadFilteredAppointments(service_id);
          console.log(service_id);
        });
      },
      error: function(error) {
        console.log("ERROR IN LOAD FILTERS FUNCTION", error);
      }
    });
  }

  function loadInitialAppointments() {
    loadFilteredAppointments('All');
  }
  loadInitialAppointments();

  function loadFilteredAppointments(service_id) {
    $.ajax({
      type: 'GET',
      url: 'handles/appointments/read_appointments.php',
      dataType: 'JSON',
      data: { service_id: service_id },
      success: function(response) {
        console.log(response.data.service_id);
        console.log('filteredd',response);
        $('#tbodyAppointments').empty();
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
          <td data-appointment-id='${data.appointment_id}' data-full-name="${data.first_name} ${data.last_name}" data-appointment-name="${data.service_name}">
          <div class="d-grid gap-2 d-md-flex justify-content-md-end text-center">
          <button id='callReject' data-bs-toggle="modal" data-bs-target="#mod_Reject" type='button' class='btn btn-danger btn-sm'>
          <i class="fas fa-thumbs-down"></i>
          </button>
          <button id='callApprove' data-bs-toggle="modal" data-bs-target="#mod_Approve" type='button' class='btn btn-success btn-sm'>
          <i class="fas fa-thumbs-up"></i>
          </button>
          </div>
          </td>
          </tr>
          `;
          $('#tbodyAppointments').append(read_appointments_html);
        });
      },
      error: function(error) {
        console.log("ERROR", error);
      }
    });
  }



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
    var user_input = $('#reject_user_input').val('');

    var data = {
      appointment_id: appointment_id,
      status: status,
      user_input: user_input
    }

    console.log(appointment_id, user_input);

    if (user_input !== 'REJECT') {
      alert('Please type REJECT to reject.');
      console.log(appointment_id);
      return;
    }
    $.ajax({
      type: 'POST',
      url: 'handles/appointments/approve_reject_appointment.php',
      data: data,
      dataType: 'JSON',
      success: function(response) {
        console.log("SUCESS REJECT BTN CLICK",response);
        loadFilteredAppointments();
        $('#mod_Reject').modal('hide');
        $('#reject_user_input').val();
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