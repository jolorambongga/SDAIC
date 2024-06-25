<?php
$title = 'Admin - Services';
$active_services = 'active';
include_once('header.php');
?>

<body>
  <!-- start wrapper -->
  <div class="my-wrapper">
    <!-- start container fluid -->
    <div class="container-fluid">
      <!-- start label -->
      <div class="row">
        <div class="col-4">
          <h1>Edit Services</h1>
        </div>
      </div>
      <!-- end label -->
      <!-- start add button -->
      <div class="row">
        <div class="col-12">
          <button type="button" class="btn btn-mydark mt-3 mb-3 float-end btn-sm" data-bs-toggle="modal"
          data-bs-target="#mod_addServ">Add Service</button>
        </div>
      </div>
      <!-- end add button -->
      <!-- start table -->
      <div class="row">
        <div class="col-md-12">

          <table class="table table-striped text-end">
            <!-- start table head -->
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Service</th>
                <th scope="col">Description</th>
                <th scope="col">Doctor</th>
                <th scope="col">Schedule</th>
                <th scope="col">Duration (mins)</th>
                <th scope="col">Max (/patient)</th>
                <th scope="col">Cost</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <!-- end table head -->
            <!-- start table body -->
            <tbody id="tbodyServices">

            </tbody>
            <!-- end table body -->
          </table>
        </div>
      </div>
      <!-- end table -->
      <!-- add service modal -->
      <form id="frm_addServ">
        <div class="modal fade" id="mod_addServ" tabindex="-1" aria-labelledby="mod_addServLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

              <!-- start modal header -->
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="mod_addServLabel">Add New Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <!-- end modal header -->

              <div class="modal-body">

                <!-- start service name -->
                <label for="service_name" class="form-label">Service Name</label>
                <input type="text" id="service_name" class="form-control" required>
                <pre></pre>
                <!-- end service name -->

                <!-- start service description -->
                <label for="description" class="form-label">Service Description</label>
                <textarea type="text" id="description" class="form-control" required></textarea>
                <pre></pre>
                <!-- end service description -->
                
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="switch_sched" checked>
                  <label class="form-check-label" for="switch_sched">Use doctor sched</label>
                </div>
                <pre></pre>

                <input type="hidden" id="doc_sched" name="doc_sched"/>


                <span id="set_sched">

                </span>

                <!-- service duration -->
                <label for="duration" class="form-label">Service Duration&nbsp;<small>(in minutes)</small></label>
                <input type="number" id="duration" class="form-control" required>
                <pre></pre>
                <!-- end service duration -->

                <!-- service max -->
                <label for="max" class="form-label">Service Max</label>
                <input type="number" id="max" class="form-control" required>
                <pre></pre>
                <!-- end service max -->

                <!-- service cost -->
                <label for="cost" class="form-label">Service Cost</label>
                <div class="input-group mb-3">
                  <span class="input-group-text bg-warning-">₱</span>
                  <input type="number" id="cost" class="form-control" required>
                  <span class="input-group-text bg-warning-">.00</span>
                </div>
                <!-- end service cost -->

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Service</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <!-- end modal -->
      <!-- start service sched modal -->
      <?php
      include_once('modals/service_sched_modal.php');
      ?>
      <!-- end service sched modal -->

      <!-- start edit service modal -->
      <form id="frm_editServ">
        <div class="modal fade" id="mod_editServ" tabindex="-1" aria-labelledby="mod_editServLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <!-- start modal header -->
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="mod_editServLabel">Edit <span id="editServName"></span> Service</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <!-- end modal header -->
              <div class="modal-body">
                <!-- start service name -->
                <label for="e_service_name" class="form-label">Service Name</label>
                <input type="text" id="e_service_name" class="form-control" required>
                <pre></pre>
                <!-- end service name -->

                <!-- start service description -->
                <label for="e_description" class="form-label">Service Description</label>
                <textarea type="text" id="e_description" class="form-control" required></textarea>
                <pre></pre>
                <!-- end service description -->
                
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" role="switch" id="e_switch_sched" checked>
                  <label class="form-check-label" for="e_switch_sched">Use doctor sched</label>
                </div>
                <pre></pre>

                <input type="hidden" id="e_doc_sched" name="e_doc_sched"/>


                <span id="e_set_sched">

                </span>

                <!-- service duration -->
                <label for="e_duration" class="form-label">Service Duration&nbsp;<small>(in minutes)</small></label>
                <input type="number" id="e_duration" class="form-control" required>
                <pre></pre>
                <!-- end service duration -->

                <!-- service max -->
                <label for="e_max" class="form-label">Service Max</label>
                <input type="number" id="e_max" class="form-control" required>
                <pre></pre>
                <!-- end service max -->

                <!-- service cost -->
                <label for="e_cost" class="form-label">Service Cost</label>
                <div class="input-group mb-3">
                  <span class="input-group-text bg-warning-">₱</span>
                  <input type="number" id="e_cost" class="form-control" required>
                  <span class="input-group-text bg-warning-">.00</span>
                </div>
                <!-- end service cost -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <!-- end edit service modal -->
      <!-- start edit service sched modal -->
      <?php
      include_once('modals/e_service_sched_modal.php');
      ?>
      <!-- end edit service sched modal -->

      <!-- start delete service modal -->
      <div class="modal fade" id="mod_delServ" tabindex="-1" aria-labelledby="mod_delServLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="mod_delServLabel">Delete Service</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <label for="del_user_input" class="form-label">Type <b>DELETE</b> to delete the <span id="delServName"></span>'s service.</label>
              <input type="text" id="del_user_input" class="form-control" required>            
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

              <button id="btnDel" type="button" data-service-id="" class="btn btn-danger">Delete Record</button>
            </div>
          </div>
        </div>
      </div>
      <!-- end delete service modal -->
    </div>
    <!-- end container fluid -->
  </div>
  <!-- end wrapper -->

  <!-- start jQuery script -->
  <script>
    $(document).ready(function () {
      console.log('ready');


      $(document).ready(function() { 
        $('#doc_sched').val("true");
        var doctor_sched = `<label class="form-label">Choose Doctor</label>
        <div class="input-group mb-3">
        <label class="input-group-text bg-warning-" for="doctor">Options</label>
        <select class="form-select" id="doctor">
        <option selected>Select Doctor...</option>
        <option value="1">Doctor 1</option>
        <option value="2">Doctor 2</option>
        <option value="3">Doctor 3</option>
        </select>
        </div>`;

        var service_sched = `<button id="callSetSched" type="button" class="btn btn-warning w-100">Set Schedule</button><pre></pre>`;
        $('#set_sched').append(doctor_sched);

        $('#switch_sched').on('change', function() {
          populateDoctorOptions();
          if ($('#switch_sched').is(':checked')) {
            $('#set_sched').empty();
            $('#set_sched').append(doctor_sched);
            $('#doc_sched').val("true");
            var sched_type = $('#doc_sched').val();
          } else {
            $('#set_sched').empty();
            $('#set_sched').append(service_sched);
            $('#doc_sched').val("false");
            var sched_type = $('#doc_sched').val();
          }
          console.log(sched_type);
        });
      });

      // EDIT SWITCH
      $(document).ready(function() {        
        var doctor_sched = `<label class="form-label">Choose Doctor</label>
        <div class="input-group mb-3">
        <label class="input-group-text bg-warning-" for="doctor">Options</label>
        <select class="form-select" id="e_doctor">
        <option selected>Select Doctor...</option>
        <option value="1">Doctor 1</option>
        <option value="2">Doctor 2</option>
        <option value="3">Doctor 3</option>
        </select>
        </div>`;

        var service_sched = `<button id="e_callSetSched" type="button" class="btn btn-warning w-100">Set Schedule</button><pre></pre>`;
        $('#e_set_sched').append(doctor_sched);

        $('#e_switch_sched').on('change', function() {
          populateDoctorOptions();
          if ($('#e_switch_sched').is(':checked')) {
            $('#e_set_sched').empty();
            $('#e_set_sched').append(doctor_sched);
            $('#e_doc_sched').val("true");
            var sched_type = $('#e_doc_sched').val();
          } else {
            $('#e_set_sched').empty();
            $('#e_set_sched ').append(service_sched);
            $('#e_doc_sched').val("false");
            var sched_type = $('#e_doc_sched').val();
            // displaySchedule();
          }
          console.log(sched_type);
        });
      });

      loadServices();

      var scheduleList = [];
      var editScheduleList = [];

      // READ SERVICES
      function loadServices() {
        $.ajax({
          type: 'GET',
          url: 'handles/services/read_services.php',
          dataType: 'JSON',
          success: function(response) {
            console.log("SUCCESS READ:", response);
            $('#tbodyServices').empty();

            if (response.data.length === 0) {

              const noDataHtml = `
              <tr>
                <td colspan="9" class="text-center"><i>No data available</i></td>
              </tr>
              `;
              $('#tbodyServices').append(noDataHtml);
            } else {
              response.data.forEach(function(data) {
                const schedule = data.doctor_id ? data.doctor_sched : data.service_sched;
                const full_name = data.doctor_id ? data.full_name : '<i>No Doctor Assigned</i>';

                const read_service_html = `
                <tr>
                <th scope="row"><small>${data.service_id}</small></th>
                <td><small>${data.service_name}</small></td>
                <td><small>${data.description}</small></td>
                <td><small>${full_name}</small></td>
                <td><small>${schedule}</small></td>
                <td><small>${data.duration}</small></td>
                <td><small>${data.max}</small></td>
                <td><small>${data.cost}</small></td>
                <td data-service-id='${data.service_id}' data-doctor-id='${data.doctor_id}' data-service-name='${data.service_name}'>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end text-center">
                <button id='callEdit' type='button' class='btn btn-mymedium btn-sm' data-bs-toggle='modal' data-bs-target='#mod_editServ'><i class="fas fa-edit"></i></button>
                <button id='callDelete' type='button' class='btn btn-myshadow btn-sm' data-bs-toggle='modal' data-bs-target='#mod_delServ'><i class="fas fa-trash"></i></button>
                </div>
                </td>
                </tr>
                `;
                $('#tbodyServices').append(read_service_html);
              });
            }
          },
          error: function(error) {
            console.log("ERROR READ:", error);
          }
        });
      }

      // READ DOCTORS
      function populateDoctorOptions() {
        $.ajax({
          url: 'handles/services/get_doctor_option.php',
          method: 'GET',
          dataType: 'json',
          success: function(response) {

            console.log("THE RESPONSE:", response);
            //CREATE
            var doctor_select = $('#doctor');
            doctor_select.empty()
            doctor_select.append('<option selected>Select Doctor...</option>');

            //EDIT
            var e_doctor_select = $('#e_doctor');
            e_doctor_select.empty();
            e_doctor_select.append('<option selected>Select Doctor...</option>');

            response.data.forEach(function (doc) {

              const data = `
              <option data-doctor-id="${doc.doctor_id}" value="${doc.doctor_id}">Dr. ${doc.full_name}</option>`

              doctor_select.append(data);
              e_doctor_select.append(data);

            });
          },
          error: function(error) {
            console.log('Error fetching doctor options:', error);
          }
        });
      }

      // POPULATE DOCTORS
      $('#mod_addServ').on('show.bs.modal', function () {
        populateDoctorOptions();
      });

      $('#mod_editServ').on('show.bs.modal', function () {
        populateDoctorOptions();
      });

      $(document).on('click', '#callSetSched', function () {
        new bootstrap.Modal($('#mod_addServSched')).show();
      });

      $('#btnGoBack').click(function () {
        $('#mod_addServSched').modal('hide');
        $('#mod_addServSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });
      });

      // ADD SCHEDULE
      $('#addSched').click(function () {

        var day_of_week = $('#day_of_week').val();
        var start_time = $('#start_time').val();
        var end_time = $('#end_time').val();
        
        if (!day_of_week || !start_time || !end_time) {
          alert('Please complete the schedule details.');
          return;
        }

        const sched_data = 
        `
        <div class="input-group mx-auto w-100 schedule-item">

        <span class="input-group-text text-warning">Selected Day:</span>
        <span class="input-group-text bg-warning-subtle">${day_of_week}</span>

        <span class="input-group-text text-success">Start Time:</span>
        <span class="input-group-text bg-success-subtle">${start_time}</span>

        <span class="input-group-text text-danger">End Time:</span>
        <span class="input-group-text bg-danger-subtle">${end_time}</span>

        <button class="btn btn-danger text-warning remove-sched" type="button" id="removeSched">-</button>

        </div>
        `

        $('#bodySched').append(sched_data);

        $(document).on('click', '#addSched', function() {
          $('#mod_addServSched select').prop('selectedIndex', 0);
        });

        scheduleList.push({
          day_of_week: day_of_week,
          start_time: start_time,
          end_time: end_time
        });

        console.log(scheduleList);
      });


      // REMOVE SCHEDULE
      $('#bodySched').on('click', '.remove-sched', function () {
        var index = $(this).parent().index();
        scheduleList.splice(index, 1);
        $(this).parent().remove();
        console.log(scheduleList);
      });

      // CLEAR SCHEDULE
      $('#btnClear').click(function () {

        $('#mod_addServSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });
        $('#bodySched').empty();
        scheduleList = [];
      });

      // SAVE SCHEDULE
      $('#btnSaveSched').click(function () {

        $('#mod_addServSched').modal('hide');
        $('#mod_addServSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });

        var service_sched = JSON.stringify(scheduleList);
        $('#service_sched').val(service_sched);

        console.log('Saved Schedules:', service_sched);

      });

      // CREATE SERVICE
      $('#frm_addServ').submit(function (e) {

        e.preventDefault();

        var doc_sched = $('#doc_sched').val();

        var service_name = $('#service_name').val();
        var description = $('#description').val();
        var duration = $('#duration').val();
        var max = $('#max').val();
        var cost = $('#cost').val();
        var service_sched = $('#service_sched').val();
        var doctor_id = $('#doctor').find(':selected').data('doctor-id');

        if(doc_sched === "false") {
          if (!service_sched || service_sched === '[]') {
            alert('Please Select a Schedule...');
            return;
          }
        }

        if(doc_sched === "true") {
          if(!doctor_id) {
            alert("Please Select a Doctor...");
            return;
          }
        }

        var service_data = {
          service_name: service_name,
          description: description,
          duration: duration,
          max: max,
          cost: cost,
          doctor_id: doctor_id,
          service_sched: service_sched
        }

        console.log('click submit', service_data);

        $.ajax({

          type: 'POST',
          url: 'handles/services/create_service.php',
          data: service_data,
          success: function (response) {
            console.log('FUNCTION DATA:', service_data);
            console.log(response);
            clearFields();
            loadServices();
            closeModal();
          },
          error: function (error) {
            console.log('ADD SERVICE ERROR:', error);
            console.log('ERROR: SERVICE DATA:', service_data);
            // alert("Please Ensure All Fields are COMPLETE");
          }
        });
      });

      // EDIT SERVICE
      $('#tbodyServices').on('click', '#callEdit', function() {
        var service_id = $(this).closest("td").data('service-id');
        var doctor_id = $(this).closest("td").data('doctor-id');

        $('#e_service_id').val(service_id);

        console.log("SERVICE ID ON EDIT:", service_id);
        console.log("DOCTOR ID ON EDIT:", doctor_id);
        $.ajax({
          type: 'GET',
          url: 'handles/services/get_service.php',
          data: { service_id: service_id, doctor_id: doctor_id },
          dataType: 'JSON',
          success: function(response) {
            console.log(response);
            $('#e_bodySched').empty();
            editScheduleList = [];

            response.data.forEach(function(data) {
                // Determine which schedule list to use based on doctor_id presence
              const schedule_list = data.doctor_id ? data.doctor_sched : data.service_sched;

                // Iterate through each schedule in the schedule list
              schedule_list.forEach(function(schedule) {
                const sched_data = `
                <div class="input-group mx-auto w-100 schedule-item">
                <span class="input-group-text text-warning">Selected Day:</span>
                <span class="input-group-text bg-warning-subtle">${schedule.day_of_week}</span>

                <span class="input-group-text text-success">Start Time:</span>
                <span class="input-group-text bg-success-subtle">${schedule.start_time}</span>

                <span class="input-group-text text-danger">End Time:</span>
                <span class="input-group-text bg-danger-subtle">${schedule.end_time}</span>

                <button class="btn btn-danger text-warning remove-sched" type="button" id="removeSched">-</button>
                </div>
                `;
                $('#e_bodySched').append(sched_data);

                editScheduleList.push({
                  day_of_week: schedule.day_of_week,
                  start_time: schedule.start_time,
                  end_time: schedule.end_time
                });
              });
            });

            // Further processing after appending schedules
            $('#e_service_name').val(response.data[0].service_name);
            $('#e_description').val(response.data[0].description);
            $('#e_duration').val(response.data[0].duration);
            $('#e_max').val(response.data[0].max);
            $('#e_cost').val(response.data[0].cost);

            // Handle UI changes based on doctor_id presence
            if (doctor_id) {
              $('#e_switch_sched').prop('checked', true).change();
            } else {
              $('#e_switch_sched').prop('checked', false).change();
            }

            $('#e_doctor').val(doctor_id);

            $.ajax({
              url: 'handles/services/get_doctor_option.php',
              method: 'GET',
              dataType: 'json',
              success: function(response) {
                console.log("THE RESPONSE:", response);

                var e_doctor_select = $('#e_doctor');
                e_doctor_select.empty();

                response.data.forEach(function(doc) {
                  const option = `
                  <option data-doctor-id="${doc.doctor_id}" value="${doc.doctor_id}">
                  Dr. ${doc.full_name}
                  </option>`;
                  e_doctor_select.append(option);
                });

                e_doctor_select.val(doctor_id);

                console.log("SELECTED DOCTOR ID", doctor_id);
              },
              error: function(error) {
                console.log('Error fetching doctor options:', error);
              }
            });
          },
          error: function(xhr, status, error) {
            console.error("Error fetching service data:", error);
          }
        });
      });


      $(document).on('click', '#e_callSetSched', function () {
        new bootstrap.Modal($('#mod_editServSched')).show();
      });

      // SAVE EDITED SCHEDULE
      $('#e_addSched').click(function () {
        var day_of_week = $('#e_day_of_week').val();
        var start_time = $('#e_start_time').val();
        var end_time = $('#e_end_time').val();

        if (!day_of_week || !start_time || !end_time) {
          alert('Please complete the schedule details.');
          return;
        }

        const sched_data = `
        <div class="input-group mx-auto w-100 schedule-item">

        <span class="input-group-text text-warning">Selected Day:</span>
        <span class="input-group-text bg-warning-subtle">${day_of_week}</span>

        <span class="input-group-text text-success">Start Time:</span>
        <span class="input-group-text bg-success-subtle">${start_time}</span>

        <span class="input-group-text text-danger">End Time:</span>
        <span class="input-group-text bg-danger-subtle">${end_time}</span>

        <button class="btn btn-danger text-warning remove-sched" type="button" id="removeSched">-</button>

        </div>
        `

        $('#e_bodySched').append(sched_data);

        // RESECT SCHEDULE OPTION
        $('#mod_editServSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });

        editScheduleList.push({
          day_of_week: day_of_week,
          start_time: start_time,
          end_time: end_time
        });

        console.log(editScheduleList);
      });

      // REMOVE EDITED SCHEDULE
      $('#e_bodySched').on('click', '.remove-sched', function () {
        var index = $(this).parent().index();
        editScheduleList.splice(index, 1);
        $(this).parent().remove();
        console.log(editScheduleList);
      });

      // CLEAR EDITED SCHEDULE
      $('#e_btnClear').click(function () {
        $('#mod_editServSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });
        $('#e_bodySched').empty();
        editScheduleList = [];
      });

      // SAVE EDITED SCHEDULE
      $('#e_btnSaveSched').click(function () {
        $('#mod_editServSched').modal('hide');
        // RESECT SCHEDULE OPTION
        $('#mod_editServSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });

        var service_sched = JSON.stringify(editScheduleList);
        $('#e_service_sched').val(service_sched);

        console.log('Saved AHSDUIGASDG SCHEDULES:', service_sched);
      });

      // UPDATE SERVICE
      $('#frm_editServ').submit(function (e) {

        e.preventDefault();

        var doc_sched = $('#e_doc_sched').val();

        var service_sched = JSON.stringify(editScheduleList);
        $('#e_service_sched').val(service_sched);


        var service_id = $('#e_service_id').val();
        var service_name = $('#e_service_name').val();
        var description = $('#e_description').val();
        var duration = $('#e_duration').val();
        var max = $('#e_max').val();
        var cost = $('#e_cost').val();
        var service_sched = $('#e_service_sched').val();
        var doctor_id = $('#e_doctor').find(':selected').data('doctor-id');

        if(doc_sched === "false") {
          if (!service_sched || service_sched === '[]') {
            console.log("reasch if??");
            alert('Please Select a Schedule...');
            return;
          }
        }

        if(doc_sched === "true") {
          if(!doctor_id) {
            alert("Please Select a Doctor...");
            return;
          }
        }

        var service_data = {
          service_id: service_id,
          service_name: service_name,
          description: description,
          duration: duration,
          max: max,
          cost: cost,
          doctor_id: doctor_id,
          service_sched: service_sched
        }

        console.log('click submit', service_data);

        $.ajax({

          type: 'POST',
          url: 'handles/services/update_service.php',
          data: service_data,
          success: function (response) {
            console.log('FUNCTION UPDATE SERVICE DATA:', service_data);
            console.log(response);
            loadServices();
            closeModal();
          },
          error: function (error) {
            console.log('UPDATE SERVICE ERROR:', error);
            console.log('UPDATE ERROR: SERVICE DATA:', service_data);
          }
        });
      });

      // DELETE SERVICE
      $(document).on('click', '#callDelete', function() {
        var service_id = $(this).closest("td").data('service-id');
        var service_name = $(this).closest("td").data('service-name');

        console.log("service id:", service_id, "service name:", service_name);
        $('#delServName').text(service_name);

        $('#btnDel').data('service-id', service_id);
      });

      $(document).on('click', '#btnDel', function() {
        var service_id = $(this).data('service-id');
        var user_input = $('#del_user_input').val();

        if (user_input !== 'DELETE') {
          alert('Please type DELETE to confirm.');
          return;
        }

        $.ajax({
          type: 'POST',
          url: 'handles/services/delete_service.php',
          data: {service_id: service_id, user_input: user_input},
          dataType: 'JSON',
          success: function(response) {
            console.log("DELETE SERVICE RESPONSE:", response);
            loadServices();
            closeModal();
          },
          error: function(error) {
            console.log("DELETE SERVICE ERROR:", error);
          }
        });
      });

      // CLOSE MODAL FUNCTION
      function closeModal() {
        $('#mod_addServ .btn-close').click();
        $('#mod_editServ .btn-close').click();
        $('#mod_delServ .btn-close').click();
        clearFields();
      } // END CLOSE MODAL FUNCTION

      // CLEAR FIELDS FUNCTION
      function clearFields() {
        $('#service_name').val('');
        $('#description').val('');
        $('#duration').val('');
        $('#max').val('');
        $('#cost').val('');

        $('#del_user_input').val('');

        $('#doctor').prop('selectedIndex', 0);
        $('#bodySched').empty();
        scheduleList = [];
        editScheduleList = [];
      } // END CLEAR FIELDS FUNCTION

      // ON CLOSE MODAL
      $('#mod_addServSched').on('hidden.bs.modal', function () {
        $('#mod_addServSched select').each(function() {
          $(this).prop('selectedIndex', 0);
        });
        scheduleList = [];
      });

      $('#mod_addServ').on('hidden.bs.modal', function () {
        clearFields();
      });

      $('#mod_editServ').on('hidden.bs.modal', function () {
        clearFields();
      });

      $('#mod_delServ').on('hidden.bs.modal', function () {
        clearFields();
      }); // END ON CLOSE MODAL

    }); // END READY
  </script>
  <!-- end jQuery script -->

  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>
</body>

</html>