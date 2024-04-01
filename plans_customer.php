<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plans</title>
    <!-- Links Start -->
    <?php require '_link_common.php'; ?>

    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <!-- Link End -->

</head>
<body>
    <?php
        //Login check
        require '_logincheck_customer.php';
        
        //Defining Page
        $page_type = "new_cnonnection";
        if($_SESSION['plan_type'] == "residential_plans"){
            $page_name = "Residential Plans";
        } else if($_SESSION['plan_type'] == "organizational_plans"){
            $page_name = "Organizational Plans";
        }

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

        // if Request for connection
        if(isset($_POST['new_plan'])) {
            // seting Seasion Value for Updte Page
            $_SESSION['plan_id'] = $_POST['plan_id'];
            echo "<script> window.location.href='request_connection_customer.php';</script>";
            die();
        }

        //Navbar
        require '_nav_customer.php';
    ?>

    <div class="container my-3">
        <form method="get">
            <div class="input-group">
                <!-- Search bar with filter -->
                <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button>  
                <select class="search-select" name="key" style="width: 20%;">
                    <option value="all" selected>Search By</option>
                    <option value="name">Name</option>
                    <option value="speed">Speed</option>
                    <option value="price">Price</option>
                    <option value="realip">Real-IP (Yes/No)</option>
                </select>
                <input type="text" class="form-control" name="word" placeholder="Search">
                <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button>  
            </div>
        </form>
    </div>

    <?php
        // Showing result

        // connect to the database
        require '_database_connect.php';

        //sql logic for the Search And Defalt
        if(isset($key) && isset($word)){
            if($key=="all" && $word==""){
                $show_plans_sql = "SELECT * FROM `{$_SESSION['plan_type']}`";
            } else if($key=="all" && $word!=""){
                $show_plans_sql = "SELECT * FROM `{$_SESSION['plan_type']}` WHERE CONCAT(name, speed, price, realip) LIKE '%$word%'";
            } else if($key=="name" && $word!=""){
                $show_plans_sql = "SELECT * FROM `{$_SESSION['plan_type']}` WHERE `name` LIKE '%$word%'";
            } else if($key=="speed" && $word!=""){
                $show_plans_sql = "SELECT * FROM `{$_SESSION['plan_type']}` WHERE `speed` LIKE '$word'";
            } else if($key=="price" && $word!=""){
                $show_plans_sql = "SELECT * FROM `{$_SESSION['plan_type']}` WHERE `price` LIKE '$word'";
            } else if($key=="realip" && $word!=""){
                $show_plans_sql = "SELECT * FROM `{$_SESSION['plan_type']}` WHERE `realip` LIKE '$word'";
            }
        } else {$show_plans_sql = "SELECT * FROM `{$_SESSION['plan_type']}`";}

        //Query
        $run_show_plan = mysqli_query($connect, $show_plans_sql);
        $total_plans = mysqli_num_rows($run_show_plan);

        echo"
    <div class='container mt-4'>
        <h7 class='num_of_res text-light btn btn-dark pt-2'>Total Result: $total_plans</h7>
    </div>";

        // Fatching Data form database
        if($total_plans>0){
            echo"
    <!-- Plans -->
    <div class='row row-cols-auto justify-content-center'>";
    for($i=0; $i<$total_plans; $i++){
        $plan = mysqli_fetch_assoc($run_show_plan);
        echo "
    <!-- Print Each Plan -->
    <div class='row-auto bg-dark text-light p-4 rounded m-2' style='width: 400px'>
            <h3 class='font-weight-bolder'>$plan[name]</h3>
            <p class='fs-5 mt-2'>
            <b>Speed: </b>$plan[speed]<br>
            <b>Real-IP: </b>$plan[realip]<br>
            <b>Price: </b>$plan[price]<br>
        </p>
            <form action='plans_admin.php' method='post'>
            <input type='text' class='visually-hidden' name='plan_id' value='$plan[id]'>
            <button type='submit' class='btn btn-success' name='new_connection'><i class='fa-solid fa-plus'></i> Request A New Connection</button>
        </form>
    </div>";
    }
        echo "
    </div>";
            // Close the database connection
            mysqli_close($connect);
        }
    ?>
</body>
</html>