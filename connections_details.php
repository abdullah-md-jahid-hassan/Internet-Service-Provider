<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comone page for Admin</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="navbar.css">
    <!-- Link End -->

</head>
<body>
<?php
    //Login check
    require '_logincheck_admin.php';
        
    //Defining Page
    $page_type = "connections";
    $page_name = "Connection Details";
    
    //Navbar
    require '_nav_admin.php';
?>

    <div class="container">
        <div class="row row-cols-auto justify-content-center">

            <div class="row-auto bg-dark text-light p-4 rounded m-2">
                <h3 class="text-center text-decoration-underline">Connection Info</h3>
                <p class="fs-5 mt-2">
                    <b>Name: </b>Connection_name<br>
                    <b>Address: </b>Connection_address<br>
                    <b>Statas: </b>Connection_statas
                </p>
                <button class="btn btn-danger" type="submit" name="delete"><i class="fa-solid fa-trash-can"></i> Delete Requests</button>
            </div>

            <div class="row-auto bg-dark text-light p-4 rounded m-2">
                <h3 class="text-center text-decoration-underline">Plan Info</h3>
                <p class="fs-5 mt-2">
                    <b>Name: </b>plan_name<br>
                    <b>Address: </b>plan_address<br>
                    <b>Statas: </b>plan_speed<br>
                    <b>Speed: </b>plan_price<br>
                    <b>Real-IP: </b>plan_realip<br>
                </p>
                <button class="btn btn-primary" type="submit" name="update"><i class="fa-solid fa-circle-up"></i> Update Requests</button>
            </div>

            <div class="row-auto bg-dark text-light p-4 rounded m-2">
                <h3 class="text-center text-decoration-underline">Customer Info</h3>
                <p class="fs-5 mt-2">
                    <b>Name: </b>Customer_name<br>
                    <b>Address: </b>Customer_address<br>
                    <b>Phone: </b>Customer_Phone<br>
                    <b>Email: </b>Customer_Email<br>
                </p>
                <button class="btn btn-success" type="submit" name="customer_details">Customer Details</button>
            </div>

        </div>
    </div>

</body>
</html>