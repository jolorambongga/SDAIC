<div class="modal fade" id="mod_Input" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod_InputLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mod_InputLabel">Generate Receipt for <span id="patient_name"></span> : <span id="service_name"></span> (<span id="service_price"></span>)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- <div class="mb-3">
          <label for="inputPrice" class="form-label">Price:</label>
          <input type="number" class="form-control" id="inputPrice" required>
        </div> -->
        <div class="mb-3">
          <label for="inputCash" class="form-label">Cash Amount:</label>
          <input type="number" class="form-control" id="inputCash" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="calculateChange">Calculate Change</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mod_Receipt" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod_ReceiptLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mod_ReceiptLabel">Receipt Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="receiptDetails">
          <p class="  patient-name"><strong>Patient Name:</strong> <span id="receiptPatientName"></span></p>
          <p><strong>Procedure Type:</strong> <span id="receiptProcedureType"></span></p>
          <p><strong>Appointment Date:</strong> <span id="receiptAppointmentDate"></span></p>
          <p><strong>Appointment Time:</strong> <span id="receiptAppointmentTime"></span></p>
          <hr>
          <p class="text-end right-align price"><strong>Price:</strong> <span id="receiptPrice"></span></p>
          <p class="text-end right-align cash"><strong>Cash:</strong> <span id="receiptCash"></span></p>
          <p class="text-end right-align change"><strong>Change:</strong> <span id="receiptChange"></span></p>
          <small><p class="text-center date-generated"><strong>Date Generated:</strong> <span id="receiptDateGenerated"></span></p></small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="printReceipt">Print</button>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="r_patient_name">
<input type="hidden" id="r_procedure">
<input type="hidden" id="r_appointment_date">
<input type="hidden" id="r_appointment_time">
<input type="hidden" id="r_price">

<input type="hidden" id="r_appointment_id">
<input type="hidden" id="r_service_id">
<input type="hidden" id="r_user_id">

<input type="hidden" id="r_first_name">
<input type="hidden" id="r_last_name">

<input type="hidden" id="r_cash">
<input type="hidden" id="r_change">


<input type="hidden" id="r_transaction_number">

