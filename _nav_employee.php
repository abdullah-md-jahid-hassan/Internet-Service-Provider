    <!-- Navbar Satrat -->
    <nav class="navbar navbar-expand-md bg-dark sticky-top rounded" data-bs-theme="dark">
        <div class="container-fluid d-flex justify-content-between">

            <!-- Toggler button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-links" aria-controls="nav-links" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-list-ul"></i>
            </button>

            <!-- Page Name -->
            <a class="navbar-brand" href="about.php"><?php echo $page_name;?></a>

            <!-- Nav Icons -->
            <div class="nav-icon">
                <a class="navbar-brand px-3" href="customer_notification.php" title="Notification"><i class="fa-regular fa-bell nav-icon"></i></a>
                <a class="navbar-brand px-3" href="admin_info.php" title="Profile"><i class="fa-regular fa-user nav-icon"></i></a>
                <a class="navbar-brand px-3" href="logout.php" title="Logout"><i class="fa-solid fa-right-from-bracket nav-icon"></i></a>
            </div>

            <!-- Navbar-links -->
            <div class="collapse navbar-collapse" id="nav-links">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link  <?php if($page_type == "home"){echo "op-ac";}?>" aria-current="page" href="dash_customer.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle  <?php if($page_type == "new_cnonnection"){echo "op-ac";}?>" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            New Connection
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><form method="post"><button class="dropdown-item" type="submit" name="residential_plans">
                                <i class="fa-solid fa-house"></i>
                                Residential
                            </button></form></li>
                            <li><form method="post"><button class="dropdown-item" type="submit" name="organizational_plans">
                                <i class="fa-solid fa-briefcase"></i>
                                Organizational
                            </button></form></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><form method="post"><button class="dropdown-item disabled" type="submit" name="">
                                <i class="fa-solid fa-gears"></i>
                                Custom Plan
                            </button></form></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  <?php if($page_type == "payment"){echo "op-ac";}?>" href="pament.php">Payment</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  <?php if($page_type == "customer_complaint"){echo "opac";}?>" href="customer_complaint.php">Complaint</a>
                    </li>
                </ul>
            </div>


        </div>
    </nav>
    <!-- NavBar End -->








<!-- Nab Bar Start -->

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-list-ul"></i>
            </button>
            
            <a class="navbar-brand nav-text-decoration fw-bold org-name " href="about.php">Family ISP</a>
            <a class="navbar-brand nav-text-decoration" href="customer_info.php" title="Logout"><i class="fa-regular fa-user nav-icon"></i></a>
            <a class="navbar-brand nav-text-decoration" href="logout.php" title="Profile"><i class="fa-solid fa-right-from-bracket nav-icon"></i></i></a>
            <a class="navbar-brand nav-text-decoration" href="customer_notification.php" title="Notification"><i class="fa-regular fa-bell  nav-icon"></i></a>
            
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dash_employee.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            New Connection
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="new_residential_connection.php">
                                <i class="fa-solid fa-house"></i>
                                Residential
                            </a></li>
                            <li><a class="dropdown-item" href="new_organizational_connection.php">
                                <i class="fa-solid fa-briefcase"></i>
                                Organizational
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="new_custom_connection.php">
                                <i class="fa-solid fa-gears"></i>
                                Custom Plan
                            </a></li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="pament.php">Payment</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="customer_complaint.php">Complaint</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Nab Bar End -->