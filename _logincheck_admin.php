<?php
    if(!isset($_SESSION['user']) || ($_SESSION['user'] != "admin" && $_SESSION['user'] != "sup_admin")) {
        session_unset();
        session_destroy();
        echo '<script>window.location.replace("login.php");</script>';
        die();
    }
?>
