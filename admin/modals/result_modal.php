<div class="modal fade" id="mod_Result" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod_ResultLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="mod_ResultLabel">Email for Result</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <label for="note" class="form-label">Additional Note:</label>
        <input type="text" class="form-control" id="note">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button id="submitResult" type="button" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $(document).on('click', '#callResult', function() {
      var appointment_id = $(this).closest('td').data('appointment-id');
      $('#submitResult').data('appointment-id', appointment_id);
      console.log("CALL RESULT", appointment_id);
    });

    $(document).on('click', '#submitResult', function() {
      var appointment_id = $(this).data('appointment-id');
      console.log("submit", appointment_id);
    });
  });
</script>