<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="navbar.css">
    <!-- Link End -->

</head>
<body>
    <?php
        //Defining Page
        $page_name = $_SESSION['task_type']." Tasks";

        //Navbar
        require '_nav_employee.php';

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
        
    ?>



    <div class="container my-3">
        <!-- Search bar with filter -->
        <form method="get">
            <div class="input-group">
                <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button>  
                <select class="search-select" name="key" style="width: 20%;">
                    <option value="all" selected>Search By</option>
                    <option value="title">Title</option>
                    <option value="address">Address</option>
                </select>
                <input type="text" class="form-control" name="word" placeholder="Search">
                <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button>  
            </div>
        </form>
    </div>



    <?php
        if (isset($_SESSION['task_type'])) $match = " AND  `state` = '{$_SESSION['task_type']}'"; else {
            echo "<script> window.location.href='dash_employee.php';</script>";
            die();
        }
        
        //Sarcbar function
        if(isset($key) && isset($word)){
            if($key=="all" && $word==""){
                $find_task_sql = "SELECT * FROM `task` WHERE `id` = '{$_SESSION['id']}' AND `state` = '{$_SESSION['task_type']}'";
            } else if($key=="all" && $word!=""){
                $find_task_sql = "SELECT * FROM `task` WHERE CONCAT(title, address, state) LIKE '%$word%'".$match;
            } else if($key=="title" && $word!=""){
                $find_task_sql = "SELECT * FROM `task` WHERE `title` LIKE '%$word%'".$match;
            } else if($key=="address" && $word!=""){
                $find_task_sql = "SELECT * FROM `task` WHERE `address` LIKE '%$word%'".$match;
            }
        } else {$find_task_sql = "SELECT * FROM `task` WHERE `state` = '{$_SESSION['task_type']}'";}

        // connect to the database
        require '_database_connect.php';

        //Query
        $find_task = mysqli_query($connect, $find_task_sql);
        $total_task = mysqli_num_rows($find_task);

        // Showing task list
        if($total_task>0){
            echo "
            <div class='container overflow-x-scroll mt-4'>
                <div class='num_of_res text-light btn btn-dark m-2'>
                    <h7 class='pt-2'>Total Result: $total_task</h7>
                </div>
                <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
                    <thead>
                        <tr>
                            <th>Titel</th>
                            <th>Address</th>
                            <th>Customer Number</th>
                            <th>State</th>
                            <th>Last Date</th>";
                            if ($_SESSION['task_type'] == "Pending"){
                                echo "<th>Action</th>";
                            }
                        echo "</tr>
                    </thead>
                    <tbody>";
                for($i=0; $i<$total_task; $i++){
                    $task = mysqli_fetch_assoc($find_task);

                    echo"<tr>
                            <td>$task[name]</td>
                            <td>$task[address]</td>
                            <td>$task[address]</td>
                            <td>$task[state]</td>
                            <td>$task[end]</td>
                            <td>
                                <form method='post'>
                                    <input class='visually-hidden' type='text' name='t_id' value='$task[id]'</input>";
                                    if ($_SESSION['task_type'] == "Pending"){
                                        echo "<button type='submit' name='job_done' class='btn btn-danger'>Job Done</button>";
                                    }
                                    
                                echo "</form>
                            </td>
                        </tr>";
                }
                echo"
                    </tbody>
                </table>
            </div>";
        }
        
        // Close the database connection
        mysqli_close($connect);

        // If Job done button clicked
        if (isset($_POST['job_done'])) {
            // Connect to the database
            require '_database_connect.php';

            // Sanitize input
            $t_id = mysqli_real_escape_string($connect, $_POST['t_id']);

            // Get all needed info
            $insertion_info_sql = "
                SELECT 
                    connections.id AS con_id, 
                    connections.state AS con_state, 
                    connections.starting_date AS s_date, 
                    connections.customer_id AS cus_id, 
                    plans.price AS price 
                FROM task 
                JOIN connections ON task.task_ref = connections.id 
                JOIN plans ON connections.plan_id = plans.id 
                WHERE task.id = '{$t_id}'
            ";

            $insertion_info = mysqli_query($connect, $insertion_info_sql);
            
            if (!$insertion_info) {
                die("Query Failed: " . mysqli_error($connect));
            }

            $insertion = mysqli_fetch_assoc($insertion_info);

            // Check if task is expire
            if (strtotime($insertion['s_date']) < strtotime(date('Y-m-d'))) {
                $insertion['s_date'] = date('Y-m-d');
                $late = true;
            } else {
                $late = false;
            }

            $update_connection_sql = "";
            $cur_payment_sql = "";

            // Update connection based on task reference
            if ($insertion['con_state'] == "Connection in process") {
                $update_connection_sql = "
                    UPDATE `connections` 
                    SET `state` = 'Active', `starting_date` = '{$insertion['s_date']}' 
                    WHERE `id` = '{$insertion['con_id']}'
                ";

                // Current month payment + service charge
                $insertion['price'] .= 500;         //Service charge added
                $cur_payment_sql = "
                    INSERT INTO `payment` (`connection_id`, `amount`) 
                    VALUES ('{$insertion['con_id']}', '{$insertion['price']}')
                ";

            } elseif ($insertion['con_state'] == "Update in process") {
                $update_connection_sql = "
                    UPDATE `connections` 
                    SET `state` = 'Active', `req_plan` = '' 
                    WHERE `id` = '{$insertion['con_id']}'
                ";

            } elseif ($insertion['con_state'] == "Disconnection in process") {
                $update_connection_sql = "
                    DELETE FROM `connections` 
                    WHERE `id` = '{$insertion['con_id']}'
                ";
            }

            // Execute update query
            if (!empty($update_connection_sql)) {
                if (!mysqli_query($connect, $update_connection_sql)) {
                    die("Update Query Failed: " . mysqli_error($connect));
                }
            }

            // Insert payment if applicable
            if (!empty($cur_payment_sql)) {
                if (!mysqli_query($connect, $cur_payment_sql)) {
                    die("Payment Insertion Failed: " . mysqli_error($connect));
                }
            }

            // Update task state
            $t_state = $late ? "Late" : "Completed";
            $up_task_state_sql = "UPDATE `task` SET `state` = '{$t_state}' WHERE `id` = '$t_id'";

            if (!mysqli_query($connect, $up_task_state_sql)) {
                die("Task State Update Failed: " . mysqli_error($connect));
            }

            // Close the database connection
            mysqli_close($connect);

            echo "<script> window.location.href='employee_tasks.php';</script>";
            die();
        }

    ?>

</body>
</html>