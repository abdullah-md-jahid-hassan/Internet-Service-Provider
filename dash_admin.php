<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="dash_admin.css">
    <!-- Link End -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>


</head>
<body>

<?php
    // Season variable clear
    require '_unset_session_variable.php';

    //Defining Page Type
    $page_type = "dashboard";
    $page_name = "dashboard";

    //Navbar
    require '_nav_admin.php';

    // connect to the database
    require '_database_connect.php';

    // Get number of customer
    $find_customer_sql = "SELECT * FROM `customer`";
    $find_customer = mysqli_query($connect, $find_customer_sql);
    $customer_num = mysqli_num_rows($find_customer);

    // Get number of Organizational Plans
    $find_organizational_plans_sql = "SELECT * FROM `plans` WHERE `type` = 'organizational_plans'";
    $find_organizational_plans = mysqli_query($connect, $find_organizational_plans_sql);
    $organizational_plans_num = mysqli_num_rows($find_organizational_plans);

    // Get number of Residential Plans
    $find_residential_plans_sql = "SELECT * FROM `plans` WHERE `type` = 'residential_plans'";
    $find_residential_plans = mysqli_query($connect, $find_residential_plans_sql);
    $residential_plans_num = mysqli_num_rows($find_residential_plans);

    // Get number of Connections
    $find_connections_sql = "SELECT 
            COUNT(*) AS total_connections,
            SUM(CASE WHEN type = 'residential_plans' THEN 1 ELSE 0 END) AS residential_connections
        FROM `connections`
        WHERE 1
    ";
    $find_connections = mysqli_query($connect, $find_connections_sql);
    $connections_result = mysqli_fetch_assoc($find_connections);
    $connections_num = $connections_result['total_connections'];
    $residential_connections_num = $connections_result['residential_connections'];
    $organizational_connections_num = $connections_num - $residential_connections_num;

    // Get New connection requests
    $find_new_connections_sql = "SELECT * FROM `connections`  WHERE `state` = 'Pending'";
    $find_new_connections = mysqli_query($connect, $find_new_connections_sql);
    $new_connections_num = mysqli_num_rows($find_new_connections);

    // GeT UPDATE requests
    $find_update_connections_sql = "SELECT * FROM `connections` WHERE `state` = 'Update pending'";
    $find_update_connections = mysqli_query($connect, $find_update_connections_sql);
    $update_connections_num = mysqli_num_rows($find_update_connections);

    // Get DELETE requests
    $find_disconnect_connections_sql = "SELECT * FROM `connections`  WHERE `state` = 'Disconnection pending'";
    $find_disconnect_connections = mysqli_query($connect, $find_disconnect_connections_sql);
    $disconnect_connections_num = mysqli_num_rows($find_disconnect_connections);

    // Get number of employee
    $find_employee_sql = "SELECT * FROM `employee`";
    $find_employee = mysqli_query($connect, $find_employee_sql);
    $employee_num = mysqli_num_rows($find_employee);

    // Get number of task
    $find_task_sql = "SELECT * FROM `task` WHERE `state` != 'Completed'";
    $find_task = mysqli_query($connect, $find_task_sql);
    $task_num = mysqli_num_rows($find_task);

    // Fetch complaints from the database
    $complaint_sql = "SELECT * FROM complaint WHERE 1";
    $find_complains = mysqli_query($connect, $complaint_sql);
    $complaint_num = mysqli_num_rows($find_complains);

    //Last 1 year revenue
    // Prepare date for last 6 months
    $months = [];
    for ($i = 11; $i >= 0; $i--) {
        $months[] = date('y-m', strtotime("-$i months"));
    }

    // Initialize revenue arrays
    $residential_revenue = array_fill(0, 12, 0);
    $organizational_revenue = array_fill(0, 12, 0);

    // Fetch all relevant revenue data for last 12 months
    $start_date = date('Y-m-01', strtotime('-11 months'));
    $sql = "SELECT `date`, `residential_plan`, `organizational_plan` FROM `revenue` WHERE `date` >= '$start_date'";
    $result = mysqli_query($connect, $sql);

    // Process data
    while ($row = mysqli_fetch_assoc($result)) {
        $date_key = date('y-m', strtotime($row['date']));
        $index = array_search($date_key, $months);
        if ($index !== false) {
            $residential_revenue[$index] += (int)$row['residential_plan'];
            $organizational_revenue[$index] += (int)$row['organizational_plan'];
        }
    }
    

    // Close the database connection
    mysqli_close($connect);
