<?php
$title = 'Admin - Doctors';
$active_doctors = 'active';
include_once('header.php');
?>

<body>
  <!-- start wrapper -->
  <div class="my-wrapper">
    <!-- start container fluid -->
    <div class="container-fluid">
      <div class="row">
        <div class="col-4">
          <h1>Edit Doctors</h1>
        </div>
      </div>
      <!-- add button -->
      <div class="row">
        <div class="col-12">
          <button type="button" class="btn btn-mydark mt-3 mb-3 float-end btn-sm" data-bs-toggle="modal"
          data-bs-target="#mod_addDoc">Add Doctor</button>
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
                <th scope="col">Doctor Name</th>
                <th scope="col">Schedule</th>
                <th scope="col">Contact</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody id="tbodyDoctors">

            </tbody>
          </table>
        </div>
      </div>
      <!-- end table -->
      <!-- start add doctor modal -->
      <form id="frm_addDoc" method="POST">
        <div class="modal fade" id="mod_addDoc" tabindex="-1" aria-labelledby="mod_addDocLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="mod_addDocLabel">Add New Doctor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!-- doctor first_name, middle_name, last_name, contact, avail_date, avail_time, action -->
                <!-- start doctor name -->
                <label for="first_name" class="form-label">Doctor's First Name</label>
                <input type="text" id="first_name" class="form-control" required>
                <pre></pre>
                <label for="middle_name" class="form-label">Doctor's Middle Name</label>
                <input type="text" id="middle_name" class="form-control">
                <pre></pre>
                <label for="last_name" class="form-label">Doctor's Last Name</label>
                <input type="text" id="last_name" class="form-control" required>
                <pre></pre>
                <!-- end doctor name -->
                <!-- start doctor contact -->
                <label for="contact" class="form-label">Doctor's Contact<small>&nbsp;(ex. 09663419521)</small></label>
                <input maxlength="11" type="text" id="contact" class="form-control" required pattern="09[0-9]{9}">
                <pre></pre>
                <!-- end doctor contact -->
                <!-- start doctor sched -->
                <button id="callSetSched" type="button" class="btn btn-warning w-100">Set Schedule</button>
                <!-- end doctor sched -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                
                <button type="submit" class="btn btn-primary">Add Doctor</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <!-- end add doctor modal -->
      <!-- start add doctor sched modal -->
      <?php
      include_once('modals/doctor_sched_modal.php');
      ?>
      <!-- end add doctor sched modal -->


      <!-- start edit doctor modal -->
      <form id="frm_editDoc" method="POST">
        <div class="modal fade" id="mod_editDoc" tabindex="-1" aria-labelledby="mod_editDocLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="mod_editDocLabel">Edit Doctor</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!-- doctor first_name, middle_name, last_name, contact, avail_date, avail_time, action -->
                <!-- start doctor name -->
                <label for="e_first_name" class="form-label">Doctor's First Name</label>
                <input type="text" id="e_first_name" class="form-control" required>
                <pre></pre>
                <label for="e_middle_name" class="form-label">Doctor's Middle Name</label>
                <input type="text" id="e_middle_name" class="form-control">
                <pre></pre>
                <label for="e_last_name" class="form-label">Doctor's Last Name</label>
                <input type="text" id="e_last_name" class="form-control" required>
                <pre></pre>
                <!-- end doctor name -->
                <!-- start doctor contact -->
                <label for="e_contact" class="form-label">Doctor's Contact</label>
                <input type="number" id="e_contact" class="form-control" required>
                <pre></pre>
                <!-- end doctor contact -->
                <!-- start doctor sched -->
                <button id="e_callSetSched" type="button" class="btn btn-warning w-100">Change Schedule</button>
                <!-- end doctor sched -->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                
                <button type="submit" class="btn btn-primary">Save Changes</button>
              </div>
            </div>
          </div>
        </div>
      </form>
      <!-- end edit doctor modal -->
      <!-- start edit doctor sched modal -->
      <?php
      include_once('modals/e_doctor_sched_modal.php');
      ?>
      <!-- end edit doctor sched modal -->

      <!-- start delete doctor modal -->
      <div class="modal fade" id="mod_delDoc" tabindex="-1" aria-labelledby="mod_delDocLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="mod_delDocLabel">Delete Doctor</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <label for="del_user_input" class="form-label">Type <b>DELETE</b> to delete Dr. <span id="delDrName"></span>'s record.</label>
              <input type="text" id="del_user_input" class="form-control" required>            
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

              <button id="btnDel" type="button" data-doctor-id="" class="btn btn-danger">Delete Record</button>
            </div>
          </div>
        </div>
      </div>
      <!-- end delete doctor modal -->


    </div>
    <!-- end container fluid -->
  </div>
  <!-- end wrapper -->

  <?php
  include_once('script/edit_doctors_script.php');
  ?>

  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>
</body>

</html>
