<?php
    // Check if session is not set OR user is not an employee
    if (!isset($_SESSION['user']) || $_SESSION['user'] != "employee") {
        // Clear session data and destroy the session
        session_unset();
        session_destroy();
        
        // Redirect to login page
        header("location: login.php");
        die();
    }
?>
