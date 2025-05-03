<?php
    session_start();

    $massage = "";
    $error = false;
    $hold_value = false;
    $invalid_nid = "";
    $invalid_phone = "";
    $invalid_email = "";
    $invalid_pass = "";
    $photo_file_error = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        require '_database_connect.php';

        $hold_value = true;

        $name = $_POST["name"];
        $address = $_POST["address"];
        $nid = $_POST["nid"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $gender = $_POST["gender"];
        $pass = $_POST["pass"];
        $repass = $_POST["repass"];

        // Photo file Error
        //Allowed photo file types and maximum file size
        $photo_file_allowed_type = array("jpg", "jpeg", "png");
        $photo_file_max_size = 2 * 1048576; // 2MB
        // Get file details
        $photo_file_name = $_FILES['photo']['name'];
        $photo_file_size = $_FILES['photo']['size'];
        $photo_file_type = strtolower(pathinfo($photo_file_name, PATHINFO_EXTENSION));
        // Check file type
        if (!in_array($photo_file_type, $photo_file_allowed_type)) {
            $photo_file_error = "Only JPG, JPEG, and PNG files are allowed.";
            $error = true;
        }
        // Check file size
        if ($photo_file_max_size <= $photo_file_size) {
            $photo_file_error = "File size exceeds the maximum limit of 2MB.";
            $error = true;
        }

        $nid_get = "SELECT * FROM `customer` WHERE `nid` = '$nid'";
        $phone_get = "SELECT * FROM `customer` WHERE `phone` = '$phone'";
        $email_get = "SELECT * FROM `customer` WHERE `email` = '$email'";

        $nid_check = mysqli_query($connect, $nid_get);
        $nid_state = mysqli_num_rows($nid_check);
        if(strlen($nid)!=10){
            $invalid_nid = "Enter a valid NID number.";
            $error=true;
        }
        else if($nid_state>0){
            $invalid_nid = "The NID already exist.";
            $error=true;
        }

        $phone_check = mysqli_query($connect, $phone_get);
        $phone_state = mysqli_num_rows($phone_check);
        if(strlen($phone)!=11){
            $invalid_phone = "Enter a valid Phone number.";
            $error=true;
        }
        else if($phone_state>0){
            $invalid_phone = "The Phone number already exist.";
            $error=true;
        }

        $email_check = mysqli_query($connect, $email_get);
        $email_state = mysqli_num_rows($email_check);
        if($email_state>0){
            $invalid_email = "The Email already exist.";
            $error=true;
        }

        if($pass!=$repass){
            $invalid_pass = "Password Doesn't match.";
            $error=true;
        }

        if($error!=true){
            $hold_value = false;

            // Move the photo file to the folder
            $photo_folder = "files/customer/profile_pic_file";
            $photo_file_location = $photo_folder . "/" . $nid . "_photo." . $photo_file_type;
            $photo_tamp_name = $_FILES["photo"]["tmp_name"];

            $registration_sql = "INSERT INTO `customer` (`email`, `phone`, `name`, `address`, `gender`, `password`, `nid`, `photo`) VALUES ('$email', '$phone', '$name', '$address', '$gender', '$pass', '$nid', '$photo_file_location')";
            
            if(move_uploaded_file($photo_tamp_name, $photo_file_location)){
                $state = mysqli_query($connect, $registration_sql);

                // Close the database connection
                mysqli_close($connect);

                //Redirect to the login
                echo '<script>window.location.replace("login.php");</script>';
                die();
            } else {
                $photo_file_error = "Failed to upload the photo.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>

    <!-- Links Start -->
    <?php require '_link_common.php'; ?>
    
    <link rel="stylesheet" href="registration.css">
    <link rel="stylesheet" href="footer.css">
    
    <!-- Links End -->
</head>
<body>
    <!-- Top Banner -->
    <img src="images\banar\welcome_to_the_famaly.gif" class="d-block w-100 mb-5" alt="Welcome">
    <!-- Or -->
    <!-- <div class="container text-center p-5">
        <h1 class="page-title p-3">
            Welcome To The Family
            <span class="span-top"></span>
            <span class="span-right"></span>
            <span class="span-bottom"></span>
            <span class="span-left"></span>
        </h1>
    </div> -->

    <!-- registration Box -->
    <div class="registration-box mb-5">
        <form action="registration.php" method="post" enctype="multipart/form-data">
            <h2>Registration</h2>

            <!-- Full name -->
            <div class="user-input">
                <input type="text" id="full_name" name="name" <?php if ($hold_value == true) { echo 'value="' . $name . '"'; } ?> required>
                <label>Full Name</label>
            </div>

            <!-- Present Address -->
            <div class="user-input">
                <input type="text" id="address" name="address" <?php if ($hold_value == true) { echo 'value="' . $address . '"'; } ?> required>
                <label>Present Address</label>
            </div>

            <!-- NID Number -->
            <div class="user-input">
                <input type="number" id="nid" name="nid" <?php if ($hold_value == true) { echo 'value="' . $nid . '"'; } ?> required>
                <label>NID Number</label>
            </div>
            <?php
                if ($invalid_nid != "") {
                    echo '<span class="text-danger input-error">' . $invalid_nid . '</span>';
                }
            ?>

            <!-- E-mail -->
            <div class="user-input">
                <input type="email" id="email" name="email"  <?php if ($hold_value == true) { echo 'value="' . $email . '"'; } ?> required>
                <label>E-mail <Address></Address></label>
            </div>
            <?php
                if ($invalid_email != "") {
                    echo '<span class="text-danger input-error">' . $invalid_email . '</span>';
                }
            ?>

            <!-- Phone Number -->
            <div class="user-input">
                <input type="number" id="phone" name="phone" <?php if ($hold_value == true) { echo 'value="' . $phone . '"'; } ?> required>
                <label>Phone Number</label>
            </div>
            <?php
                if ($invalid_phone != "") {
                    echo '<span class="text-danger input-error">' . $invalid_phone . '</span>';
                }
            ?>

            <!-- Profile picture -->
            <div class="row text-light mb-4">
                <label>Profile picture</label>
                <input type="file" id="photo" name="photo" required>
                <?php
                    if ($photo_file_error != "") {
                        echo '<span class="text-danger input-error">' . $photo_file_error . '</span>';
                    }
                ?>
            </div>

            <label class="text-light fs-6">Gender</label><br>
            <div class="form-check form-switch form-check-inline">
                <input class="form-check-input active" type="radio" name="gender" value="male" id="gender-male">
                <label class="form-check-label text-light fs-7" for="male">Male</label>
            </div>
            <div class="form-check form-switch form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="female" id="gender-female">
                <label class="form-check-label text-light fs-7" for="Female">Female</label>
            </div>
            <div class="form-check form-switch form-check-inline">
                <input class="form-check-input" type="radio" name="gender" value="other" id="gender-other" checked>
                <label class="form-check-label text-light fs-7" for="Female">Other</label>
            </div>

            <!-- Confirm Password -->
            <div class="user-input mt-3">
                <input type="password" id="pass" name="pass" required>
                <label>Password</label>
            </div>
            <div class="user-input">
                <input type="password" id="repass" name="repass" required>
                <label>Confirm Password</label>
            </div>
            <?php
                if ($invalid_pass != "") {
                    echo '<span class="text-danger input-error">' . $invalid_pass . '</span>';
                }
            ?>

            <span class="registration-span my-4">
                <span class="span-top"></span>
                <span class="span-right"></span>
                <span class="span-bottom"></span>
                <span class="span-left"></span>
                <input class="registration-btn" type="submit" value="REGISTER">
            </span>

            <div class="position-relative bg-light mt-5">
                <div class="position-absolute top-0 end-0 mt-4">
                    <a href="login.php">Already have account? Login</a><br>
                </div>
            </div>
        </form>
    </div>

<!-- Footer -->
<?php include '_footer_common.php';?>
</body>
</html>
