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
        require '_logincheck_admin.php';
        
        //Defining Page
        $page_type = "plans";
        if($_SESSION['plan_type'] == "residential_plans"){
            $page_name = "Residential Plans";
        } else if($_SESSION['plan_type'] == "organizational_plans"){
            $page_name = "Organizational Plans";
        }

        // if update button clicked
        if(isset($_POST['update'])) {
            // seting Seasion Value for Updte Page
            $_SESSION['plan_id'] = $_POST['plan_id'];
            echo "<script> window.location.href='plans_update.php';</script>";
            die();
        }

        // if add button clicked
        if(isset($_GET['add'])) {
            // Rederection for Add Page
            echo "<script> window.location.href='plans_add.php';</script>";
            die();
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

        //Navbar
        require '_nav_admin.php';
    ?>

    <div class="container my-3">
        <form action="plans_admin.php" method="get">
            <div class="input-group">
                <!-- Add Button -->
                <button type="submit" class="btn btn-success" name="add">
                    <i class="fa-solid fa-plus"></i> Add
                </button>
                
                <!-- Search bar with filter -->
                <select class="search-select" name="key" style="width: 20%;">
                    <option value="all" selected>Search By</option>
                    <option value="name">Name</option>
                    <option value="speed">Speed</option>
                    <option value="price">Price</option>
                    <option value="realip">Real-IP (Yes/No)</option>
                </select>
                <input type="text" class="form-control" name="word" placeholder="Search">
                <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button>  
                <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button>  
            </div>
        </form>
    </div>

    <?php
        // Showing result

        // connect to the database
        require '_database_connect.php';

        //sql
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

        // Fatching Data form database
        if($total_plans>0){
            for($i=0; $i<$total_plans; $i++){
                $plan = mysqli_fetch_assoc($run_show_plan);
                echo "<div class='container shadow-lg p-1 my-4 bg-dark rounded-3 text-light'>
                        <div class='row  py-2 my-2 d-flex justify-content-between'>
                            <div class='col-auto'>
                                <ul class='list-unstyled'>
                                    <li class='ms-3 connection_name fs-3 fw-bolder'>$plan[name]</li>
                                    <li class='ms-3 connection_address fs-4'><b>Speed:</b> $plan[speed] Mbps</li>
                                    <li class='ms-3 connection_status fs-5'><b>Real-IP:</b> $plan[realip]</li>
                                    <li class='ms-3 connection_plan fs-6'><b>Price:</b> $plan[price] tk/Month</li>
                                </ul>
                            </div>
                            <div class='col-auto mx-2'>
                                <div class='btn-group'>
                                    <form action='plans_admin.php' method='post'>
                                        <input type='text' class='visually-hidden' name='plan_id' value='$plan[id]'>
                                        <button type='submit' class='btn btn-success' name='update'><i class='fa-solid fa-up-down'></i> Update</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>";
        
            }
        }
        // Close the database connection
        mysqli_close($connect);
    ?>
</body>
</html>