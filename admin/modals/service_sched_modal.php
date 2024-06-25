<!-- start schedule modal -->
<div class="modal fade" id="mod_addServSched" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod_addServSchedLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="mod_addServSchedLabel">Set Services' Schedule</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <input type="hidden" id="service_sched" name="service_sched">

        <pre></pre>


        <!-- input group -->
        <div class="input-group mb-3">

          <label class="input-group-text bg-warning-subtle" for="day_of_week">Select Day</label>
          <select class="form-select" id="day_of_week">
            <option selected></option>
            <option>Sunday</option>
            <option>Monday</option>
            <option>Tuesday</option>
            <option>Wednesday</option>
            <option>Thursday</option>
            <option>Friday</option>
            <option>Saturday</option>
          </select>


          <!-- start time -->
          <label class="input-group-text bg-success-subtle" for="start_time">Start Time</label>
          <select class="form-select" id="start_time">
            <option selected></option>
            <optgroup label="AM">                    
              <option value="24:00:00">12:00 AM</option>
              <option value="01:00:00">1:00 AM</option>
              <option value="02:00:00">2:00 AM</option>
              <option value="03:00:00">3:00 AM</option>
              <option value="04:00:00">4:00 AM</option>
              <option value="05:00:00">5:00 AM</option>
              <option value="06:00:00">6:00 AM</option>
              <option value="07:00:00">7:00 AM</option>
              <option value="08:00:00">8:00 AM</option>
              <option value="09:00:00">9:00 AM</option>
              <option value="10:00:00">10:00 AM</option>
              <option value="11:00:00">11:00 AM</option>
            </optgroup>
            <option selected></option>
            <optgroup label="PM">
              <option value="12:00:00">12:00 PM</option>
              <option value="13:00:00">1:00 PM</option>
              <option value="14:00:00">2:00 PM</option>
              <option value="15:00:00">3:00 PM</option>
              <option value="16:00:00">4:00 PM</option>
              <option value="17:00:00">5:00 PM</option>
              <option value="18:00:00">6:00 PM</option>
              <option value="19:00:00">7:00 PM</option>
              <option value="20:00:00">8:00 PM</option>
              <option value="21:00:00">9:00 PM</option>
              <option value="22:00:00">10:00 PM</option>
              <option value="23:00:00">11:00 PM</option>
            </optgroup>
          </select>
          <!-- end time -->
          <label class="input-group-text bg-danger-subtle" for="end_time">End Time</label>
          <select class="form-select" id="end_time">
            <option selected></option>
            <optgroup label="AM">                    
              <option value="24:00:00">12:00 AM</option>
              <option value="01:00:00">1:00 AM</option>
              <option value="02:00:00">2:00 AM</option>
              <option value="03:00:00">3:00 AM</option>
              <option value="04:00:00">4:00 AM</option>
              <option value="05:00:00">5:00 AM</option>
              <option value="06:00:00">6:00 AM</option>
              <option value="07:00:00">7:00 AM</option>
              <option value="08:00:00">8:00 AM</option>
              <option value="09:00:00">9:00 AM</option>
              <option value="10:00:00">10:00 AM</option>
              <option value="11:00:00">11:00 AM</option>
            </optgroup>
            <option selected></option>
            <optgroup label="PM">
              <option value="12:00:00">12:00 PM</option>
              <option value="13:00:00">1:00 PM</option>
              <option value="14:00:00">2:00 PM</option>
              <option value="15:00:00">3:00 PM</option>
              <option value="16:00:00">4:00 PM</option>
              <option value="17:00:00">5:00 PM</option>
              <option value="18:00:00">6:00 PM</option>
              <option value="19:00:00">7:00 PM</option>
              <option value="20:00:00">8:00 PM</option>
              <option value="21:00:00">9:00 PM</option>
              <option value="22:00:00">10:00 PM</option>
              <option value="23:00:00">11:00 PM</option>
            </optgroup>
          </select>

          <button class="btn btn-success text-warning" type="button" id="addSched">+</button>
        </div>
        <!-- end input group -->

        <div id="bodySched"></div>

      </div>
      <div class="modal-footer">
        <button id="btnClear" type="button" class="btn btn-warning">Clear</button>
        <button id="btnSaveSched" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- end schedule modal -->

<style>
  .schedule-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .schedule-item .input-group-text,
  .schedule-item .btn {
    flex: 1;
    margin: 2px;
  }
</style>