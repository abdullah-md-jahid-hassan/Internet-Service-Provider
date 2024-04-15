<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="dash_admin.css">
    <!-- Link End -->

</head>
<body>

    <?php
        //Login check
        require '_logincheck_admin.php';

        // Seasion variable clear
        require '_unset_seasion_variable.php';

        //Defining Page Type
        $page_type = "employee";
        $page_name = "employee";

        //Navbar
        require '_nav_admin.php';
    ?>

    <div class="container bg-info">
        
    </div>

</body>
</html>