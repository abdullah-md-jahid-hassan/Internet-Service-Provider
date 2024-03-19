<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>

    
    <!-- Links Start -->

    <?php require '_common_link.php'; ?>

    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="footer.css">
        <!-- Link End -->

</head>
<body>

    <!-- Databas Connection -->
    <?php
        $inval_mass = "";
        if($_SERVER["REQUEST_METHOD"] == "POST") {

            require '_database_connect.php';
            
            $userid = $_POST["userid"];
            $pss = $_POST["pass"];
            $user = $_POST["user"];
            
            $login_sql = "SELECT * FROM `$user` WHERE `email` = '$userid' AND `password` = '$pss'";
            $login_check = mysqli_query($connect, $login_sql);
            $login_state = mysqli_num_rows($login_check);

            if ($login_state == 1){
                $login = true;
                session_start();
                $_SESSION['loggedin'] = true;
                $_SESSION['user' ] = $phone;
                $_SESSION['user' ] = $user;
                
                if($user=="customer"){ header("location: customer_dash.php"); }
                if($user=="admin"){ header("location: admin_dash.php"); }
                if($user=="employee"){ header("location: employee_dash.php"); }
            } else{$inval_mass = "Invalid User-name or Password";}
        }
    ?>

    <!-- Top Banner -->
    <div class="container text-center p-5 top_banner">
        <h1 class="page-title p-3">
            Welcome Back
            <span class="span-top"></span>
            <span class="span-right"></span>
            <span class="span-bottom"></span>
            <span class="span-left"></span>
        </h1>
    </div>

    <!-- Login Box -->
    <div class="login-box mb-5">
            <?php
                if($inval_mass != "") {echo '<p>' . $inval_mass . '</p>';}
            ?>
        <form autocomplete="on" action="login.php" method="post">
            <h2>Login</h2>
            <div class="user-input">
                <input type="email" name="userid" required>
                <label>Email</label>
            </div>
            <div class="user-input">
                <input type="password" name="pass" required>
                <label>Password</label>
            </div>

            <div class="position-relative bg-light">
                <div class="position-absolute top-0 end-0">
                    <a class="btn btn-danger" href="forgot-pass.php">Forgot Password?</a><br>
                </div>
            </div>

            <label class="text-light fs-6">Choose Your Role</label>
            <div class="form-check form-switch">
                <input class="form-check-input" type="radio" name="user" value="admin" id="user-admin">
                <label class="form-check-label text-light fs-7" for="user-admin">Admin</label>
            </div>
            <div class="form-check form-switch">
                <input class="form-check-input" type="radio" name="user" value="customer" id="user-customer" checked>
                <label class="form-check-label text-light fs-7" for="user-customer">Customer</label>
            </div> 
            <div class="form-check form-switch">
                <input class="form-check-input" type="radio" name="user" value="employee" id="user-employee">
                <label class="form-check-label text-light fs-7" for="user-employee">Employee</label>
            </div>



            <span class="login-span my-4">
                <span class="span-top"></span>
                <span class="span-right"></span>
                <span class="span-bottom"></span>
                <span class="span-left"></span>
                <input class="login-btn" type="submit" value="LOGIN">
            </span>

            <div class="position-relative bg-light mt-5">
                <div class="position-absolute top-0 end-0 mt-4">
                    <a class="btn btn-success" href="registration.php">Don't have account? Register your-self</a><br>
                </div>
            </div>
        </form>
    </div>

    <!-- Footer -->
    <?php require '_common_footer.php';?>
</body>
</html>