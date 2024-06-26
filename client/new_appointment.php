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

<!-- Datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<!-- <script src="/bundles/bootstrap-datepicker?v=csAQJHewk-MF7ULDm6WPf6LzXwjK6YdTmHd8vsLCykI1"></script> -->

<div class="my-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h1 class="text-start">Make your new appointment</h1>
            </div>
        </div>
        <!-- Start multi-step form -->
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
                                    <option value="" disabled selected>Select a procedure</option>
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
                                <label for="appointment_date">Select Appointment Date:</label>
                                <input type="text" id="appointment_date" name="appointment_date" class="form-control">

                                <select id="appointment_time" class="form-select mt-3" aria-label="Default select example">
                                    <option value="" disabled selected>Select Time</option>
                                    <!-- Times will be populated dynamically -->
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
        <!-- End multi-step form -->

        <!-- Spinner for loading -->
        <div id="load_spinner" class="d-flex justify-content-center" style="display: none;">
            <!-- Your spinner HTML or loading message here -->
        </div>

        <!-- Schedule information -->
        <input type="hidden" id="schedule" name="schedule"/>
        <div id="schedule_body"></div>
    </div>
</div>

<!-- jQuery and Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script>
    $(document).ready(function () {

function formatDate(dateString) {
    var weekdays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    var date = new Date(dateString);
    var month = ('0' + (date.getMonth() + 1)).slice(-2); // Adding 1 because getMonth() returns 0-based index
    var day = ('0' + date.getDate()).slice(-2);
    var year = date.getFullYear();
    var dayOfWeek = weekdays[date.getDay()]; // Get day of the week name

    return `${month}-${day}-${year} (${dayOfWeek})`;
}




        function getDayOfWeek(selectedDate) {
            var dateObj = new Date(selectedDate);
            var dayOfWeek = dateObj.toLocaleDateString('en-US', { weekday: 'long' });
            return dayOfWeek;
        }

    function populateTimeOptions(start_time, end_time, duration) {
    $('#appointment_time').empty(); // Clear existing options

    // Convert start_time and end_time to Date objects for comparison
    var startTime = new Date('1970-01-01T' + start_time);
    var endTime = new Date('1970-01-01T' + end_time);

    // Calculate intervals based on duration
    var interval = duration * 60 * 1000; // Convert duration to milliseconds
    var currentTime = new Date(startTime); // Start from startTime

    // Format options in 12-hour format with AM/PM
    var options = [];
    while (currentTime <= endTime) {
        var hours = currentTime.getHours();
        var minutes = ('0' + currentTime.getMinutes()).slice(-2);
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // Handle midnight (0 hours)
        var optionTime = hours + ':' + minutes + ' ' + ampm;
        
        options.push({
            value: currentTime.toTimeString().slice(0, 5),
            text: optionTime
        });

        // Move to next interval
        currentTime.setTime(currentTime.getTime() + interval);
    }

    // Populate select options
    options.forEach(function(option) {
        $('#appointment_time').append($('<option>', {
            value: option.value,
            text: option.text
        }));
    });
}


// Event handler for updating appointment time options based on date selection
$(document).on('change', '#appointment_date', function() {
    var selectedDate = $(this).val();
    var service_id = $('#procedure-select').val();

    var day_of_week = getDayOfWeek(selectedDate);

    // Fetch relevant time details (start_time, end_time, duration) for selected service_id and date
    $.ajax({
        type: 'GET',
        url: 'handles/fetch_time.php',
        dataType: 'json',
        data: {
            service_id: service_id,
            day_of_week: day_of_week
        },
        success: function(response) {
            console.log(response);
            if (response.status === 'success') {
                var start_time = response.start_time;
                var end_time = response.end_time;
                var duration = response.duration;

                // Populate time options based on fetched details
                populateTimeOptions(start_time, end_time, duration);

                // Fetch existing appointments for the selected date and disable corresponding options
                $.ajax({
                    type: 'GET',
                    url: 'handles/check_appointments.php', // PHP script to fetch existing appointments
                    dataType: 'json',
                    data: {
                        service_id: service_id,
                        appointment_date: selectedDate
                    },
                    success: function(existingAppointments) {
                        console.log("Existing Appointments:", existingAppointments);

                        // Disable options in #appointment_time based on existing appointments
                        $('#appointment_time option').each(function() {
                            var optionValue = $(this).val();
                            if (existingAppointments.indexOf(optionValue) !== -1) {
                                $(this).prop('disabled', true);
                            } else {
                                $(this).prop('disabled', false);
                            }
                        });
                    },
                    error: function(error) {
                        console.log("Error fetching existing appointments:", error);
                    }
                });

            } else {
                alert(response.message); // Handle error or display message
            }
        },
        error: function(error) {
            console.log("Error fetching time details:", error);
        }
    });
});

    var schedule = []; // Define the schedule array globally

    // Function to fetch and save schedule data
    function saveSchedule(service_id, doctor_id) {
        $.ajax({
            type: 'GET',
            url: 'handles/fetch_schedule.php',
            dataType: 'json',
            data: { service_id: service_id, doctor_id: doctor_id },
            success: function(response) {
                console.log(response);
                var body = $('#schedule_body');
                body.empty();
                schedule = []; // Clear schedule array

                response.schedule_data.forEach(function(data) {
                    var scheduleItem = {
                        day_of_week: data.day_of_week,
                        start_time: data.start_time,
                        end_time: data.end_time
                    };
                    schedule.push(scheduleItem); // Push schedule item to array

                    const schedule_html = `
                    <div>
                    <p>DAY OF WEEK: ${data.day_of_week}</p>
                    <p>START TIME: ${data.start_time}</p>
                    <p>END TIME: ${data.end_time}</p>
                    </div>
                    <hr>
                    `;
                    body.append(schedule_html); // Append schedule HTML to body
                });

                console.log("Schedule List:", schedule);
                schedule = schedule.filter(Boolean);
                updateDatePickerAvailability(); // Call function to update datepicker
            },
            error: function(error) {
                console.log("Error fetching schedule:", error);
            }
        });
    }

    // Function to update datepicker availability based on schedule
    function updateDatePickerAvailability() {
        $("#appointment_date").datepicker("destroy"); // Destroy existing datepicker instance

        var dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        console.log("Schedule Data:", schedule); // Check schedule data

        $("#appointment_date").datepicker("widget").on("show", function () {
            var $calendar = $('.datepicker');
            var $days = $calendar.find('td.day');

            $days.each(function () {
                var $day = $(this);
                var dateString = new Date($day.data('date')).toDateString();
                var dayOfWeek = dayNames[new Date(dateString).getDay()];

                if (schedule.some(function (item) {
                    return item.day_of_week === dayOfWeek;
                })) {
                    $day.removeClass('disabled');
                } else {
                    $day.addClass('disabled');
                }
            });
        });
    }




    // Event handler for procedure selection change
    $(document).on('change', '#procedure-select', function() {
        var service_id = $(this).val();
        var doctor_id = $(this).find(':selected').data('doctor-id');

        console.log("Procedure selected:", service_id, doctor_id);
        saveSchedule(service_id, doctor_id); // Fetch and save schedule for selected procedure
    });

    // Initial loading of procedures
    loadProcedures();

    function loadProcedures() {
        $.ajax({
            type: 'GET',
            url: 'handles/read_services.php',
            dataType: 'JSON',
            success: function(response) {
                console.log(response);
                $('#procedure-select').empty();
                $('#procedure-select').append('<option value="" disabled selected>Select a procedure</option>');

                $.each(response.data, function(key, value) {
                    const option = `<option value="${value.service_id}" data-service-name="${value.service_name}" data-service-duration="${value.duration}" data-service-max="${value.max}" data-doctor-id="${value.doctor_id}">${value.service_name}</option>`;
                    $('#procedure-select').append(option);
                });
            },
            error: function(error) {
                console.log("Error loading procedures:", error);
            }
        });
    }

    // Event handler for next button clicks (form navigation)
    $('.next-btn').click(function() {
        var $currentStep = $(this).closest('.form-step');
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
            if ($('#appointment_date').val() === '' || $('#appointment_time').val() === null) {
                alert('Please select a date and time before proceeding.');
                return;
            }
            var appointmentTime = new Date($('#appointment_date').val() + 'T' + $('#appointment_time').val());
            var now = new Date();
            now.setHours(now.getHours() + 1);

            if (appointmentTime < now) {
                alert('Appointment time must be at least 1 hour from now.');
                return;
            }
        }
        $currentStep.hide().next('.form-step').show();

        // Update review box content on step 3
        if ($currentStep.attr('id') === 'step-3') {
            populateReviewBox();
        }
    });

    // Event handler for previous button clicks (form navigation)
    $('.prev-btn').click(function() {
        $(this).closest('.form-step').hide().prev('.form-step').show();
    });

    // Function to populate review box content
    function populateReviewBox() {
        var service_name = $('#procedure-select option:selected').data('service-name');
        var request_image = $('#request_image')[0].files[0];
        var appointment_date = formatDate($('#appointment_date').val());
        var appointment_time = $('#appointment_time option:selected').text();

        $('#review-box').html(`
            <p><strong>Procedure:</strong> ${service_name}</p>
            <p><strong>Image:</strong> ${request_image ? request_image.name : 'No image uploaded'}</p>
            <p><strong>Appointment Date:</strong> ${appointment_date}</p>
            <p><strong>Appointment Time:</strong> ${appointment_time}</p>
            `);
    }

    // Event handler for form submission
    $('#appointment-form').submit(function(e) {
        e.preventDefault();
        var service_id = $('#procedure-select').val();
        var service_name = $('#procedure-select option:selected').data('service-name');
        var request_image = $('#request_image')[0].files[0];
        var appointment_date = $('#appointment_date').val();
        var appointment_time = $('#appointment_time').val();
        var formData = new FormData();
        formData.append('user_id', <?php echo($_SESSION['user_id']); ?>);
        formData.append('full_name', "<?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name']; ?>");
        formData.append('email', "<?php echo($_SESSION['email']); ?>");
        formData.append('service_id', service_id);
        formData.append('service_name', service_name);
        formData.append('appointment_date', appointment_date);
        formData.append('appointment_time', appointment_time);
        formData.append('request_image', request_image);        
        $.ajax({
            type: 'POST',
            url: 'handles/submit_appointment.php',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            beforeSend: function() {
                console.log(formData);
                $('#load_spinner').show();
            },
            success: function(response) {
                $('#load_spinner').hide();
                alert('Appointment submitted successfully!');
                console.log(response);
                // window.location.href = "your_appointments.php";
            },
            error: function(error) {
                $('#load_spinner').hide();
                console.log("Error submitting appointment:", error);
            }
        });
    });

    // Initialize datepicker after loading procedures
    $(document).ajaxComplete(function() {
        $("#appointment_date").datepicker({
            dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            minDate: 0,
            changeMonth: true,
            changeYear: false
        });
    });

    // Event handler for updating appointment time options based on date selection
    $(document).on('change', '#appointment_date', function() {
        var selectedDate = $(this).val();
        // Implement logic to fetch and populate appointment times dynamically
        // Example: AJAX call to fetch available times and populate #appointment_time select options
    });

});
</script>

<?php
include_once('footer_script.php');
?>
