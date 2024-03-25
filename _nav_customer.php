
    <!-- Nab Bar Start -->

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-list-ul"></i>
            </button>
            
            <a class="navbar-brand nav-text-decoration fw-bold org-name " href="about.php"><?php echo $page_name;?></a>
            <a class="navbar-brand nav-text-decoration" href="info_customer.php" title="Home"><i class="fa-regular fa-user nav-icon"></i></a>
            <a class="navbar-brand nav-text-decoration" href="logout.php" title="Logout"><i class="fa-solid fa-right-from-bracket nav-icon"></i></a>
            <a class="navbar-brand nav-text-decoration" href="customer_notification.php" title="Notification"><i class="fa-regular fa-bell  nav-icon"></i></a>
            
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link  <?php if($page_type == "home"){echo "active";}?>" aria-current="page" href="dash_customer.php">Home</a>
                    </li>

                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle  <?php if($page_type == "new_cnonnection"){echo "active";}?>" id="navbarDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            New Connection
                        </button>
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
                        <a class="nav-link  <?php if($page_type == "payment"){echo "active";}?>" href="pament.php">Payment</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link  <?php if($page_type == "customer_complaint"){echo "";}?>" href="customer_complaint.php">Complaint</a>
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
            echo "<script> window.location.href='plans_customer.php';</script>";
            die();
        } else if(isset($_POST['organizational_plans'])){
            $_SESSION['plan_type'] = "organizational_plans";
            echo "<script> window.location.href='plans_customer.php';</script>";
            die();
        }
    ?>