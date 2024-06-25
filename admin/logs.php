<?php
$title = 'Admin - Logs';
$active_logs = 'active';
include_once('header.php');
?>

<body>

  <div class="my-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-4">
          <h1>View Logs</h1>
        </div>
      </div>
      <!-- add button -->
      <div class="row">
        <div class="col-12">
          <!-- <button type="button" class="btn btn-mydark mt-3 mb-3 float-end btn-sm">IDK YET</button> -->
        </div>
      </div>
      <!-- end button -->
      <!-- table -->
      <div class="row">
        <div class="col-md-12">
          <table class="table table-striped text-end">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Category</th>
                <th scope="col">Action</th>
                <th scope="col">Affected Data</th>
                <th scope="col">Details</th>
                <th scope="col">Timestamp</th>
              </tr>
            </thead>
            <tbody id="tbodyLogs">

            </tbody>
          </table>
        </div>
      </div>
      <!-- end table -->
    </div>
  </div>


  <script>
  $(document).ready(function() {
    loadLogs();

    // READ LOGS
    function loadLogs(){
      $.ajax({
        type: 'GET',
        url: 'handles/logs/read_logs.php',
        dataType: 'json',
        success: function(response) {
          console.log("SUCCESS READ LOGS: ", response);
          var tbody = $('#tbodyLogs');
          tbody.empty();
          response.data.forEach(function(tae) {
            const read_logs_html = `
            <tr>
            <th scope="row">${tae.log_id}</th>
            <td>${tae.first_name} ${tae.last_name}</td>
            <td>${tae.category}</td>
            <td>${tae.action}</td>
            <td>${tae.affected_data}</td>
            <td>
            Device: ${tae.device},
            Model: ${tae.device_model}<br>
            Browser: ${tae.browser}<br>
            IP Address: ${tae.ip_address}<br>
            Location: ${tae.location}
            </td>
            <td>${tae.time_stamp}</td>
            </tr>
            `;
            tbody.append(read_logs_html);
          });
        },
        error: function(error) {
          console.log("ERROR READ LOGS:", error);
          var row = '<tr><td colspan="8" class="text-center">Error fetching logs</td></tr>';
          $('#tbodyLogs').append(row);
        }
      });
    }
  });
</script>


  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>
</body>

</html>