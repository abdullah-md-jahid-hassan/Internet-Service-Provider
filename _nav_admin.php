
    <!-- Nab Bar Start -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-list-ul"></i>
            </button>
            
            <a class="navbar-brand nav-text-decoration fw-bold org-name " href="about.php"><?php echo $page_name;?></a>
            <a class="navbar-brand nav-text-decoration" href="admin_info.php" title="Profile"><i class="fa-regular fa-user nav-icon"></i></a>
            <a class="navbar-brand nav-text-decoration" href="logout.php" title="Logout"><i class="fa-solid fa-right-from-bracket nav-icon"></i></a>
            <a class="navbar-brand nav-text-decoration" href="customer_notification.php" title="Notification"><i class="fa-regular fa-bell  nav-icon"></i></a>
            
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php if($page_type == "home"){echo "active";}?>" aria-current="page" href="dash_admin.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle <?php if($page_type == "requests"){echo "active";}?>" id="requests" data-bs-toggle="dropdown" aria-expanded="false">
                            Requests
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="requests">
                            <li><a class="dropdown-item" href="new_connections.php">
                                <i class="fa-solid fa-plus"></i>
                                New Connections
                            </a></li>
                            <li><a class="dropdown-item" href="update_requests.php">
                                <i class="fa-solid fa-circle-up"></i>
                                Update Requests
                            </a></li>
                            <li><a class="dropdown-item" href="delete_requests.php">
                                <i class="fa-solid fa-trash-can"></i>
                                Delete Requests
                            </a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php if($page_type == "plans"){echo "active";}?>" id="plans" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Plans
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="plans">
                            <li><form method="post"><button class="dropdown-item" type="submit" name="residential_plans">
                                <i class="fa-solid fa-house-laptop"></i>
                                Residential Plans
                            </button></form></li>
                            <li><form method="post"><button class="dropdown-item" type="submit" name="organizational_plans">
                                <i class="fa-solid fa-building"></i>
                                Organizational plans
                            </button></form></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?php if($page_type == "reports"){echo "active";}?>" id="reports" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Reports
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="reports">
                            <li><a class="dropdown-item" href="payment_reports.php">
                                <i class="fa-solid fa-house"></i>
                                Payment Reports
                            </a></li>
                            <li><a class="dropdown-item" href="task_reports.php">
                                <i class="fa-solid fa-briefcase"></i>
                                Task Reports
                            </a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?php if($page_type == "manage_employee"){echo "active";}?>" href="manage_employee.php">Manage Employee</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  <?php if($page_type == "complaints"){echo "active";}?>" href="customer_complaints.php">Complaints</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Nab Bar End -->

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