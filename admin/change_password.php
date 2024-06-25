<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['change_password']) || $_SESSION['change_password'] !== true) {
    header('Location: ../client/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="">
</head>
<body>
    <div class="container">
        <h1 class="text-center">Change Your Password</h1>
        <div id="changePasswordForm">
            <div id="changePasswordMessage"></div>
            <form id="frm_change_password">
                <div class="form-group">
                    <label for="currentPassword">Current Password:</label>
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">New Password:</label>
                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm New Password:</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bowser"></script>
    <script>
        $(document).ready(function() {
            $('#frm_change_password').submit(function(e) {
                e.preventDefault();
                var currentPassword = $('#currentPassword').val();
                var newPassword = $('#newPassword').val();
                var confirmPassword = $('#confirmPassword').val();

                if (newPassword !== confirmPassword) {
                    $('#changePasswordMessage').html('<div class="alert alert-danger">Passwords do not match.</div>');
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: 'handles/change_password_endpoint.php',
                    data: { currentPassword: currentPassword, newPassword: newPassword },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#changePasswordMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 2000);
                        } else {
                            $('#changePasswordMessage').html('<div class="alert alert-danger">' + response.message + '</div>');
                        }
                    },
                    error: function(error) {
                        console.log(error);
                        $('#changePasswordMessage').html('<div class="alert alert-danger">Error occurred. Please try again.</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
