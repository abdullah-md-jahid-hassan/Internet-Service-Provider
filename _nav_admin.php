
    <?php
        //Plans Rederection PHP
        if(isset($_POST['residential_plans'])){
            $_SESSION['plan_type'] = "residential_plans";
            echo "<script> window.location.href='plans_admin.php';</script>";
            die();
        } else if(isset($_POST['organizational_plans'])){
            $_SESSION['plan_type'] = "organizational_plans";
            echo "<script> window.location.href='plans_admin.php';</script>";
            die();
        }
    ?>



    <!-- Navbar Satrat -->
    <nav class="navbar bg-dark sticky-top rounded" data-bs-theme="dark">
        <div class="container-fluid d-flex justify-content-between">

            <!-- Button to open Sidebar -->
            <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas" data-bs-target="#dashboard-sidebar" aria-controls="dashboard-sidebar" title="Dashboard">
                <i class="fa-solid fa-angles-right"></i>
            </button>
            <a class="navbar-brand" href="about.php"><?php echo $page_name;?></a>
            <div class="nav-icon">
                <a class="navbar-brand px-3" href="customer_notification.php" title="Notification"><i class="fa-regular fa-bell nav-icon"></i></a>
                <a class="navbar-brand px-3" href="admin_info.php" title="Profile"><i class="fa-regular fa-user nav-icon"></i></a>
                <a class="navbar-brand px-3" href="logout.php" title="Logout"><i class="fa-solid fa-right-from-bracket nav-icon"></i></a>
            </div>
        </div>
    </nav>
    <!-- NavBar End -->


    <!-- Sidebar/offcanvas start -->
    <div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="dashboard-sidebar" aria-labelledby="dashboard-head">
    
        <!-- Sidebar Header -->
        <div class="offcanvas-header ms-2">
            <h3 class="offcanvas-title font-weight-bolder" id="dashboard-head"><i class="fa-solid fa-gauge"></i> DASHBOARD</h3>
            <button type="button" class="btn btn-dark position-absolute top-0 end-0 m-2 text-light" data-bs-dismiss="offcanvas" title="Close"><i class="fa-solid fa-angles-left"></i> </button>
        </div>
        
        <!-- slide bar boddy -->
        <div class="offcanvas-body ms-2">
        
            <div class="offcanvas-options fs-5">
                <ul class="list-unstyled">
                    <li class="">
                        <a class="dropdown-item rounded p-2" href="dash_admin.php"><i class="fa-solid fa-house"></i> Home</a>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle dropdown-item rounded p-2" id="requests" data-bs-toggle="dropdown">
                            <i class="fa-regular fa-hand"></i> Requests
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="requests">
                            <li><a class="dropdown-item rounded p-2" href="new_connections.php">
                                <i class="fa-solid fa-plus"></i> 
                                New Connections
                            </a></li>
                            <li><a class="dropdown-item rounded p-2" href="update_requests.php">
                                <i class="fa-solid fa-circle-up"></i> 
                                Update Requests
                            </a></li>
                            <li><a class="dropdown-item rounded p-2" href="delete_requests.php">
                                <i class="fa-solid fa-trash-can"></i> 
                                Delete Requests
                            </a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle dropdown-item rounded p-2" id="plans" data-bs-toggle="dropdown"><i class="fa-solid fa-clipboard-list"></i> Plans</a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="plans">
                            <li><form method="post"><button class="dropdown-item rounded p-2" type="submit" name="residential_plans">
                                <i class="fa-solid fa-house-laptop"></i> 
                                Residential Plans
                            </button></form></li>
                            <li><form method="post"><button class="dropdown-item rounded p-2" type="submit" name="organizational_plans">
                                <i class="fa-solid fa-building"></i> 
                                Organizational plans
                            </button></form></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="dropdown-toggle dropdown-item rounded p-2" id="reports" data-bs-toggle="dropdown">
                            <i class="fa-regular fa-flag"></i> Reports
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="reports">
                            <li><a class="dropdown-item rounded p-2" href="payment_reports.php">
                                <i class="fa-solid fa-house"></i> 
                                Payment Reports
                            </a></li>
                            <li><a class="dropdown-item rounded p-2" href="task_reports.php">
                                <i class="fa-solid fa-briefcase"></i> 
                                Task Reports
                            </a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="dropdown-item rounded p-2" href="manage_employee.php"><i class="fa-solid fa-users-gear"></i> Manage Employee</a>
                    </li>

                    <li>
                        <a class="dropdown-item rounded p-2 op-ac" href="customer_complaints.php"><i class="fa-solid fa-not-equal"></i> Complaints</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Sidebar/Offcanvas -->