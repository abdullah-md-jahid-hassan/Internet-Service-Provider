<?php session_start(); ?>
<?php
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Links Start -->

    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="carousel.css">
    <!-- Link End--> 
</head>


<body>
<?php
    //Defining Page
    $page_type = $page_name = "home";

    //Navbar
    require '_nav_customer.php';

    //Carousel
    include '_carousel.php';

    // Sanitize session ID
    $customer_id = $_SESSION['id'];

    //variables
    $key = "all";
    $word = "";

    //if searched
    if(isset($_GET['search'])){
        $key = $_GET['key'];
        $word = $_GET['word'];
    }

    //If Re-Set
    if(isset($_GET['reset'])){
        $key = "all";
        $word = "";
    }

    //Seeing Details
    if(isset($_POST['details'])){
        // Season variable clear
        require '_unset_session_variable.php';
        $_SESSION['connections_id_details'] = $_POST['con_id'];
        echo "<script> window.location.href='connections_details_customer.php';</script>";
        die();
    }
    
    // connect to the database
    require '_database_connect.php';

    // Query to get the total due amount
    $total_due_sql = "SELECT SUM(bill.amount) AS total_due FROM connections 
    JOIN bill ON connections.id = bill.connection_id 
    WHERE connections.customer_id = '{$customer_id}' AND bill.state = 'Unpaid'";
    $total_due_result = mysqli_query($connect, $total_due_sql);
    $total_due_row = mysqli_fetch_assoc($total_due_result);
    $total_due = $total_due_row['total_due'] ?? 0; // Default to 0 if null

    //Searchbar function
    $find_connections_sql = "SELECT * FROM `connections` WHERE `customer_id` = '{$_SESSION['id']}'";
    if(isset($key) && isset($word)){
        if($key=="all" && $word!=""){
            $find_connections_sql = "SELECT * FROM `connections` WHERE `customer_id` = '{$_SESSION['id']}' AND CONCAT(name, address, state) LIKE '%$word%'";
        } else if($key=="name" && $word!=""){
            $find_connections_sql = "SELECT * FROM `connections` WHERE `customer_id` = '{$_SESSION['id']}' AND `name` LIKE '%$word%'";
        } else if($key=="address" && $word!=""){
            $find_connections_sql = "SELECT * FROM `connections` WHERE `customer_id` = '{$_SESSION['id']}' AND `address` LIKE '%$word%'";
        } else if($key=="type" && $word!=""){
            $find_connections_sql = "SELECT * FROM `connections` WHERE `customer_id` = '{$_SESSION['id']}' AND `type` LIKE '%$word%'";
        }
    }
    //Query
    $find_connections = mysqli_query($connect, $find_connections_sql);
    $total_connections = mysqli_num_rows($find_connections);

    //Get num of connections
    $connection_num_sql = "SELECT 
    SUM(CASE WHEN type = 'residential_plans' THEN 1 ELSE 0 END) AS res,
    SUM(CASE WHEN type = 'organizational_plans' THEN 1 ELSE 0 END) AS org
    FROM connections 
    WHERE customer_id = '{$customer_id}'";
    $find_connection_result = mysqli_query($connect, $connection_num_sql);
    $connection_counts = mysqli_fetch_assoc($find_connection_result);
    $residential_con_num = $connection_counts['res'] ? : 0;
    $organizational_con_num = $connection_counts['org'] ? : 0;


    // Query to get the total due amount
    $total_due_sql = "SELECT SUM(bill.amount) AS total_due FROM connections 
    JOIN bill ON connections.id = bill.connection_id 
    WHERE connections.customer_id = '{$customer_id}' AND bill.state = 'Unpaid'";
    $total_due_result = mysqli_query($connect, $total_due_sql);
    $total_due_row = mysqli_fetch_assoc($total_due_result);
    $total_due = $total_due_row['total_due'] ?? 0; // Default to 0 if null

    // Fetch complaints from the database
    $complaint_sql = "SELECT * FROM complaint WHERE customer_id = '{$customer_id}'";
    $find_complains = mysqli_query($connect, $complaint_sql);
    $complaint_num = mysqli_num_rows($find_complains);

    // Close the database connection
    mysqli_close($connect);
?>

