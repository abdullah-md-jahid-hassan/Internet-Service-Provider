<?php
    session_start();

    if (isset($_SESSION['user'])) {
        // connect to the database
        require '_database_connect.php';

        // Session cleanup
        session_unset();
        session_destroy();

        // Close the database connection
        mysqli_close($connect);
    }

    echo '<script>window.location.replace("login.php");</script>';
?>
