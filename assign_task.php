<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Assign</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="assign_task.css">
    <!-- Link End -->

</head>
<body>
<?php
    //Login check
    require '_logincheck_admin.php';
        
    //Defining Page
    $page_type = "manage_employee";
    $page_name = "Assign Task";

    //Variable
    $key = "all";
    $word = "";

    // If choose button clicked
    if(isset($_POST['choose-em'])) {
        $_SESSION['employee_id_task'] = $_POST['employee_id'];
    }

    // If detail button clicked
    if(isset($_POST['details'])) {
        // Connect to the database
        require '_database_connect.php';
        // Close the database connection
        mysqli_close($connect);

        // Seasion Variable for employee dtails
        $_SESSION['employee_id_details'] = $_POST['employee_id'];

        // Generate the JavaScript code to open the new tab
        echo "<script>
            var newTab = window.open('employee_details_admin.php', '_blank');
            newTab.onload = function() {
                window.location.href = window.location.href.split('?')[0];
            };
        </script>";

        // Prevent form resubmission on reload
        echo "<script>history.pushState({}, '', '');</script>";
    }

    // If Assigned button clicked
    if(isset($_POST['assign'])) {
        $titel = $_POST['task_title'];
        $em_id = $_SESSION['employee_id_task'];
        $ref = $_SESSION['connections_id_details'];
        $details = $_POST['task_details'];
        $date = $_POST['task_date'];
        $address = $_POST['task_address'];

        //insart task
        $task_insertion_sql = "INSERT INTO `task` (`title`, `status`, `address`, `employee_id`, `task_ref`, `details`, `last_date`) VALUES ('$titel', 'Pending', '$address', '$em_id', '$ref', '$details', '$date');";
        // connect to the database
        require '_database_connect.php';
        $run_task_insertion = mysqli_query($connect, $task_insertion_sql);
        // Close the database connection
        mysqli_close($connect);

        if($run_task_insertion){

            //rederect to the previous page
            
            echo "<script> window.location.href='requests.php';</script>";
            die();
        }

    }

    //if searched
    if(isset($_GET['search'])){
        $key = $_GET['key'];
        $word = $_GET['word'];
    }

    //If Re-Set
    if(isset($_GET['reset'])){
        $key = "all";
        $word = "";
    }

    // connect to the database
    require '_database_connect.php';

    //sql
    if(isset($key) && isset($word)){
        if($key=="all" && $word==""){
            $show_employee_sql = "SELECT * FROM `employee`";
        } else if($key=="all" && $word!=""){
                $show_employee_sql = "SELECT * FROM `employee` WHERE CONCAT(name, post, phone, email, id) LIKE '%$word%'";
        } else if($key=="name" && $word!=""){
            $show_employee_sql = "SELECT * FROM `employee` WHERE `name` LIKE '%$word%'";
        } else if($key=="post" && $word!=""){
            $show_employee_sql = "SELECT * FROM `employee` WHERE `post` LIKE '%$word%'";
        } else if($key=="phone" && $word!=""){
            $show_employee_sql = "SELECT * FROM `employee` WHERE `phone` LIKE '%$word%'";
        } else if($key=="email" && $word!=""){
            $show_employee_sql = "SELECT * FROM `employee` WHERE `email` LIKE '%$word%'";
        } else if($key=="e-id" && $word!=""){
            $show_employee_sql = "SELECT * FROM `employee` WHERE `id` LIKE '%$word%'";
        } else {$show_employee_sql = "SELECT * FROM `employee`";}
    }

    //Query
    $run_show_employee = mysqli_query($connect, $show_employee_sql);
    $total_employee = mysqli_num_rows($run_show_employee);

    if (isset($_SESSION['connections_id_details'])){
        // Get Connection Info
        $find_connection_sql = "SELECT * FROM `connections` WHERE `id` = '{$_SESSION['connections_id_details']}'";
        $find_connection = mysqli_query($connect, $find_connection_sql);
        $connection = mysqli_fetch_assoc($find_connection);
    }

    // Close the database connection
    mysqli_close($connect);
    
    //Navbar
    require '_nav_admin.php';
?>

