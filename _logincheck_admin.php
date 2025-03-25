<?php
    if(!isset($_SESSION['user']) || ($_SESSION['user'] != "admin" && $_SESSION['user'] != "sup_admin")) {
        session_unset();
        session_destroy();
        header("location: login.php");
        die();
    }
?>
