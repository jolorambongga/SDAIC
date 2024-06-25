<?php
$title = 'Admin - Users';
$active_users = 'active';
include_once('header.php');
?>

<body>

  <div class="my-wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-4">
          <h1>Edit Users</h1>
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
                <th scope="col">Username</th>
                <th scope="col">Patient Name</th>
                <th scope="col">Contact Number</th>
                <th scope="col">Email Address</th>
                <th scope="col">Address</th>
                <th scope="col">Birthday</th>
                <th scope="col">Age</th>
                <th scope="col">Date Registered</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody id="tbodyUsers">

            </tbody>
          </table>
        </div>
      </div>
      <!-- end table -->
    </div>
  </div>

  <script>
    $(document).ready(function() {
    loadUsers();

    function loadUsers() {
        $.ajax({
            type: 'GET',
            url: 'handles/users/read_users.php',
            dataType: 'JSON',
            success: function(response) {
                console.log("RESPONSE READ USERS:", response);
                $('#tbodyUsers').empty();
                
                response.data.forEach(function(data) {
                    const read_users_html = `
                        <tr>
                            <th scope="row">${data.user_id}</th>
                            <td><small>${data.username}</small></th>
                            <td><small>${data.first_name} ${data.middle_name} ${data.last_name}</small></td>
                            <td><small>${data.contact}</small></td>
                            <td><small>${data.email}</small></td>
                            <td><small>${data.address}</small></td>
                            <td><small>${data.formatted_birthday}</small></td>
                            <td><small>${data.age}</small></td>
                            <td><small>${data.formatted_user_created}</small></td>
                            <td>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end text-center">
                                    <button id='edit_${data.user_id}' type='button' class='btn btn-mymedium btn-sm'><i class="fas fa-edit"></i></button>
                                    <button id='delete_${data.user_id}' type='button' class='btn btn-myshadow btn-sm'><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    `;
                    
                    $('#tbodyUsers').append(read_users_html);
                });
            },
            error: function(error) {
                console.log("ERROR READ USERS:", error);
            }
        });
    }
});

  </script>

  <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js'></script>
</body>

</html>