<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Links Start -->

    <?php include '_common_link.php'; ?>

    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="carousel.css">
    <link rel="stylesheet" href="customer_dash.css">
    <link rel="stylesheet" href="footer.css">
    <!-- Link End--> 
</head>


<body>
    <!-- Navbar -->
    <?php require '_coustomer_nav.php';?>

    <!-- Carousel -->
    <?php include '_carousel.php'; ?>

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
                        <input type="text" class="visually-hidden" name="connection_name" value="con_name">
                        <input type="submit" class="btn btn-success" value="Update">
                    </form>
                    <form action="" method="post">
                        <input type="text" class="visually-hidden" name="connection_name" value="con_name">
                        <input type="submit" class="btn btn-danger ml-2" value="Delete">
                    </form>
                    <!-- <a href="update_plan" class="btn btn-success"><i class="fa-solid fa-up-down"></i> Update</a>
                    <a href="delete_connection" class="btn btn-danger"><i class="fa-regular fa-trash-can"></i> Delete</a> -->
                </div>
                

            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require '_common_footer.php';?>
</body>
</html>