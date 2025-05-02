<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>
    <!-- Link End -->

</head>
<body>
<?php
    //Defining Page
    $page_name = "Home";

    //Navbar
    require '_nav_employee.php';

    // connect to the database
    require '_database_connect.php';

    // Get number of pending task
    $find_pending_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$_SESSION['id']}' AND `state` = 'Pending'";
    $find_pending_task = mysqli_query($connect, $find_pending_task_sql);
    $pending_task = mysqli_num_rows($find_pending_task);

    // Get number of complied task
    $find_completed_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$_SESSION['id']}' AND `state` = 'Completed'";
    $find_completed_task = mysqli_query($connect, $find_completed_task_sql);
    $completed_task = mysqli_num_rows($find_completed_task);

    // Get number of Expired task
    $find_expired_task_sql = "SELECT * FROM `task` WHERE `employee_id` = '{$_SESSION['id']}' AND `state` = 'late'";
    $find_expired_task = mysqli_query($connect, $find_expired_task_sql);
    $late_task = mysqli_num_rows($find_expired_task);

    // Close the database connection
    mysqli_close($connect);
?>

<div class="container">
    <div class="row justify-content-center my-5">

        <!-- Pending Task -->
        <div class="col-auto p-2">
            <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                <div class="card-side rounded-start" style="background-color: red"></div>
                
                <div class="card-text">
                    <h5><i class="fa-solid fa-list-check" style="color: #9d8189"></i>Pending Tasks</h5>

                    <p>On-Going Task: <?php echo "$pending_task"; ?></p>

                    <a class="btn btn-secondary rounded p-2" href="employee_tasks.php?task_type=Pending">Pending Tasks</a>
                </div>
            </div>
        </div>

        <!-- Completed Task -->
        <div class="col-auto p-2">
            <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                <div class="card-side rounded-start" style="background-color: green"></div>
                
                <div class="card-text">
                    <h5><i class="fa-solid fa-check"></i> Completed Tasks</h5>
                    
                    <p>Completed Task: <?php echo "$completed_task"; ?></p>
                    
                    <a class="btn btn-secondary rounded p-2" href="employee_tasks.php?task_type=Completed">Completed Tasks</a>
                </div>
            </div>
        </div>

        <!-- Expired Task -->
        <div class="col-auto p-2">
            <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                <div class="card-side rounded-start" style="background-color: black"></div>
                
                <div class="card-text">
                    <h5><i class="fa-solid fa-xmark"></i> Expired Tasks</h5>
                    
                    <p>Expired Task: <?php echo "$late_task"; ?></p>
                    
                    <a class="btn btn-secondary rounded p-2" href="employee_tasks.php?task_type=Late">Expired Tasks</a>
                </div>
            </div>
        </div>

        
        <?php
            // Charts

            // Data or chart
            $total_task = $completed_task + $late_task + $pending_task;

            $task_ratio = ($total_task > 0) ? round(($completed_task / $total_task) * 100, 2) : 0;
        ?>
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

        <!-- Comon js File For charts by chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
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

    </div>


</div>

<?php require '_footer_common.php';?>


</body>
</html>
