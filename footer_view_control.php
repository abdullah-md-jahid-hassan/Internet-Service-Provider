<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Footer</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>
    <!-- Link End -->

</head>
<body>
    <?php
        //Page name and type
        $page_name = "Edit footer";
        $page_type = "customer_output_control";

        $error = "";

        
        // connect to the database
        require '_database_connect.php';

        //get footer data
        $footer_info_sql = "SELECT * FROM `footer_data` WHERE 1";
        $find_footer_info = mysqli_query($connect, $footer_info_sql);
        $footer_info = mysqli_fetch_assoc($find_footer_info);

        // Updating the footer data
        if(isset($_POST['footer_update'])) {

            // Getting footer values
            $address_text = $_POST['address_text'];
            $address_link = $_POST['address_link'];
            $phone_text = $_POST['phone_text'];
            $phone_link = $_POST['phone_link'];
            $mail_text = $_POST['mail_text'];
            $mail_link = $_POST['mail_link'];
            $fb_link = $_POST['fb_link'];
            $ms_link = $_POST['ms_link'];
            $wh_link = $_POST['wh_link'];
            $in_link = $_POST['in_link'];
            $yt_link = $_POST['yt_link'];

            // SQL
            $update_footer_sql = "UPDATE `footer_data` SET `address_text` = '$address_text', `address_link` = '$address_link', `phone_text` = '$phone_text', `phone_link` = '$phone_link', `mail_text` = '$mail_text', `mail_link` = '$mail_link', `fb_link` = '$fb_link', `ms_link` = '$ms_link', `wh_link` = '$wh_link', `in_link` = '$in_link', `yt_link` = '$yt_link' WHERE `id` = '1';";

            // Insert Data Into Database
            if(mysqli_query($connect, $update_footer_sql)) {
                // Success, redirect

                // Close the database connection
                mysqli_close($connect);

                echo "<script> window.location.href='dash_admin.php';</script>";
                die();
            } else {
                $error = "Failed To Create Plan! Please Try Again.";
            }
        }

        // Close the database connection
        mysqli_close($connect);

        // Navbar
        require '_nav_admin.php';
    ?>

    <!-- Add Plan form --> 
    <div class="container rounded bg-black my-5 p-4 text-light">
        <h3 class="border p-2 text-center">
            Update the Info Of the Footer
        </h3>
        <form method="post">
            <!-- address_text -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="address_text" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['address_text'].'"'?> required>
            </div>
            
            <!-- address_link -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="address_link" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['address_link'].'"'?> required>
            </div>
            
            <!-- phone_text -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="phone_text" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['phone_text'].'"'?> required>
            </div>
            
            <!-- phone_link -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="phone_link" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['phone_link'].'"'?> required>
            </div>
            
            <!-- mail_text -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="mail_text" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['mail_text'].'"'?> required>
            </div>
            
            <!-- mail_link -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="mail_link" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['mail_link'].'"'?> required>
            </div>
            
            <!-- fb_link -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="fb_link" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['fb_link'].'"'?> required>
            </div>
            
            <!-- ms_link -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="ms_link" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['ms_link'].'"'?> required>
            </div>
            
            <!-- wh_link -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="wh_link" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['wh_link'].'"'?> required>
            </div>
            
            <!-- in_link -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="in_link" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['in_link'].'"'?> required>
            </div>
            
            <!-- yt_link -->
            <div class="mb-3">
                <label for="plan_name" class="form-label">Address text</label>
                <input type="text" name="yt_link" class="form-control" id="plan_name" <?php echo 'value="'.$footer_info['yt_link'].'"'?> required>
            </div>

            <!-- Submit button -->
            <div class="input-group">
                <button type="submit" class="btn btn-success" name="footer_update">
                    <i class='fa-solid fa-up-down'></i> Update
                </button>
                <a href="dash_admin.php" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i> Cancel</a>
            </div>

        </form>
        <h5 class="text-danger"><?php echo $error;?></h5>
    </div>

    <?php require '_footer_common.php';?>
</body>
</html>
