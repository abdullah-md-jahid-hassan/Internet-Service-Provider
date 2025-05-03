<?php
    // Check if session is not set OR user is not a customer
    if (!isset($_SESSION['user']) || $_SESSION['user'] != "customer") {
        // Clear and destroy session
        session_unset();
        session_destroy();
        
        // Redirect to login page
        echo '<script>window.location.replace("login.php");</script>';
        die();
    }
?>