<div class="container my-3">
    <div class="row justify-content-center my-5">
        <!-- Connections -->
        <div class="col-auto p-2">
            <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                <div class="card-side rounded-start" style="background-color: rgb(86, 86, 221)"></div>
                <div class="card-text">
                    <h5><i class="fa-solid fa-wifi" style="color:rgb(86, 86, 221)"></i> Connections</h5>
                    <p>Residential: <?php echo $residential_con_num; ?></p>
                    <p>Organizational: <?php echo $organizational_con_num; ?></p>
                </div>
            </div>
        </div>

        <!-- Bill and Bills -->
        <div class="col-auto p-2">
            <div class="card-box card-1 bg-light rounded d-flex align-items-center">
                <div class="card-side rounded-start" style="background-color: green"></div>
                <div class="card-text">
                    <h5><i class="fa-solid fa-money-check-dollar" style="color:green"></i> Bills And Payment</h5>

                    <?php if ($total_due > 0): ?>
                        <form id="payForm" action="pay.php" method="POST">
                            <input type="hidden" name="pay" value="all">
                            <button type="submit" class="btn btn-danger p-2"><b><i class='fa-solid fa-hand-holding-dollar fa-beat'></i> Pay <?php echo $total_due; ?></b></button>
                        </form>
                    <?php else: ?>
                        <p><i class="fa-solid fa-thumbs-up"></i> No due</p>
                    <?php endif; ?>

                    <a class="btn btn-success rounded mt-1" href="customer_info.php#bill_history">Bill details</a>
                    
                </div>
            </div>
        </div>

        <!-- Complains -->
        <div class="col-auto p-2">
            <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                <div class="card-side rounded-start" style="background-color: black"></div>
                <div class="card-text">
                    <h5><i class="fa-solid fa-paper-plane"></i> Complains</h5>
                    <p>Unsolved Complain: <?php
                    echo $complaint_num;
                    ?></p>
                    <a class="btn btn-secondary rounded p-2" href="customer_info.php#complaint_history">Complain list</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Search bar with filter -->
    <form method="get">
        <div class="input-group">
            <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button>  
            <select class="search-select" name="key" style="width: 20%;">
                <option value="all" selected>Search By</option>
                <option value="name">Name</option>
                <option value="address">Address</option>
                <option value="type">Type (res/org)</option>
                <option value="speed">Speed</option>
                <option value="price">Price</option>
            </select>
            <input type="text" class="form-control" name="word" placeholder="Search">
            <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button>  
        </div>
    </form>
</div>

<?php
    // connect to the database
    require '_database_connect.php';

    // Showing connections list
    if($total_connections>0){
        echo "
        <div class='container overflow-x-auto mb-5'>
            <div class='num_of_res text-light btn btn-dark m-2'>
                <h7 class='pt-2'>Total Result: $total_connections</h7>
            </div>
            <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Type</th>
                        <th>State</th>
                        <th>Speed (Mbps)</th>
                        <th>Price (TK)</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>";
            for($i=0; $i<$total_connections; $i++){
                // Get row
                $connections = mysqli_fetch_assoc($find_connections);

                //Get plan details
                $plan_sql = "SELECT * FROM `plans` WHERE `type` = '{$connections['type']}' AND `id` = '{$connections['plan_id']}'";
                $get_plan = mysqli_query($connect, $plan_sql);
                $plan = mysqli_fetch_assoc($get_plan);

                //Search Filter for Speed and Price
                if($key=="speed" && $word!=""){if ($plan['speed']!=$word){continue;}}
                if($key=="price" && $word!=""){if ($plan['price']!=$word){continue;}}

                //Get Plan type
                if($connections['type'] == "residential_plans"){
                    $type = "Residential Plans";
                } else if($connections['type'] == "organizational_plans"){
                    $type = "Organizational Plans";
                }

                echo"<tr>
                        <td>$connections[name]</td>
                        <td>$connections[address]</td>
                        <td>$type</td>
                        <td>$connections[state]</td>
                        <td>$plan[speed]</td>
                        <td>$plan[price]</td>
                        <td>
                            <form method='post'>
                                <input class='visually-hidden' type='text' name='con_id' value='$connections[id]'</input>
                                <button class='btn btn-success' type='submit' name='details'>Details</button>
                            </form>
                        </td>
                    </tr>";
            }
            echo"
                </tbody>
            </table>
        </div>";;
    }

    // Close the database connection
    mysqli_close($connect);

    //Footer
    include '_footer_common.php';

    // Season variable clear
    require '_unset_session_variable.php';
?>
</body>
</html>
