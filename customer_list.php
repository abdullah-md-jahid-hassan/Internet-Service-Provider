<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer list</title>
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
    $page_type = "customers_list";
    $page_name = "Customers List";

    //Navbar
    require '_nav_admin.php';

    // connect to the database
    require '_database_connect.php';

    //Sarcbar function
    if(isset($key) && isset($word)){
        if($key=="all" && $word==""){
            $find_customer_sql = "SELECT * FROM `customer`";
        } else if($key=="all" && $word!=""){
            $find_customer_sql = "SELECT * FROM `{$_SESSION['plan_type']}` WHERE CONCAT(name, speed, price, realip) LIKE '%$word%'";
        } else if($key=="name" && $word!=""){
            $find_customer_sql = "SELECT * FROM `{$_SESSION['plan_type']}` WHERE `name` LIKE '%$word%'";
        } else if($key=="speed" && $word!=""){
            $find_customer_sql = "SELECT * FROM `{$_SESSION['plan_type']}` WHERE `speed` LIKE '$word'";
        } else if($key=="price" && $word!=""){
            $find_customer_sql = "SELECT * FROM `{$_SESSION['plan_type']}` WHERE `price` LIKE '$word'";
        } else if($key=="realip" && $word!=""){
            $find_customer_sql = "SELECT * FROM `{$_SESSION['plan_type']}` WHERE `realip` LIKE '$word'";
        }
    } else {$find_customer_sql = "SELECT * FROM `{$_SESSION['plan_type']}`";}

    //Query
    $run_show_plan = mysqli_query($connect, $find_customer_sql);
    $total_plans = mysqli_num_rows($run_show_plan);
    
    // Close the database connection
    mysqli_close($connect);
?>

    <div class="container my-3">
        <!-- Search bar with filter -->
        <form method="get">
            <div class="input-group">
                <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button>  
                <select class="search-select" name="key" style="width: 20%;">
                    <option value="all" selected>Search By</option>
                    <option value="name">Name</option>
                    <option value="address">Address</option>
                    <option value="phone">Phone</option>
                    <option value="email">Email</option>
                </select>
                <input type="text" class="form-control" name="word" placeholder="Search">
                <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button>  
            </div>
        </form>
    </div>


</body>
</html>