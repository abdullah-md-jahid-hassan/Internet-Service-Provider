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

        //Variable
        $key = "all";
        $word = "";

        //Navbar
        require '_nav_admin.php';
    ?>

    <div class="container my-2">
        <!-- Search Bar -->
        <form method="get">
            <div class="input-group">
                <!-- Recruit Button -->
                <button type="submit" class="btn btn-success" name="recruit">
                    <i class="fa-solid fa-plus"></i> Recruit
                </button>
                
                <!-- Search bar with filter -->
                <select class="search-select" name="key" style="width: 20%;">
                    <option value="all" selected>Search By</option>
                    <option value="name">Name</option>
                    <option value="post">Post</option>
                    <option value="phone">Phone</option>
                    <option value="email">Email</option>
                    <option value="e-id">Employee-ID</option>
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
                $show_employee_sql = "SELECT * FROM `employee`";
            } else if($key=="all" && $word!=""){
                    $show_employee_sql = "SELECT * FROM `employee` WHERE CONCAT(name, post, phone, email, id) LIKE '%$word%'";
            } else if($key=="name" && $word!=""){
                $show_employee_sql = "SELECT * FROM `employee` WHERE `name` LIKE '%$word%'";
            } else if($key=="post" && $word!=""){
                $show_employee_sql = "SELECT * FROM `employee` WHERE `post` LIKE '%$word%'";
            } else if($key=="phone" && $word!=""){
                $show_employee_sql = "SELECT * FROM `employee` WHERE `phone` LIKE '%$word%'";
            } else if($key=="email" && $word!=""){
                $show_employee_sql = "SELECT * FROM `employee` WHERE `email` LIKE '%$word%'";
            } else if($key=="e-id" && $word!=""){
                $show_employee_sql = "SELECT * FROM `employee` WHERE `id` LIKE '%$word%'";
            } else {$show_employee_sql = "SELECT * FROM `employee`";}
        }

        //Query
        $run_show_employee = mysqli_query($connect, $show_employee_sql);
        $total_employee = mysqli_num_rows($run_show_employee);
                
        // Close the database connection
        mysqli_close($connect);

        // Showing connections list
        echo "
            <div class='container overflow-auto mt-4'>
                <div class='num_of_res text-light btn btn-dark m-2'>
                    <h7 class='pt-2'>Total Result: $total_employee</h7>
                </div>";
        if($total_employee>0){
            //Show the Employee List
            echo "
                <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Post</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Pending Task</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            ";
            for($i=0; $i<$total_employee; $i++){
                // connect to the database
                require '_database_connect.php';
                // Get row

                $employee = mysqli_fetch_assoc($run_show_employee);
                
                // Close the database connection
                mysqli_close($connect);
                
                echo "
                        <tr>
                            <td><img src='$employee[photo_file]' class='rounded-circle' alt='Employee Photo' style='width: 50px'></td>
                            <td>$employee[name]</td>
                            <td>$employee[post]</td>
                            <td>$employee[phone]</td>
                            <td>$employee[email]</td>
                            <td>task</td>
                            <td>
                                <form method='post'>
                                    <input class='visually-hidden' type='text' name='employee_id' value='$employee[id]'</input>
                                    <button class='btn btn-danger mb-1' type='submit' name='assign_task' style='width: 111px'>Assign Task</button>
                                    <button class='btn btn-success' type='submit' name='details' style='width: 111px'>Details</button>
                                </form>
                            </td>
                        </tr>
                ";
            }
            echo "
                    </tbody>
                </table>
            ";
        }
        echo"</div>";
        
        // if recruit button clicked
        if(isset($_GET['recruit'])) {
            // Rederection for recruit Page
            echo "<script> window.location.href='employee_recruit.php';</script>";
            // Close the database connection
            mysqli_close($connect);
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

        // if recruit button clicked
        if(isset($_GET['recruit'])) {
            // Rederection for recruit Page
            echo "<script> window.location.href='employee_recruit.php';</script>";
            // Close the database connection
            mysqli_close($connect);
            die();
        }

        // connect to the database
        require '_database_connect.php';
        // Close the database connection
        mysqli_close($connect);
    ?>

</body>
</html>