<div class="container assign_task_body rounded my-3 py-2">
    <form method="post" class="text-light">
        <div class="mb-3">
            <label for="task_details" class="form-label">Assigned Emmployee</label><br>
            <?php
                if(!isset($_SESSION['employee_id_task'])){
                    echo "
                    <button type='button' class='btn btn-info' data-bs-toggle='modal' data-bs-target='#employee_list'>
                        Choose an employee
                    </button>";
                } else {
                    // connect to the database
                    require '_database_connect.php';

                    $choosen_employee_sql = "SELECT * FROM `employee` WHERE `id` = '$_SESSION[employee_id_task]'";
                    $run_choosen_employee = mysqli_query($connect, $choosen_employee_sql);
                    $choosen_employee = mysqli_fetch_assoc($run_choosen_employee);

                    echo "<div class='container bg-light rounded text-black p-2'>
                        <div class='row'>
                            <div class='col align-self-start'>
                                <img src='$choosen_employee[photo_file]' class='rounded-circle' alt='Employee Photo' style='width: 50px'>
                            </div>
                            <div class='col align-self-center'>
                                <b>Name:</b> $choosen_employee[name]
                            </div>
                            <div class='col align-self-center'>
                                <b>Post:</b> $choosen_employee[post]
                            </div>
                            <div class='col align-self-center'>
                            <b>Phone:</b> $choosen_employee[phone]
                            </div>
                            <div class='col align-self-center'>
                                <b>Assigned task:</b> 10
                            </div>
                            <div class='col align-self-center'>
                                <b>Expiard task:</b> 02
                            </div>
                            <div class='col align-self-center'>
                            <button type='button' class='btn btn-info' data-bs-toggle='modal' data-bs-target='#employee_list'>Change</button>
                            </div>
                        </div>
                    </div>";
                }
            ?>

            
        </div>


        <div class="mb-3">
            <label for="task_title" class="form-label">Task Title</label>
            <input type="text" class="form-control" id="task_title" name="task_title" <?php if(isset($_SESSION['connections_id_details'])){
                echo "value='";
                if($connection['state']=="Connection Pending"){
                    echo "New connection in ";
                }else if (strpbrk($connection['state'], '0123456789')) {
                    echo "Update connection in ";
                } else if($connection['state']=="Delete Request Pending"){
                    echo "Disconnect connection in ";
                }
                echo"$connection[address]"."'";
            } else {
                echo "placeholder='Enter the title Here'";
            }?>>
        </div>

        <div class="mb-3">
            <label for="task_address" class="form-label">Task Address</label>
            <input type="text" class="form-control" id="task_address" name="task_address" <?php if(isset($_SESSION['connections_id_details'])){echo "value='"."$connection[address]"."'";} else {
                echo "placeholder='Enter the address Here'";
            }?>>
        </div>

        <div class="mb-3">
            <label for="task_date" class="form-label">Task Date</label>
            <input type="date" class="form-control" id="task_date" name="task_date" <?php if(isset($_SESSION['connections_id_details'])){
                echo "value='";
                if($connection['state']=="Connection Pending"){
                    echo "$connection[starting_date]"."'";
                }else if (strpbrk($connection['state'], '0123456789')) {
                    echo "$connection[submission_date]"."'";
                } else if($connection['state']=="Delete Request Pending"){
                    echo "$connection[submission_date]"."'";
                }
            }?>>
        </div>

        <div class="mb-3">
            <label for="task_details" class="form-label">Task Details</label>
            <input type="text" class="form-control" id="task_details" name="task_details" placeholder="Enter the details Here">
        </div>

        <button type="submit" class="btn btn-success" name="assign"><i class="fa-solid fa-scroll"></i> Assign</button>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="employee_list" data-bs-keyboard="false" aria-labelledby="employee-list" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="employee-list">Chhose Employee</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Search Bar -->
                <div class="container my-2">
                    <form method="get">
                        <div class="input-group">  
                            <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button> 
                            <!-- Search bar with filter -->
                            <select class="search-select" name="key" style="width: 20%;">
                                <option value="all" selected>Search By</option>
                                <option value="name">Name</option>
                                <option value="post">Post</option>
                                <option value="phone">Phone</option>
                                <option value="email">Email</option>
                                <option value="e-id">Employee-ID</option>
                            </select>
                            <input type="text" class="form-control" name="word" placeholder="Search">
                            <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button> 
                        </div>
                    </form>
                </div>
                <?php
                    // Showing Employee list
                    echo "
                        <div class='container overflow-auto mt-4'>
                            <div class='num_of_res text-light btn btn-dark m-2'>
                                <h7 class='pt-2'>Total Result: $total_employee</h7>
                            </div>";
                    if($total_employee>0){
                        //Show the Employee List
                        echo "
                            <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
                                <thead>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Post</th>
                                        <th>Actions</th>
                                        <th>Phone</th>
                                        <th>Pending Task</th>
                                        <th>Expiard</th>
                                    </tr>
                                </thead>
                                <tbody>
                        ";
                        for($i=0; $i<$total_employee; $i++){
                            // connect to the database
                            require '_database_connect.php';
                            // Get row

                            $employee = mysqli_fetch_assoc($run_show_employee);
                            
                            // Close the database connection
                            mysqli_close($connect);
                            
                            echo "
                                    <tr>
                                        <td><img src='$employee[photo_file]' class='rounded-circle' alt='Employee Photo' style='width: 50px'></td>
                                        <td>$employee[name]</td>
                                        <td>$employee[post]</td>
                                        <td>
                                            <form method='post'>
                                                <input class='visually-hidden' type='text' name='employee_id' value='$employee[id]'</input>

                                                <button class='btn btn-danger mb-1' type='submit' name='choose-em' data-bs-dismiss='modal' style='width: 114px'>Choose</button>

                                                <button class='btn btn-success' type='submit' name='details' style='width: 114px'>Details</button>
                                            </form>
                                        </td>
                                        <td>$employee[phone]</td>
                                        <td>task</td>
                                        <td>ex-task</td>
                                    </tr>
                            ";
                        }
                        echo "
                                </tbody>
                            </table>
                        ";
                    }
                    echo"</div>";
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Script to open modal by default -->
<script>
    document.addEventListener("DOMContentLoaded", function(){
        var myModal = new bootstrap.Modal(document.getElementById('employee_list'));
        myModal.show();
    });
</script>

</body>
</html>