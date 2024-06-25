<?php
    try {
        
        function checkAuth() {
            if (!isset($_SESSION['user_id'])) {
                header('Location: login.php');
                exit();
            }
        }

        function checkLoggedIn() {
            if(isset($_SESSION['user_id'])) {
                header('Location: index.php');
                exit();
            }
        }
    } catch (Exception $e) {
        echo '<script>console.log(' . json_encode($e->getMessage()) . ');</script>';
    }
?>