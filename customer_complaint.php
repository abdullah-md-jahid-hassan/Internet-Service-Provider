<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Complaint</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>
    <!-- Link End -->

</head>
<body>
    <?php
        // Defining Page
        $page_type = "customer_complaint";
        $page_name = "Complaint";

        // Navbar
        require '_nav_customer.php';

        $error = "";


        //Submit the Complaint
        if(isset($_POST['request'])) {
            // connect to the database
            require '_database_connect.php';

            $name = mysqli_real_escape_string($connect, $_POST['name']);
            $type = mysqli_real_escape_string($connect, $_POST['type']);
            $details = mysqli_real_escape_string($connect, $_POST['details']);
            $customer_id = $_SESSION['id'];

            $new_complaint_sql = "INSERT INTO `complaint` 
            (`title`, `type`, `customer_id`, `state`, `complaining_date`, `details`) 
            VALUES ('$name', '$type', '$customer_id', 'Pending', NOW(), '$details')";
        
            $new_complaint_query = mysqli_query($connect, $new_complaint_sql);

            if($new_complaint_query){
                echo "<script> window.location.href='dash_customer.php';</script>";
                die();
            } else {$error = "Failed to Submit Complaint";}

            // Close the database connection
            mysqli_close($connect);
        }
    ?>

    <!-- Add complain form -->
    <div class="container rounded bg-black my-5 p-4 text-light">
        <h3 class="border p-2 text-center">Complaint Details</h3>
        <form method="post">

            <!-- Complain title -->
            <div class="my-4">
                <label for="name" class="form-label">Complain title</label>
                <input type="text" name="name" class="form-control" id="title" placeholder="Complain title" required>
            </div>

            <!-- Complain type -->
            <div class="my-4">
                <label for="type" class="form-label">Complain Type</label>
                <select name="type" class="form-control" id="type" placeholder="Type" required>
                    <option value="General" selected>General</option>
                    <option value="Speed">Speed</option>
                    <option value="Connection">Connection</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <!-- Complain details -->
            <div class="my-4">
                <label for="details" class="form-label">Complain Details</label>
                <textarea style="height: 200px;" name="details" class="form-control" id="details" placeholder="Details" required></textarea>

            </div>

            <!-- Submit or Cancel button -->
            <div class="input-group">
                <button type="submit" class="btn btn-success" name="request"><i class="fa-solid fa-cloud-arrow-up"></i> Submit Request</button>
                <a href="dash_customer.php" class="btn btn-secondary"><i class="fa-solid fa-xmark"></i> Cancel</a>
            </div>
        </form>
        <h5 class="text-danger"><?php echo $error;?></h5>
    </div>
    
</body>
</html>
