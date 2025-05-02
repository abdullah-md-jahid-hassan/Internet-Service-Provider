<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Details</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="customer_details.css">
    <!-- Link End -->

</head>
<body>
    <?php
        $employee_id = $_SESSION["id"];

        //Defining Page
        $page_type = $page_name = "Profile";
        
        //Navbar
        require '_nav_admin.php';

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

        // if Assign task button clicked
        if(isset($_GET['assign_task'])) {
            // Rederection for assign_task Page
            $_SESSION['employee_id_task'] = $employee_id;
            echo "<script> window.location.href='assign_task.php';</script>";
            die();
        }

        // connect to the database
        require '_database_connect.php';

        //getting Employee Info
        $find_employee_sql = "SELECT * FROM `employee` WHERE `id` = '{$employee_id}'";
        $find_employee = mysqli_query($connect, $find_employee_sql);
        $employee = mysqli_fetch_assoc($find_employee);

        //Sarcbar function
        if(isset($key) && isset($word)){
            if($key=="all" && $word==""){
                $find_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$employee_id}'";
            } else if($key=="all" && $word!=""){
                $find_task_sql = "SELECT * FROM `task` WHERE CONCAT(name, address, state) LIKE '%$word%' AND `employee_id` = '{$employee_id}'";
            } else if($key=="title" && $word!=""){
                $find_task_sql = "SELECT * FROM `task` WHERE `name` LIKE '%$word%' AND `employee_id` = '{$employee_id}'";
            } else if($key=="address" && $word!=""){
                $find_task_sql = "SELECT * FROM `task` WHERE `address` LIKE '%$word%' AND `employee_id` = '{$employee_id}'";
            } else if($key=="status" && $word!=""){
                $find_task_sql = "SELECT * FROM `task` WHERE `state` LIKE '%$word%' AND `employee_id` = '{$employee_id}'";
            }
        } else {$find_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$employee_id}'";}

        // Find the task number
        $find_task = mysqli_query($connect, $find_task_sql);
        $total_task_to_show = mysqli_num_rows($find_task);

        // Find Task Ratio
        $total_task =  mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `task` WHERE `employee_id` = '{$employee_id}'"));

        $completed_task = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `task` WHERE `employee_id` = '{$employee_id}' AND `state` = 'completed'"));

        $task_ratio = ($total_task > 0) ? round(($completed_task / $total_task) * 100, 2) : 0;

        // Close the database connection
        mysqli_close($connect);
    ?>

    <div class="container">
        <div class="row row-cols-auto bg-light rounded justify-content-center my-4">
            <!-- profile picture -->
            <div class="col-auto m-2">
                <img class="pp_img" src="<?php echo $employee['photo_file']?>" alt="Profile Picture">
            </div>
            <!-- Details -->
            <div class="col-auto m-2 ms-md-3">
                <div class="bg-dark text-light rounded pb-1 pt-2 px-3 text-center"><h2><b><?php echo $employee["name"]?></b></h2></div>
                <div class="details fs-5 mt-4">
                    <p>
                        <b>Post: </b><?php echo $employee['post'];?><br>
                        <b>Address: </b><?php echo $employee['address'];?><br>
                        <b>Email: </b><?php echo $employee['email'];?><br>
                        <b>Phone: </b><?php echo $employee['phone'];?><br>
                        <b>NID: </b><?php echo $employee['nid'];?><br>
                        <b>Salary: </b><?php echo $employee['salary'];?>
                    </p>
                    
                    <!-- NID button -->
                    <div class='btn-group'>
                        <a class='btn btn-info' href='<?php echo $employee['nid_file'];?>' target='_blank'><i class="fa-solid fa-eye"></i> NID</a>

                        <a class='btn btn-warning' href='<?php echo $employee['nid_file'];?>' target='_blank' download><i class="fa-solid fa-download"></i></a>
                    </div>

                    <!-- CV button -->
                    <div class='btn-group'>
                        <a class='btn btn-info' href='<?php echo $employee['resume_file'];?>' target='_blank'><i class="fa-solid fa-eye"></i> CV</a>

                        <a class='btn btn-warning' href='<?php echo $employee['resume_file'];?>' target='_blank' download><i class="fa-solid fa-download"></i></a>
                    </div>

                    <!-- Certificate button -->
                    <div class='btn-group'>
                        <a class='btn btn-info' href='<?php echo $employee['certificate_file'];?>' target='_blank'><i class="fa-solid fa-eye"></i> Certificate</a>

                        <a class='btn btn-warning' href='<?php echo $employee['certificate_file'];?>' target='_blank' download><i class="fa-solid fa-download"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- task Section -->
    <div class='container'>
        <div>
            <h5 class='p-2 bg-info rounded'>Total Assigned Task: <?php echo $total_task_to_show;?></h5>
        </div>

        <div class="container">
            <!-- Search bar with filter -->
            <form method="get">
                <div class="input-group">
                    <!-- assign_task Button -->
                    <button type="submit" class="btn btn-success" name="assign_task">
                        <i class="fa-solid fa-plus"></i> Assign Task    
                    </button>
                    <select class="search-select" name="key" style="width: 20%;">
                        <option value="all" selected>Search By</option>
                        <option value="title">Title</option>
                        <option value="address">Address</option>
                        <option value="status">Status</option>
                    </select>
                    <input type="text" class="form-control" name="word" placeholder="Search">
                    <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button> 
                    <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button>  

                </div>
            </form>
        </div>

        <?php
            // Showing task list
            if($total_task_to_show>0){
                echo "
                <div class='container overflow-x-scroll'>
                    <div class='num_of_res text-light btn btn-dark m-1'>
                        <h7 class='pt-2'>Total Result: $total_task_to_show</h7>
                    </div>
                    <table class='table table-info table-striped table-hover m-1 p-2 text-center'>
                        <thead>
                            <tr>
                                <th>Titel</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Last Date</th>
                            </tr>
                        </thead>
                        <tbody>";
                    for($i=0; $i<$total_task_to_show; $i++){
                        $task = mysqli_fetch_assoc($find_task);

                        echo"<tr>
                                <td>$task[name]</td>
                                <td>$task[address]</td>
                                <td>$task[state]</td>
                                <td>$task[end]</td>
                            </tr>";
                    }
                    echo"
                        </tbody>
                    </table>
                </div>";
            }
        ?>

        <!-- Charts -->
        <div class="row justify-content-center mt-5">
            <!-- Doughnut-PI chart for plans -->
            <div class="col-lg-6 col-sm-12 my-3">
                <div class="bg-light rounded p-3" style="width: 100%;">
                    <h5 class=" text-center"><b>Task Report</b></h5>
                    <h6 class=" text-center">
                        <b>Total: <?php echo"$total_task"?><br>
                        Sacsess Ratio: <?php echo"$task_ratio"?>%</b>
                    </h6>
                    <canvas id="doughnut_pi_plans"></canvas>
                </div>
            </div>
        </div>
    </div>

    

    <!-- Comon js File For charts by chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Data forDoughnut-PI chart for plans
        <?php
            // connect to the database
            require '_database_connect.php';

            $late_task = mysqli_num_rows(mysqli_query($connect, "SELECT * FROM `task` WHERE `employee_id` = '{$employee_id}' AND `state` = 'late'"));

            $pending_task = $total_task - $completed_task - $late_task;

            // Close the database connection
            mysqli_close($connect);
        ?>

        // Doughnut-PI chart for plans
        const d_pai = document.getElementById('doughnut_pi_plans');
        new Chart(d_pai, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Late', 'Pending'],
                datasets: [
                    {
                        data: [<?= $completed_task ?>, <?= $late_task ?>, <?= $pending_task ?>],
                        backgroundColor: [
                            'rgb(0, 255, 0)',
                            'rgb(255, 0, 0)',
                            'rgb(0, 0, 255)'
                        ],
                        hoverOffset: 15
                    }
                ]
            }
        });
    </script>
    
</body>
</html>
