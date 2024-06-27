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

<script>
  $(document).ready(function() {

    $(document).on('click', '#callReceipt', function() {
      $('#r_patient_name').val($(this).closest('td').data('full-name'));
      $('#r_procedure').val($(this).closest('td').data('appointment-name'));
      $('#r_appointment_date').val($(this).closest('td').data('appointment-date'));
      $('#r_appointment_time').val($(this).closest('td').data('appointment-time'));
      $('#r_price').val($(this).closest('td').data('price'));
      console.log($('#price'));
      var patient_name = $('#r_patient_name').val();
      var service_name = $('#r_procedure').val();
      var service_price = $('#r_price').val();
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

      populateReceiptModal(patientName, procedureType, appointmentDate, appointmentTime);

      $('#mod_Input').modal('hide');
      $('#receiptPrice').text(price.toFixed(2));
      $('#receiptCash').text(cash.toFixed(2));
      $('#receiptChange').text(change.toFixed(2));
      $('#mod_Receipt').modal('show');
    });

$('#printReceipt').click(function() {
  var printWindow = window.open('', '_blank');
  var modalContent = $('#mod_Receipt .modal-content').clone(); // Clone the modal content

  // Remove any existing script tags to prevent execution in the print window
  modalContent.find('script').remove();

  var html = `
  <!DOCTYPE html>
  <html lang="en">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Receipt</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet">
<style>
    .container {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .centered-text {
      display: flex;
      justify-content: center;
      width: 100%;
    }
    .left-align {
      text-align: left;
      width: 50%; /* Adjust this value as needed */
    }
    .right-align {
      text-align: right;
      width: 85%; /* Adjust this value as needed */
    }
    .price-container, .cash-container, .change-container {
      display: flex;
      justify-content: center;
      width: 100%;
    }
    .price, .cash, .change {
      margin-left: 50px;
    }
    .date-generated {
      text-align: center;
      margin-top: 20px; /* Adjust this value for spacing */
    }
  </style>
  <link rel="stylesheet" href="print.css" media="print">
  </head>
  <body>
    <div class="container">
  ${modalContent[0].outerHTML}
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