<script>
  $(document).ready(function() {

    // FUNCTIONS FOR TRANSAC NUM>
    function generateRandomString(length) {
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      let result = '';
      const charactersLength = characters.length;
      for (let i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
      }
      return result;
    }

    function getFirstTwoLetters(str) {
      if (str && str.length >= 2) {
        return str.substring(0, 2);
      }
      return '';
    }


    $(document).on('click', '#callReceipt', function() {

      const now = new Date();
      const month = String(now.getMonth() + 1).padStart(2, '0');
      const day = String(now.getDate()).padStart(2, '0');
      const year = now.getFullYear();
      const randomString = generateRandomString(2);
      const firstTwoLetters = getFirstTwoLetters(randomString);

      $('#r_patient_name').val($(this).closest('td').data('full-name'));
      $('#r_procedure').val($(this).closest('td').data('appointment-name'));
      $('#r_appointment_date').val($(this).closest('td').data('appointment-date'));
      $('#r_appointment_time').val($(this).closest('td').data('appointment-time'));
      $('#r_price').val($(this).closest('td').data('price'));
      
      $('#r_appointment_id').val($(this).closest('td').data('appointment-id'));
      $('#r_service_id').val($(this).closest('td').data('service-id'));
      $('#r_user_id').val($(this).closest('td').data('user-id'));

      $('#r_first_name').val($(this).closest('td').data('first-name'));
      $('#r_last_name').val($(this).closest('td').data('last-name'));

      var patient_name = $('#r_patient_name').val();
      var service_name = $('#r_procedure').val();
      var service_price = $('#r_price').val();
      var first_name = $('#r_first_name').val();
      var last_name = $('#r_last_name').val();

      var procedure = getFirstTwoLetters(service_name);
      var fname = getFirstTwoLetters(first_name).toUpperCase();
      var lname = getFirstTwoLetters(last_name).toUpperCase();

      var random_string = generateRandomString(5);

      var appointment_id = $('#r_appointment_id').val();
      var service_id = $('#r_service_id').val();
      var user_id = $('#r_user_id').val();

      const transaction_number = `SDAIC-${appointment_id}${random_string}-00${procedure}00${service_id}${fname}${lname}-00${user_id}`;

      $('#r_transaction_number').val(transaction_number);


      $('#patient_name').text(patient_name);
      $('#service_name').text(service_name);
      $('#service_price').text(service_price);
    });

    $(document).on('hidden.bs.modal', '#mod_Input', function () {
      $('#inputCash').val('');
    });

    // Function to format a number with leading zeros (e.g., 1 -> 01, 12 -> 12)
    function formatWithLeadingZero(number) {
      return number < 10 ? '0' + number : number;
    }

    // Function to populate receipt modal with data
    function populateReceiptModal(patientName, procedureType, appointmentDate, appointmentTime) {
      $('#receiptPatientName').text(patientName);
      $('#receiptProcedureType').text(procedureType);
      $('#receiptAppointmentDate').text(appointmentDate);
      $('#receiptAppointmentTime').text(appointmentTime);

      var currentDate = new Date();
      var day = currentDate.getDate();
      var month = currentDate.getMonth() + 1;
      var year = currentDate.getFullYear();

      var formattedDay = day < 10 ? '0' + day : day;
      var formattedMonth = month < 10 ? '0' + month : month;

      var formattedDate = formattedMonth + '-' + formattedDay + '-' + year;

      console.log(formattedDate); // Output: "06-03-2034"
      var hours = formatWithLeadingZero(currentDate.getHours());
      var minutes = formatWithLeadingZero(currentDate.getMinutes());
      var seconds = formatWithLeadingZero(currentDate.getSeconds());
      var formattedTime = hours + ':' + minutes + ':' + seconds;

      $('#receiptDateGenerated').text(formattedDate + ' | ' + formattedTime);
    }

    // Event listener for clicking "Calculate Change" button
    $('#calculateChange').click(function() {
      var price = parseFloat($('#r_price').val());
      var cash = parseFloat($('#inputCash').val());

      if (isNaN(price) || isNaN(cash) || price < 0 || cash < 0) {
        alert('Please enter valid numbers for price and cash.');
        return;
      }

      var change = cash - price;
      if (change < 0) {
        alert('Cash is less than price. Please enter a valid amount.');
        return;
      }

      var patientName = $('#r_patient_name').val();
      var procedureType = $('#r_procedure').val();
      var appointmentDate = $('#r_appointment_date').val();
      var appointmentTime = $('#r_appointment_time').val();

      $('#r_cash').val(cash);
      $('#r_change').val(change);

      populateReceiptModal(patientName, procedureType, appointmentDate, appointmentTime);

      $('#mod_Input').modal('hide');
      $('#receiptPrice').text(price.toFixed(2));
      $('#receiptCash').text(cash.toFixed(2));
      $('#receiptChange').text(change.toFixed(2));
      $('#mod_Receipt').modal('show');
    });



    $('#printReceipt').click(function() {
      var printWindow = window.open('', '_blank');

      var patient_name = $('#r_patient_name').val();
      var procedure = $('#r_procedure').val();
      var date = $('#r_appointment_date').val();
      var time = $('#r_appointment_time').val();
      // var price = $('#r_price').val();
      var price = $('#r_price').val();
      var cash = parseFloat($('#r_cash').val()).toFixed(2);

      var change = parseFloat($('#r_change').val()).toFixed(2);

      var transaction_number = $('#r_transaction_number').val();

    function getCurrentDateTime() {
        const now = new Date();

        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0'); // January is 0, so we add 1
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        return `${year}-${month}-${day} | ${hours}:${minutes}:${seconds}`;
    }

    var date_time = getCurrentDateTime();

      var html = `

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      font-family: Arial, sans-serif;
    }
    .container {
      border: 1px solid #000;
      padding: 20px;
      width: 300px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      text-align: left;
      word-wrap: break-word; /* Added for word wrapping */
    }
    .header {
      text-align: center;
      margin-bottom: 20px;
    }
    .clinic-title {
      font-weight: bold;
      margin-bottom: 5px;
    }
    .clinic-address {
      font-size: 0.9em;
      margin-bottom: 10px;
    }
    .logo {
      width: 100px;
      height: 100px;
      background-image: url('https://i.ibb.co/Rc17B0p/SDAIC.png');
      background-size: contain; /* Ensures the background image fits within the dimensions without stretching */
      background-position: center; /* Centers the background image within the .logo element */
      margin: 0 auto 10px auto;
    }
    .row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }
    .separator {
      border-top: 1px solid #000;
      margin: 10px 0;
    }
    .price-container, .cash-container, .change-container {
      margin-top: 10px;
    }
    .date-generated {
      text-align: center;
      margin-top: 20px;
    }
    .transaction-number {
      text-align: center;
      margin-top: 10px;
      font-weight: bold;
      word-wrap: break-word; /* Added for word wrapping */
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="logo"></div>
      <div class="clinic-title">STA. MARIA DIAGNOSTIC CLINIC</div>
      <div class="clinic-address">#73 MGP BLDG. J.C. DE JESUS ST. POBLACION, STA. MARIA, BULACAN</div>
      <strong>Official Receipt</strong>
    </div>
    <div class="row">
      <div>Patient Name:</div>
      <div>${patient_name}</div>
    </div>
    <div class="row">
      <div>Procedure:</div>
      <div>${procedure}</div>
    </div>
    <div class="row">
      <div>Date:</div>
      <div>${date}</div>
    </div>
    <div class="row">
      <div>Time:</div>
      <div>${time}</div>
    </div>
    <div class="separator"></div>
    <div class="price-container row">
      <div>Price:</div>
      <div>₱${price}</div>
    </div>
    <div class="cash-container row">
      <div>Cash:</div>
      <div>₱${cash}</div>
    </div>
    <div class="change-container row">
      <div>Change:</div>
      <div>₱${change}</div>
    </div>
    <div class="separator"></div>
    <div class="transaction-number" id="transaction-number">
      Transaction Number:
    </div>
    <center><small>${transaction_number}</small></center>
    <hr>
    <center>
      <small><u>Tell us about your experience.</u></small><br>
      <small style="font-size: 10px">Send us feedback at <i>https://tinyurl.com/2e8jvbmm</i></small><br>
      <small style="font-size: 10px">Visit us also at <i>https://tinyurl.com/yfpapwfu</i></small>
    </center>      
    <div class="date-generated">
      <hr>
      <small style="font-size: 12px"><i>Date Generated: ${date_time}</i></small>
    </div>
  </div>
  <script>
    window.onload = function() {
      window.print();
      // window.close();
    };
  <\/script>
</body>
</html>


      `;

      printWindow.document.write(html);
      printWindow.document.close();
    });


  });
</script>
