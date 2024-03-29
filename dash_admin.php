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
    //Login check
    require '_logincheck_admin.php';

    //Defining Page Type
    $page_type = "dashboard";
    $page_name = "dashboard";

    //Navbar
    require '_nav_admin.php';
?>



    <div class="container d-grid">
        <div class="row justify-content-center my-5">

            <!-- Customers -->
            <div class="col-auto p-2">
                <div class="card-box card-2 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #bc6c25"></div>
                    <div class="card-text">
                        <h3><i class="fa-solid fa-people-line" style="color: #bc6c25"></i> Customers</h3>
                        <p>Total Active Customer: 20</p>
                            <a class="btn btn-secondary rounded p-2" href="customer_list.php">Customers</a>
                    </div>
                </div>
            </div>

            <!-- Residential Plans -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: blue"></div>
                    <div class="card-text">
                        <h3><i class="fa-solid fa-house-laptop" style="color: blue"></i> Residential Plans</h3>
                        <p>Total Active Residential Plans: 30</p>
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
                        <h3><i class="fa-solid fa-building" style="color: #e09f3e"></i> Organizational plans</h3>
                        <p>Total Active Organizational Plans: 20</p>
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
                        <h3><i class="fa-solid fa-circle-nodes" style="color: #003049"></i> Connections</h3>
                        <p>Total Active Connections Plans: 20</p>
                        <a class="btn btn-secondary rounded p-2" href="connections.php">Connections</a>
                    </div>
                </div>
            </div>

            <!-- New connections -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: green"></div>
                    <div class="card-text">
                        <h3><i class="fa-solid fa-plus" style="color: green"></i> New Connections</h3>
                        <p>Panding New Connection Requests: 30</p>
                        <form method="post">
                            <button class="btn btn-secondary rounded" type="submit" name="residential_plans">
                                See Requests
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Connection Update -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #ffb703"></div>
                    <div class="card-text">
                        <h3><i class="fa-solid fa-circle-up" style="color: #ffb703"></i> Update Requests</h3>
                        <p>Panding Update Requests: 30</p>
                        <form method="post">
                            <button class="btn btn-secondary rounded" type="submit" name="residential_plans">
                                See Requests
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Connections -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: red"></div>
                    <div class="card-text">
                        <h3><i class="fa-solid fa-trash-can" style="color: red"></i> Delete Requests</h3>
                        <p>Panding Delete Requests: 30</p>
                        <form method="post">
                            <button class="btn btn-secondary rounded" type="submit" name="residential_plans">
                                See Requests
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Employee -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #606c38"></div>
                    <div class="card-text">
                        <h3><i class="fa-solid fa-users-gear" style="color: #606c38"></i> Manage Employee</h3>
                        <p>Total Active Employees: 30</p>
                        <a class="btn btn-secondary rounded p-2" href="employee.php">Employee</a>
                    </div>
                </div>
            </div>

            <!-- Task -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #9d8189"></div>
                    <div class="card-text">
                        <h3><i class="fa-solid fa-list-check" style="color: #9d8189"></i> Tasks</h3>
                        <p>On-Going Task: 30</p>
                        <a class="btn btn-secondary rounded p-2" href="employee.php">Employee</a>
                    </div>
                </div>
            </div>

            <!-- Complaints -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #000000"></div>
                    <div class="card-text">
                        <h3><i class="fa-solid fa-not-equal" style="color: #000000"></i> Complaints</h3>
                        <p>Panding Complaints: 30</p>
                        <a class="btn btn-secondary rounded p-2" href="complaints.php">Complaints</a>
                    </div>
                </div>
            </div>

            <!-- Payment -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #e63946"></div>
                    <div class="card-text">
                        <h3><i class="fa-solid fa-money-check-dollar" style="color: #e63946"></i> Payment Reports</h3>
                        <p>Panding Payment: 30</p>
                        <a class="btn btn-secondary rounded p-2" href="paymentreport.php">Reports</a>
                    </div>
                </div>
            </div>

            <!-- Customer view control -->
            <div class="col-auto p-2">
                <div class="card-box card-1 text-bg-light rounded d-flex align-items-center">
                    <div class="card-side rounded-start" style="background-color: #8ac926"></div>
                    <div class="card-text">
                        <h3><i class="fa-regular fa-eye" style="color: #8ac926"></i> Customer View Control</h3>
                        <p>On-Going Task: 30</p>
                        <a class="btn btn-secondary rounded p-2" href="Cus_view_control.php">Edite</a>
                    </div>
                </div>
            </div>

        </div>

        
        <!-- Charts -->
        <div class="row row-cols-lg-2 row-cols-sm-1 justify-content-center mt-5">

            <!-- Bar chart for Revenue -->
            <div class="col-lg-8 col-sm-12">
                <div class="bg-light rounded p-3" style="width: 100%;">
                    <h5 class=" text-center"><b>Last 1 Year Revenue</b></h5>
                    <canvas id="area_revenue"></canvas>
                </div>
            </div>

            <!-- Doughnut-PI chart for plans -->
            <div class="col-lg-4 col-sm-12">
                <div class="bg-light rounded p-3" style="width: 100%;">
                    <h5 class=" text-center"><b>Connection Catagory Ratio</b></h5>
                    <h6 class=" text-center"><b>Total Connections: 110</b></h6>
                    <canvas id="doughnut_pi_plans"></canvas>
                </div>
            </div>

        </div>

        <!-- Charts -->
        <div class="row justify-content-center mt-3">
            
            <!-- Line Chart for Customer And Connections -->
            <div class="col-12">
                <div class="bg-light rounded p-3" style="width: 100%;">
                    <h5 class=" text-center"><b>1 year Growth Of Customer and Connections</b></h5>
                    <canvas id="line_customer_and_connection"></canvas>
                </div>
            </div>

        </div>

    </div>

    <!-- Comon js File For charts by chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Line Chart for Customer And Connections
        const line = document.getElementById('line_customer_and_connection');
        new Chart(line, {
            type: 'line',
            data: {
                labels: ['07-23', '08-23', '09-23', '10-23', '11-23', '12-23', '01-24', '02-24', '03-24', '04-24', '05-24', '06-24'],
                datasets: [
                    {
                        label: 'Customers',
                        data: [15, 30, 25, 40, 55, 100, 150, 250, 350, 330, 375, 413],
                        borderColor: 'rgb(0, 255, 0)',
                    },
                    {
                        label: 'Connections',
                        data: [10, 35, 37, 50, 45, 90, 102, 157, 170, 210, 203, 256],
                        borderColor: 'rgb(0, 0, 255)',
                    }
                ]
            },
            options: { 
                scales: {
                    beginAtZero: true,
                    y: {  
                        title: { 
                            display: true, 
                            text: 'Count Number Unit' 
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


        // Doughnut-PI chart for plans
        const d_pai = document.getElementById('doughnut_pi_plans');
        new Chart(d_pai, {
            type: 'doughnut',
            data: {
                labels: ['Total Connections', 'Residential Plan', 'Organizational plan'],
                datasets: [
                    {
                        data: [0, 50, 60],
                        backgroundColor: [
                            'rgb(0, 255, 255)',
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)'
                        ],
                        hoverOffset: 20
                    },
                    {
                        label: 'Customers',
                        data: [110, 0, 0],
                        backgroundColor: [
                            'rgb(0, 255, 255)',
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)'
                        ],
                        hoverOffset: 20
                    }
                ]
            }
        });


        // bar chart for Revenue
        const bar = document.getElementById('area_revenue');
        new Chart(bar, {
            type: 'bar',
            data: {
                labels: ['01-24', '02-24', '03-24', '04-24', '05-24', '06-24'],
                datasets: [
                    {
                        label: 'Residential Plan',
                        data: [15, 30, 25, 40, 55, 100],
                        backgroundColor: ['rgba(188, 108, 37, 0.5)'],
                        borderWidth: 1,
                        order:2
                    },
                    {
                        label: 'Organizational plan',
                        data: [25, 32, 27, 39, 45, 77],
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
                            text: 'Tk in K (x1000)' 
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

</body>
</html>