<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task List</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>
    <!-- Link End -->

</head>

<body>

<?php
    // connect to the database
    require '_database_connect.php';

    // Check if user the task type is set or not
    $task_type = "";
    if (isset($_GET['task_type'])){
        $task_type = mysqli_real_escape_string($connect, $_GET['task_type']);
    }
    else {
        echo "<script> window.location.href='dash_employee.php';</script>";
        die();
    }

    //Defining Page
    $page_name = $task_type." Tasks";

    $employee_id = mysqli_real_escape_string($connect, $_SESSION['id']);

    //Navbar
    require '_nav_employee.php';

    //if searched
    if(isset($_POST['search'])){
        $key = mysqli_real_escape_string($connect, $_POST['key']);
        $word = mysqli_real_escape_string($connect, $_POST['word']);
    }

    //If Re-Set
    if(isset($_POST['reset'])){
        $key = "all";
        $word = "";
    }
    
    //Searchbar function
    $find_task_sql = "SELECT
            task.id AS id, 
            task.name AS name, 
            task.address AS address, 
            task.state AS state, 
            task.end AS end,
            customer.phone AS phone
        FROM `task`
        LEFT JOIN `connections` ON task.task_ref = connections.id
        LEFT JOIN `customer` ON connections.customer_id = customer.id
        WHERE
            task.state = ?
            AND task.employee_id = ?";

    if(isset($key) && isset($word) && $word!=""){
        if($key=="all"){
            $find_task_sql .= " AND CONCAT(task.name, task.address, customer.phone, customer.name) LIKE ?";
        } else if($key=="title"){
            $find_task_sql .= " AND task.name LIKE ?";
        } else if($key=="address"){
            $find_task_sql .= " AND task.address LIKE ?";
        }
        
        // Prepare and execute query
        $word = "%{".$word."}%";
        $stmt = mysqli_prepare($connect, $find_task_sql." GROUP BY task.id");
        mysqli_stmt_bind_param($stmt, "sss", $task_type, $employee_id, $word);
    } else {
        // Prepare and execute query
        $stmt = mysqli_prepare($connect, $find_task_sql." GROUP BY task.id");
        mysqli_stmt_bind_param($stmt, "ss", $task_type, $employee_id);
    }

    mysqli_stmt_execute($stmt);
    $find_task = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);

    // Get the total number of tasks
    $total_task = mysqli_num_rows($find_task);

    // If Job done button clicked
    if (isset($_POST['job_done'])) {

        // Sanitize input
        $t_id = mysqli_real_escape_string($connect, $_POST['t_id']);

        // Get all needed info
        $insertion_info_sql = "SELECT 
                connections.id AS con_id, 
                connections.state AS con_state, 
                connections.starting_date AS s_date, 
                connections.customer_id AS cus_id,
                connections.req_plan AS req_plan,
                task.end AS e_date, 
                plans.price AS price 
            FROM task 
            JOIN connections ON task.task_ref = connections.id 
            JOIN plans ON connections.plan_id = plans.id 
            WHERE task.id = ?";

        $stmt = mysqli_prepare($connect, $insertion_info_sql);
        mysqli_stmt_bind_param($stmt, "s", $t_id);
        mysqli_stmt_execute($stmt);
        $insertion_result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
        $insertion = mysqli_fetch_assoc($insertion_result);

        $update_connection_sql = "";
        $cur_payments_sql = "";

        // Update connection based on task reference Connection in proces
        if ($insertion['con_state'] == "Connection in process") {
            $update_connection_sql = "UPDATE `connections` 
                SET `state` = 'Active',
                `starting_date` = '{$insertion['s_date']}' 
                WHERE `id` = '{$insertion['con_id']}'
            ";

            // Current month payments + service charge
            $insertion['price'] += 500;  //Service charge added
            $due_date = date('Y-m-d', strtotime('+5 days'));
            $cur_payments_sql = "INSERT INTO `bill` (`connection_id`, `amount`, `due_date`) 
                VALUES ('{$insertion['con_id']}', '{$insertion['price']}', '$due_date')";


        } elseif ($insertion['con_state'] == "Update in process") {
            $update_connection_sql = "UPDATE `connections` 
                SET
                    `state` = 'Active',
                    `plan_id` = '{$insertion['req_plan']}',
                    `req_plan` = NULL  
                WHERE `id` = '{$insertion['con_id']}'
            ";

        } elseif ($insertion['con_state'] == "Disconnection in process") {
            $update_connection_sql = "DELETE FROM `connections` WHERE `id` = '{$insertion['con_id']}'";
        }

        // Execute update query
        mysqli_query($connect, $update_connection_sql);

        // Insert payments if applicable
        if (!empty($cur_payments_sql)) {
            mysqli_query($connect, $cur_payments_sql);
        }

        // If the starting date is in the past, set it to today and mark as late
        $late = false;
        if (strtotime($insertion['e_date']) < strtotime(date('Y-m-d'))) {
            $insertion['e_date'] = date('Y-m-d');
            $late = true;
        }

        // Update task state
        $t_state = $late ? "Late" : "Completed";
        $up_task_state_sql = "UPDATE `task` SET `state` = '{$t_state}', `completed_at` = NOW() WHERE `id` = '$t_id'";
        mysqli_query($connect, $up_task_state_sql);

        // Close the database connection
        mysqli_close($connect);

        // Redirect to the credent link so that the user can not go back to the previous page
        echo '<script>window.location.replace("employee_tasks.php?task_type='.$task_type.'");</script>';
        exit("Task Complied Successfully!");
    }
    
?>

<div class="container my-3">
    <!-- Search bar with filter -->
    <form method="post">
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




<!-- Showing task list -->
<?php if($total_task>0): ?>
    <div class='container overflow-x-scroll mt-4'>
        <div class='num_of_res text-light btn btn-dark m-2'>
            <h7 class='pt-2'>Total Result: <?php echo $total_task; ?></h7>
        </div>

        <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Address</th>
                    <?php if($task_type != "Completed"): ?>
                        <th>Customer Number</th>
                    <?php endif; ?>
                    <th>State</th>
                    <th>Last Date</th>
                    <?php echo ($task_type == "Pending") ? "<th>Action</th>" : ""; ?>
                </tr>
            </thead>
            <tbody>
                <?php for($i=0; $i<$total_task; $i++):
                    $task = mysqli_fetch_assoc($find_task); ?>

                    <tr>
                        <td><?php echo htmlspecialchars($task['name']); ?></td>
                        <td><?php echo htmlspecialchars($task['address']); ?></td>
                        <?php if($task_type != "Completed"): ?>
                            <td><?php
                                if(isset($task['phone'])) echo htmlspecialchars($task['phone']);
                                else echo ""; ?>
                            </td>
                        <?php endif; ?>
                        <td><?php echo htmlspecialchars($task['state']); ?></td>
                        <td><?php echo htmlspecialchars($task['end']); ?></td>

                        <?php if ($task_type == "Pending"): ?>
                            <td>
                                <form method='post'>
                                    <input class='visually-hidden' type='text' name='t_id' value='<?php echo $task['id']; ?>'></input>
                                    <button type='submit' name='job_done' class='btn btn-success'><i class="fa-solid fa-square-check"></i> Job Done</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>

            </tbody>
        </table>
    </div>
<?php endif;


// Close the database connection
mysqli_close($connect); ?>

</body>
</html>
