<?php
$title = 'Admin - Landing';
$active_landing = 'active';
include_once('header.php');
?>

<body>
  <!-- start wrapper -->
  <div class="my-wrapper">
    <!-- start container fluid -->
    <div class="container-fluid">
      <!-- start label -->
      <div class="row">
        <div class="col-12">
          <h1>Edit Landing Page</h1>
          <button id="" type="button" data-bs-toggle="modal" data-bs-target="#mod_addLanding" class="btn btn-warning w-100">Edit Landing Page</button>
          <pre></pre>
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>About Us</th>
                  <th>About Us Image</th>
                  <th>Main Image</th>
                </tr>
              </thead>
              <tbody id="current_landing">
                <!-- Table rows will be dynamically populated here -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- end label -->
    </div>
    <!-- end container fluid -->
  </div>
  <!-- end wrapper -->

  <!-- Bootstrap Modal with Form -->
  <form id="frm_addLanding">
    <div class="modal fade" id="mod_addLanding" tabindex="-1" aria-labelledby="mod_addLandingLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <!-- start modal header -->
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="mod_addLandingLabel">Edit Landing</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <!-- end modal header -->
          <div class="modal-body">
            <!-- start service name -->

            <!-- about us -->
            <label for="about_us" class="form-label">About Us</label>
            <textarea id="about_us" class="form-control" required></textarea>
            <pre></pre>

            <!-- about us image -->
            <label for="about_us_image" class="form-label">About Us Image</label>
            <div class="form-check mb-3">
              <input class="form-check-input" type="radio" name="about_us_image_option" id="about_us_image_url" value="url" required>
              <label class="form-check-label" for="about_us_image_url">
                URL
              </label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="radio" name="about_us_image_option" id="about_us_image_upload" value="upload" required>
              <label class="form-check-label" for="about_us_image_upload">
                Upload Image
              </label>
            </div>
            <div class="input-group mb-3 about-us-image-url" style="display: none;">
              <input type="url" class="form-control" id="about_us_image_url_input" placeholder="Enter URL" required>
            </div>
            <div class="input-group mb-3 about-us-image-upload" style="display: none;">
              <input type="file" class="form-control" id="about_us_image_upload_input" required>
            </div>
            <pre></pre>

            <!-- main image -->
            <label for="main_image" class="form-label">Main Image</label>
            <div class="form-check mb-3">
              <input class="form-check-input" type="radio" name="main_image_option" id="main_image_url" value="url" required>
              <label class="form-check-label" for="main_image_url">
                URL
              </label>
            </div>
            <div class="form-check mb-3">
              <input class="form-check-input" type="radio" name="main_image_option" id="main_image_upload" value="upload" required>
              <label class="form-check-label" for="main_image_upload">
                Upload Image
              </label>
            </div>
            <div class="input-group mb-3 main-image-url" style="display: none;">
              <input type="url" class="form-control" id="main_image_url_input" placeholder="Enter URL" required>
            </div>
            <div class="input-group mb-3 main-image-upload" style="display: none;">
              <input type="file" class="form-control" id="main_image_upload_input" required>
            </div>
            <pre></pre>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            <button id="btnSave" type="submit" class="btn btn-success">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!-- end modal -->

  <!-- Bootstrap Modal -->
<div class="modal fade" id="mod_ReqImg" tabindex="-1" aria-labelledby="mod_ReqImgLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="mod_ReqImgLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body text-center">
                <img src="" class="img-fluid" alt="Image">
            </div>

            <!-- Modal Footer (optional) -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {

      loadLandingInfo();

      function loadLandingInfo() {
    $.ajax({
        type: 'GET',
        url: 'handles/landing/get_landing.php',
        data: { landing_id: 1 }, // Adjust landing_id as needed
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response.status === 'success') {
                var data = response.data;

                // Prepare HTML with buttons to view images in modal
                const landing_html = `
                    <tr>
                        <td>${data.about_us}</td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm view-image-btn" data-bs-toggle="modal" data-bs-target="#mod_ReqImg" data-image-url="${data.about_us_image}">View Image</button>
                        </td>
                        <td>
                            <button type="button" class="btn btn-warning btn-sm view-image-btn" data-bs-toggle="modal" data-bs-target="#mod_ReqImg" data-image-url="${data.main_image}">View Image</button>
                        </td>
                    </tr>
                `;

                $('#current_landing').append(landing_html);

            } else {
                const landing_html = `
                    <i><tr><td colspan="3"></td></tr></i>
                `;
                $('#current_landing').append(landing_html);
            }
        },
        error: function(error) {
            console.log('AJAX Error:', error);
        }
    });

    // Modal image display handling
    $('#mod_ReqImg').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var imageUrl = button.data('image-url'); // Extract image URL from data- attribute

        // Update modal content dynamically with the image URL
        var modal = $(this);
        modal.find('.modal-body img').attr('src', imageUrl);
    });
}


      $('input[name="about_us_image_option"]').change(function() {
        if (this.value === 'url') {
          $('.about-us-image-url').show();
          $('.about-us-image-upload').hide();
          $('#about_us_image_upload_input').removeAttr('required');
          $('#about_us_image_url_input').prop('required', true);
        } else if (this.value === 'upload') {
          $('.about-us-image-url').hide();
          $('.about-us-image-upload').show();
          $('#about_us_image_url_input').removeAttr('required');
          $('#about_us_image_upload_input').prop('required', true);
        }
      });

      $('input[name="main_image_option"]').change(function() {
        if (this.value === 'url') {
          $('.main-image-url').show();
          $('.main-image-upload').hide();
          $('#main_image_upload_input').removeAttr('required');
          $('#main_image_url_input').prop('required', true);
        } else if (this.value === 'upload') {
          $('.main-image-url').hide();
          $('.main-image-upload').show();
          $('#main_image_url_input').removeAttr('required');
          $('#main_image_upload_input').prop('required', true);
        }
      });

      $(document).on('submit', '#frm_addLanding', function(event) {
        event.preventDefault(); // Prevent form submission

        var about_us = $('#about_us').val();

        var formData = new FormData();
        formData.append('about_us', about_us);

        if ($('#about_us_image_upload_input')[0].files.length > 0) {
          formData.append('about_us_image', $('#about_us_image_upload_input')[0].files[0]);
        } else {
          formData.append('about_us_image', $('#about_us_image_url_input').val());
        }

        if ($('#main_image_upload_input')[0].files.length > 0) {
          formData.append('main_image', $('#main_image_upload_input')[0].files[0]);
        } else {
          formData.append('main_image', $('#main_image_url_input').val());
        }

        $.ajax({
          type: 'POST',
          url: 'handles/landing/create_landing.php',
          data: formData,
          dataType: 'json',
          contentType: false,
          processData: false,
          success: function(response) {
            console.log("SAVE LANDING RESPONSE", response);
            // Handle success response
            $('#mod_addLanding .btn-close').click();
            loadLandingInfo();
          },
          error: function(error) {
            console.log("ERROR LANDING RESPONSE", error);
            // Handle error response
          }
        });
      });
    });
  </script>
</body>
</html>
