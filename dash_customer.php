<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Links Start -->

    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="carousel.css">
    <link rel="stylesheet" href="dash_customer.css">
    <link rel="stylesheet" href="footer.css">
    <!-- Link End--> 
</head>


<body>
    <?php
        //Login check
        require '_logincheck_customer.php';

        $con_name = "";
        $page_name = "Home";
        $page_type = "home";


        //Navbar
        require '_nav_customer.php';
        //Carousel
        //include '_carousel.php';

    ?>


    <div class="container rounded bg-dark text-light my-2">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-5 py-2 my-2 d-flex justify-content-between">
            <div class="col-auto ">
                <ul class="list-unstyled">
                    <li class="ms-3 connection_name fs-3 fw-bolder">Name of the connection</li>
                    <li class="ms-3 connection_address fs-4">House 40, road 05, sector 10, uttora.</li>
                    <li class="ms-3 connection_status fs-5">Status: Paanding</li>
                    <li class="ms-3 connection_plan fs-6">Active Plan: 20Mbps -- 1000tk/month</li>
                </ul>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <form action="" method="post" class="">
                        <input type="text" class="" name="connection_name" <?php echo 'value="' . $con_name. '"'?> >
                        <button type="submit" class="btn btn-success"><i class="fa-solid fa-up-down"></i> Update</button>
                    </form>
                    <form action="" method="post">
                        <input type="text" class="" name="connection_name" <?php echo 'value="' . $con_name . '"'?>>
                        <button type="submit" class="btn btn-danger ml-2"><i class="fa-regular fa-trash-can"></i> Delete</button>
                    </form>
                </div>
                

            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '_footer_common.php';?>
</body>
</html>