?>



    <div class="container">
        <div class="row justify-content-center my-5">

            <!-- Customers -->
            <div class="col-auto p-2">
                <div class="card-box card-2 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #bc6c25"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-people-line" style="color: #bc6c25"></i> Customers</h5>
                        <p>Total Customer: <?php echo "$customer_num"; ?></p>
                            <a class="btn btn-secondary rounded p-2" href="customer_list.php">Customers</a>
                    </div>
                </div>
            </div>

            <!-- Residential Plans -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: blue"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-house-laptop" style="color: blue"></i> Residential Plans</h5>
                        <p>Residential Plans: <?php echo "$residential_plans_num"; ?></p>
                        <form method="post">
                            <button class="btn btn-secondary rounded" type="submit" name="residential_plans">
                                See Plans
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Organizational plans -->
            <div class="col-auto p-2">
                <div class="card-box card-2 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #e09f3e"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-building" style="color: #e09f3e"></i> Organizational plans</h5>
                        <p>Organizational Plans: <?php echo "$organizational_plans_num"; ?></p>
                        <form method="post">
                            <button class="btn btn-secondary rounded p-2" type="submit" name="organizational_plans">
                                        See Plans
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Connections -->
            <div class="col-auto p-2">
                <div class="card-box card-2 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #003049"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-circle-nodes" style="color: #003049"></i> Connections</h5>
                        <p>Total Connections: <?php echo "$connections_num"; ?></p>
                        <a class="btn btn-secondary rounded p-2" href="connections_admin.php">Connections</a>
                    </div>
                </div>
            </div>

            <!-- New connections -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: green"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-plus" style="color: green"></i> Connections Requests</h5>
                        <p>Pending Connection Requests: <?php echo "$new_connections_num"; ?></p>
                        <form method="post">
                            <button class="btn btn-secondary" type="submit" name="new_connections">See Requests</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Connection Update -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #ffb703"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-circle-up" style="color: #ffb703"></i> Update Requests</h5>
                        <p>Pending Update Requests: <?php echo "$update_connections_num"; ?></p>
                        <form method="post">
                            <button class="btn btn-secondary" type="submit" name="update_connections">See Requests</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Connections -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: red"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-trash-can" style="color: red"></i> Disconnection Requests</h5>
                        <p>Disconnection Requests: <?php echo "$disconnect_connections_num"; ?></p>
                        <form method="post">
                            <button class="btn btn-secondary" type="submit" name="disconnect_connections">See Requests</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Employee -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #606c38"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-users-gear" style="color: #606c38"></i> Manage Employee</h5>
                        <p>Employees: <?php echo "$employee_num"; ?></p>
                        <a class="btn btn-secondary rounded p-2" href="employee.php">Employee</a>
                    </div>
                </div>
            </div>

            <!-- Task -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #9d8189"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-list-check" style="color: #9d8189"></i> Tasks</h5>
                        <p>Pending Task: <?php echo "$task_num"; ?></p>
                        <a class="btn btn-secondary rounded p-2" href="task_reports.php">Task list</a>
                    </div>
                </div>
            </div>

            <!-- Complaints -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #000000"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-not-equal" style="color: #000000"></i> Complaints</h5>
                        <p>Pending Complaints: <?php echo $complaint_num; ?></p>
                        <a class="btn btn-secondary rounded p-2" href="complaints.php">Complaints</a>
                    </div>
                </div>
            </div>

            <!-- Payment Reports -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #e63946"></div>
                    <div class="card-text">
                        <h5><i class="fa-solid fa-money-check-dollar" style="color: #e63946"></i> Payment Reports</h5>
                        <p>Pending Payment: 30</p>
                        <a class="btn btn-secondary rounded p-2" href="paymentreport.php">Reports</a>
                    </div>
                </div>
            </div>

            <!-- footer view control -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #8ac926"></div>
                    <div class="card-text">
                        <h5><i class="fa-regular fa-eye" style="color: #8ac926"></i> Customer View Control</h5>
                        <a class="btn btn-secondary rounded p-2" href="footer_view_control.php">Edit</a>
                    </div>
                </div>
            </div>

            <!-- Supper admin control -->
            <?php
                if ($_SESSION['user'] == "sup_admin"){
                    echo '
                    <div class="col-auto p-2">
                        <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                            <div class="card-side rounded-start" style="background-color:rgb(245, 9, 9)"></div>
                            <div class="card-text">
                                <h5><i class="fa-solid fa-user-tie" style="color: rgb(245, 9, 9)"></i> Supper Admin Control</h5>
                                <a class="btn btn-secondary rounded p-2" href="supper_admin_control.php">Supper Panel</a>
                            </div>
                        </div>
                    </div>';
                }
            ?>
        </div>

        
        <!-- Charts -->
        <div class="row row-cols-lg-2 row-cols-sm-1 justify-content-center mt-5">

            <!-- Bar chart for Revenue -->
            <div class="col-lg-8 col-sm-12 my-3">
                <div class="bg-light rounded p-3" style="width: 100%;">
                    <h5 class=" text-center"><b>Last 1 Year Revenue</b></h5>
                    <canvas id="area_revenue"></canvas>
                </div>
            </div>

            <!-- Doughnut-PI chart for plans -->
            <div class="col-lg-4 col-sm-12 my-3">
                <div class="bg-light rounded p-3" style="width: 100%;">
                    <h5 class=" text-center"><b>Connection Category Ratio</b></h5>
                    <h6 class=" text-center"><b>Total Connections: <?php echo $organizational_plans_num + $residential_plans_num; ?></b></h6>
                    <canvas id="doughnut_pi_plans"></canvas>
                </div>
            </div>

        </div>

    </div>

    <!-- Common js File For charts by chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Doughnut-PI chart for plans
        const d_pai = document.getElementById('doughnut_pi_plans');
        new Chart(d_pai, {
            type: 'doughnut',
            data: {
                labels: ['Residential Plan', 'Organizational plan'],
                datasets: [
                    {
                        data: [<?php echo $residential_connections_num; ?>, <?php echo $organizational_connections_num; ?>],
                        backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)'
                        ],
                        hoverOffset: 20
                    }
                ]
            }
        });


        // Convert PHP arrays to JavaScript arrays
        const $months = <?php echo json_encode($months); ?>;
        const $residential_revenue = <?php echo json_encode($residential_revenue); ?>;
        const $organizational_revenue = <?php echo json_encode($organizational_revenue); ?>;
        
        // bar chart for Revenue
        const bar = document.getElementById('area_revenue');
        new Chart(bar, {
            type: 'bar',
            data: {
                labels: $months,
                datasets: [
                    {
                        label: 'Residential Plan',
                        data: $residential_revenue,
                        backgroundColor: ['rgba(188, 108, 37, 0.5)'],
                        borderWidth: 1,
                        order:2
                    },
                    {
                        label: 'Organizational plan',
                        data: $organizational_revenue,
                        backgroundColor: ['rgba(42, 157, 143, 0.5)'],
                        borderWidth: 1,
                        order:2
                    }
                ]
            },
            options: { 
                scales: {
                    beginAtZero: true,
                    y: {
                        title: { 
                            display: true, 
                            text: 'Tk in K' 
                        } 
                    },
                    x: { 
                        title: {
                            display: true, 
                            text: 'Months' 
                        } 
                    } 
                }
            }
        });
    </script>

    <?php
        //Clear Session Variable
        include '_unset_session_variable.php';
    ?>

</body>
</html>
