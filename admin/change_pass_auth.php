<?php
session_start();
if ($_SESSION['change_password'] = true) {
    header('Location: change_password.php');
    exit;
}
?>