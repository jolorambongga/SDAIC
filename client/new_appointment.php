<?php
$title = "SDAIC - New Appointment";
$active_index = "";
$active_profile = "";
$active_your_appointments = "";
$active_new_appointment = "active";
include_once('header.php');
include_once('handles/auth.php');
checkAuth();
?>

<div class="my-wrapper">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <h1 class="text-start">Make your new appointment</h1>
      </div>
    </div>
    <!-- start multi-step form -->
    <div class="row justify-content-center bg- p-jp-md-5">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="wrapper mt-5">
          <form id="appointment-form">
            <!-- Step 1: Select Procedure -->
            <div id="step-1" class="form-step">
              <div class="title">Your Procedure</div>
              <div class="box mb-3">
                <select id="procedure-select" class="form-control">
                  <!-- Options will be loaded here by jQuery -->
                </select>
              </div>
              <button type="button" class="btn btn-warning next-btn float-end mt-3">Next</button>
            </div>
            
            <!-- Step 2: Upload Image -->
            <div id="step-2" class="form-step" style="display:none;">
              <div class="title">Upload Photo of Your Request</div>
              <div class="box mb-3">
                <input accept="image/jpeg, image/png, image/gif" type="file" name="request_image" id="request_image" class="form-control">              
              </div>
              <button type="button" class="btn btn-warning next-btn float-end mt-3">Next</button>
              <button type="button" class="btn btn-danger prev-btn float-end mt-3 me-2">Previous</button>
            </div>
            
            <!-- Step 3: Select Date and Time -->
            <div id="step-3" class="form-step" style="display:none;">
              <div class="title">Select Date and Time</div>
              <div class="box mb-3">
                <input type="" name="appointment_date" id="appointment_date" class="form-control">
                <select id="appointment_time" class="form-control mt-3" disabled>
                  <!-- Time options will be loaded here by jQuery -->
                </select>
              </div>
              <button type="button" class="btn btn-warning next-btn float-end mt-3">Next</button>
              <button type="button" class="btn btn-danger prev-btn float-end mt-3 me-2">Previous</button>
            </div>
            
            <!-- Step 4: Review and Submit -->
            <div id="step-4" class="form-step" style="display:none;">
              <div class="title">Review and Submit</div>
              <div id="review-box" class="box mb-3">
                <!-- Review content will be populated here -->
              </div>
              <button type="submit" class="btn btn-success float-end mt-3">Submit</button>
              <button type="button" class="btn btn-danger prev-btn float-end mt-3 me-2">Previous</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- end multi-step form -->
    <div id="load_spinner" class="d-flex justify-content-center" style="display: none;">
      <!-- Loading spinner -->
    </div>
  </div>
</div>

<script type="text/javascript">
$(document).ready(function () {
    // Load procedures on page load
    loadProcedures();

    // Function to load procedures via AJAX
    function loadProcedures() {
        $.ajax({
            type: 'GET',
            url: 'handles/read_services.php',
            dataType: 'JSON',
            success: function(response) {
                $('#procedure-select').empty();
                $('#procedure-select').append('<option value="" disabled selected>Select a procedure</option>');
                $.each(response.data, function(key, value){
                    const option = `<option value="${value.service_id}" 
                                     data-duration="${value.duration}" 
                                     data-schedule='${JSON.stringify({
                                        day_of_week: value.day_of_week,
                                        start_time: value.start_time,
                                        end_time: value.end_time
                                     })}'>
                                     ${value.service_name}
                                   </option>`;
                    $('#procedure-select').append(option);
                });
            },
            error: function(error) {
                console.log("ERROR LOADING PROCEDURES:", error);
            }
        });
    }

    // Event handler for procedure selection
    $('#procedure-select').change(function() {
        const selectedOption = $('#procedure-select option:selected');
        const duration = selectedOption.data('duration');
        const schedule = JSON.parse(selectedOption.data('schedule'));

        // Configure datepicker
        $('#appointment_date').attr('min', new Date().toISOString().split('T')[0]);
        $('#appointment_date').datepicker({
            daysOfWeekDisabled: schedule.day_of_week.map(day => (day === 'Sunday' ? 0 : day)),
            format: 'yyyy-mm-dd',
            startDate: '+1d',
        });

        // Event handler for date selection
        $('#appointment_date').change(function() {
            const selectedDate = $(this).val();
            loadAvailableTimes(selectedOption.val(), selectedDate, duration, schedule);
        });
    });

    // Function to load available times based on service and date
    function loadAvailableTimes(service_id, date, duration, schedule) {
        $.ajax({
            type: 'GET',
            url: `handles/check_appointment_availability.php?service_id=${service_id}&date=${date}`,
            dataType: 'JSON',
            success: function(response) {
                $('#appointment_time').empty().prop('disabled', false);

                const takenTimes = response.data;
                const { start_time, end_time } = schedule;
                const startTime = parseInt(start_time.substring(0, 2), 10); // Extract hour from HH:MM format
                const endTime = parseInt(end_time.substring(0, 2), 10);

                // Generate available time slots
                for (let hour = startTime; hour < endTime; hour++) {
                    for (let minute = 0; minute < 60; minute += duration) {
                        let time = `${hour.toString().padStart(2, '0')}:${minute.toString().padStart(2, '0')}`;
                        if (!takenTimes.includes(time)) {
                            $('#appointment_time').append(`<option value="${time}">${time}</option>`);
                        }
                    }
                }
            },
            error: function(error) {
                console.log("ERROR CHECKING AVAILABILITY:", error);
            }
        });
    }

    // Event handler for next button
    $('.next-btn').click(function(){
        var $currentStep = $(this).closest('.form-step');

        // Basic validation per step
        if ($currentStep.attr('id') === 'step-1') {
            if ($('#procedure-select').val() === null) {
                alert('Please select a procedure before proceeding.');
                return;
            }
        } else if ($currentStep.attr('id') === 'step-2') {
            if ($('#request_image')[0].files.length === 0) {
                alert('Please upload an image before proceeding.');
                return;
            }
        } else if ($currentStep.attr('id') === 'step-3') {
            if ($('#appointment_date').val() === '' || $('#appointment_time').val() === '') {
                alert('Please select a date and time before proceeding.');
                return;
            }
        }

        // Show next step
        $currentStep.hide().next('.form-step').show();

        // Populate review box on step 3
        if ($currentStep.attr('id') === 'step-3') {
            populateReviewBox();
        }
    });

    // Event handler for previous button
    $('.prev-btn').click(function(){
        $(this).closest('.form-step').hide().prev('.form-step').show();
    });

    // Function to populate review box
    function populateReviewBox() {
        var service_name = $('#procedure-select option:selected').text();
        var request_image = $('#request_image')[0].files[0];
        var appointment_date = $('#appointment_date').val();
        var appointment_time = $('#appointment_time').val();

        $('#review-box').html(`
            <p><strong>Procedure:</strong> ${service_name}</p>
            <p><strong>Image:</strong> ${request_image ? request_image.name : 'No image uploaded'}</p>
            <p><strong>Appointment Date:</strong> ${appointment_date}</p>
            <p><strong>Appointment Time:</strong> ${appointment_time}</p>
        `);
    }
});


</script>

<?php
include_once('footer_script.php');
?>
