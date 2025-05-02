<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Details</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="connections_details.css">
    <!-- Link End -->

</head>
<body>
    <?php
        //Defining Page
        $page_type = "requests";
        $page_name = "Request Details";
        
        //Navbar
        require '_nav_admin.php';

        // connect to the database
        require '_database_connect.php';

        // Get Connection Info
        $find_connection_sql = "SELECT * FROM `connections` WHERE `id` = '{$_SESSION['connections_id_details']}'";
        $find_connection = mysqli_query($connect, $find_connection_sql);
        $connection = mysqli_fetch_assoc($find_connection);

        //Get plan info
        $plan_sql = "SELECT * FROM `plans` WHERE `id` = '{$connection['plan_id']}'";
        $get_plan = mysqli_query($connect, $plan_sql);
        $plan = mysqli_fetch_assoc($get_plan);

        //Getting Requested Plan Info
        if($connection['state']=="Update pending"){
            $req_plan_sql = "SELECT * FROM `plans` WHERE `id` = '{$connection['req_plan']}'";
            $get_req_plan = mysqli_query($connect, $req_plan_sql);
            $req_plan = mysqli_fetch_assoc($get_req_plan);
        }

        //Get Customer info
        $customer_sql = "SELECT * FROM `customer` WHERE `id` = '{$connection['customer_id']}'";
        $get_customer = mysqli_query($connect, $customer_sql);
        $customer = mysqli_fetch_assoc($get_customer);

        // Close the database connection
        mysqli_close($connect);
    ?>

    <div class="container">
        <div class="row row-cols-auto justify-content-center">

            <div class="row-auto card bg-dark text-light py-3 rounded m-2">
                <div class=" card-header">
                    <h3 class="text-center text-decoration-underline">Connection Info</h3>
                </div>
                <div class="card-body">
                    <b>Name: </b><?php echo $connection['name'] ?><br>
                    <b>Address: </b><?php echo $connection['address'] ?><br>
                    <b>Statas: </b><?php echo $connection['state'] ?>
                </div>
                <div class="card-footer">
                    <form method="post">
                        <?php
                            if ($connection['state']=="Pending"){
                                echo "<button class='btn btn-success' type='submit' name='connection'><i class='fa-solid fa-plus'></i> Accept Connection</button>

                                <button class='btn btn-danger' type='submit' name='reject'><i class='fa-solid fa-xmark'></i> Reject Request</button>";
                            } else if ($connection['state']=="Disconnection pending"){
                                echo "<button class='btn btn-success' type='submit' name='delete'><i class='fa-solid fa-trash-can'></i> Accept Disconnection</button>

                                <button class='btn btn-danger' type='submit' name='reject'><i class='fa-solid fa-xmark'></i> Reject Request</button>";
                            }
                        ?>
                    </form>
                </div>
            </div>

            <div class="row-auto card bg-dark text-light py-3 rounded m-2">
                <div class=" card-header">
                    <h3 class="text-center text-decoration-underline"><?php if($connection['state']=="Update pending"){echo "Current ";} ?>Plan Info</h3>
                </div>
                <div class="card-body">
                    <b>Name: </b><?php echo $plan['name'] ?><br>
                    <b>Speed: </b><?php echo $plan['speed'] ?><br>
                    <b>Real-IP: </b><?php echo $plan['realip'] ?><br>
                    <b>Price: </b><?php echo $plan['price'] ?>
                </div>
            </div>

            <?php
                if($connection['state']=="Update pending"){
                    echo "<div class='row-auto card bg-dark text-light py-3 rounded m-2'>
                        <div class=' card-header'>
                            <h3 class='text-center text-decoration-underline'>Requested Plan Info</h3>
                        </div>
                        <div class='card-body'>
                            <b>Name: </b>$req_plan[name]<br>
                            <b>Speed: </b>$req_plan[speed]<br>
                            <b>Real-IP: </b>$req_plan[realip]<br>
                            <b>Price: </b>$req_plan[price]
                        </div>
                        <div class='card-footer'>
                            <form method='post'>
                                <button class='btn btn-success' type='submit' name='update'><i class='fa-solid fa-plus'></i> Accept update</button>
                    
                                <button class='btn btn-danger' type='submit' name='reject'><i class='fa-solid fa-xmark'></i> Reject Request</button>
                            </form>
                        </div>
                    </div>";
                }
            ?>

            <div class="row-auto card bg-dark text-light py-3 rounded m-2">
                <div class=" card-header">
                    <h3 class="text-center text-decoration-underline">Customer Info</h3>
                </div>
                <div class="card-body">
                    <b>Name: </b><?php echo $customer['name'] ?><br>
                    <b>Address: </b><?php echo $customer['address'] ?><br>
                    <b>Phone: </b><?php echo $customer['phone'] ?><br>
                    <b>Email: </b><?php echo $customer['email'] ?><br>
                </div>
                <div class="card-footer">
                    <a class="btn btn-success" href="customer_details.php?c_id=<?php echo $customer['id'] ?>">Customer Details</a>
                </div>
            </div>

        </div>
        <a class="btn btn-info" href="requests.php"><i class="fa-solid fa-delete-left"></i> Back</a>
    </div>


    <?php

        //accept Connection request OR update button Clicked OR Delete button clicked
        if (isset($_POST['connection']) || isset($_POST['update']) || isset($_POST['delete'])){
            //Storing connection id to the session
            $_SESSION['connections_id_details'] =$connection['id'];

            //Redirection to the assign task page
            echo "<script> window.location.href='assign_task.php';</script>";
            die();
        }

        //Reject request
        else if (isset($_POST['reject'])){
            // connect to the database
            require '_database_connect.php';

            // Reject And Get Back to previous state
            if ($connection['state']=="Pending"){
                $reject_sql = "DELETE FROM `connections` WHERE `id` = '{$connection['id']}'";
            }else{
                $reject_sql = "UPDATE `connections` SET `state` = 'Active', `req_plan`='' WHERE `id` = '{$connection['id']}'";
                $reset_req_plan = "UPDATE `connections` SET `req_plan` = '' WHERE `connections`.`id` = '{$connection['id']}'";
            }
            
            $set_state = mysqli_query($connect, $reject_sql);

            // Close the database connection
            mysqli_close($connect);

            //Redetection to the connection page
            echo "<script> window.location.href='requests.php';</script>";
            die();
        }
    ?>

</body>
</html>
