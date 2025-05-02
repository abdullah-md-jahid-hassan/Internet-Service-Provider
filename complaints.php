<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints list</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>
    <!-- Link End -->

</head>
<body>
    <?php
        //Defining Page
        $page_type = $page_name = "Complaints List";

        $key = "all";
        $word = "";

        //Navbar
        require '_nav_admin.php';

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

        //Seeing Details
        if(isset($_POST['details'])){
            $_SESSION['customer_id_details'] = $_POST['c_id'];
            echo "<script> window.location.href='customer_details.php';</script>";
            die();
        }

        // connect to the database
        require '_database_connect.php';

        //Searchbar function
        $find_complaints_sql = "";
        $word = mysqli_real_escape_string($connect, $word);
        if(isset($key) && isset($word)){
            $find_complaints_sql = "SELECT 
                complaint.id AS com_id,
                complaint.title AS com_title,
                complaint.type AS com_type,
                complaint.state AS com_state,
                DATE_FORMAT (complaint.complaining_date, '%D %M, %Y') AS com_date,
                customer.id AS cus_id
                FROM `complaint`
                JOIN customer ON complaint.customer_id = customer.id ";
            if($key=="all" && $word!=""){
                 $find_complaints_sql .= "WHERE CONCAT(complaint.title, complaint.type, complaint.state, customer.phone, customer.email) LIKE '%$word%'";
            } else if($key=="title" && $word!=""){
                 $find_complaints_sql .= "WHERE `complaint.title` LIKE '%$word%'";
            } else if($key=="state" && $word!=""){
                $find_complaints_sql .= "WHERE `complaint.state` LIKE '%$word%'";
            } else if($key=="type" && $word!=""){
                $find_complaints_sql .= "WHERE `complaint.type` LIKE '%$word%'";
            } else if($key=="cu_phone" && $word!=""){
                $find_complaints_sql .= "WHERE `customer.phone` LIKE '%$word%'";
            } else if($key=="cu_email" && $word!=""){
            $find_complaints_sql .= "WHERE `customer.email` LIKE '%$word%'";
            } else {
                $find_complaints_sql .= "WHERE 1";
            }
        }

        //Query
        $find_complaints = mysqli_query($connect, $find_complaints_sql);
        $total_complaints = mysqli_num_rows($find_complaints);
        
        // Close the database connection
        mysqli_close($connect);
        
    ?>

    <div class="container my-3">
        <!-- Search bar with filter -->
        <form method="get">
            <div class="input-group">
                <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button>  
                <select class="search-select" name="key" style="width: 20%;">
                    <option value="all" selected>Search By</option>
                    <option value="title">Title</option>
                    <option value="state">Status</option>
                    <option value="type">Type</option>
                    <option value="cu_phone">Customer phone</option>
                    <option value="cu_email">Customer email</option>
                </select>

                <input type="text" class="form-control" name="word" placeholder="Search">

                <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button>  
            </div>
        </form>
    </div>



    <?php
        // connect to the database
        require '_database_connect.php';

        // Showing Customer list
        if($total_complaints>0){
            echo "
            <div class='container overflow-x-scroll mt-4'>
                <div class='num_of_res text-light btn btn-dark m-2'>
                    <h7 class='pt-2'>Total Result: $total_complaints</h7>
                </div>
                <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Complaint Date</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>";
                for($i=0; $i<$total_complaints; $i++){
                    $com_cu = mysqli_fetch_assoc($find_complaints);

                    echo"<tr>
                            <td>$com_cu[com_title]</td>
                            <td>$com_cu[com_type]</td>
                            <td>$com_cu[com_state]</td>
                            <td>$com_cu[com_date]</td>
                            <td>
                            </td>
                        </tr>";
                }
                echo"
                    </tbody>
                </table>
            </div>";;
        }
        
        // Close the database connection
        mysqli_close($connect);
    
    ?>

<a class='btn btn-success' target='_blank' href='complaint'>Details</a>

</body>
</html>
