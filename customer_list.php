<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer list</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>
    <!-- Link End -->

</head>

<body>

<?php
    //Defining Page
    $page_type = $page_name = "Customers List";

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

    //Sarcbar function
    if(isset($key) && isset($word)){
        if($key=="all" && $word==""){
            $find_customer_sql = "SELECT * FROM `customer`";
        } else if($key=="all" && $word!=""){
            $find_customer_sql = "SELECT * FROM `customer` WHERE CONCAT(name, address, phone, email) LIKE '%$word%'";
        } else if($key=="name" && $word!=""){
            $find_customer_sql = "SELECT * FROM `customer` WHERE `name` LIKE '%$word%'";
        } else if($key=="address" && $word!=""){
            $find_customer_sql = "SELECT * FROM `customer` WHERE `address` LIKE '%$word%'";
        } else if($key=="phone" && $word!=""){
            $find_customer_sql = "SELECT * FROM `customer` WHERE `phone` LIKE '%$word%'";
        } else if($key=="email" && $word!=""){
            $find_customer_sql = "SELECT * FROM `customer` WHERE `email` LIKE '%$word%'";
        }
    } else {$find_customer_sql = "SELECT * FROM `customer`";}

    //Query
    $find_customer = mysqli_query($connect, $find_customer_sql);
    $total_customer = mysqli_num_rows($find_customer);
    
?>



<div class="container my-3">
    <!-- Search bar with filter -->
    <form method="get">
        <div class="input-group">
            <button class="btn btn-success btn-outline-light" name="reset" type="submit"><i class="fa-solid fa-rotate"></i></i> Re-set</button>  
            <select class="search-select" name="key" style="width: 20%;">
                <option value="all" selected>Search By</option>
                <option value="name">Name</option>
                <option value="address">Address</option>
                <option value="phone">Phone</option>
                <option value="email">Email</option>
            </select>
            <input type="text" class="form-control" name="word" placeholder="Search">
            <button class="btn btn-danger btn-outline-light" name="search" type="submit"><i class="fa-solid fa-magnifying-glass"></i></i> Search</button>  
        </div>
    </form>
</div>


<!-- Showing Customer list -->
<?php if($total_customer>0): ?>
    <div class='container overflow-x-scroll mt-4'>
        <div class='num_of_res text-light btn btn-dark m-2'>
            <h7 class='pt-2'>Total Result: <?php echo $total_customer; ?></h7>
        </div>
        <table class='table table-info table-striped table-hover m-2 p-2 text-center'>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Number Of Connections</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>

                <?php for($i=0; $i<$total_customer; $i++):
                    $customer = mysqli_fetch_assoc($find_customer);

                    // Find the connections number
                    $find_customer_connection_sql = "SELECT * FROM `connections` where `customer_id` = '{$customer['id']}' AND `state` != 'Connection Pending'";
                    $find_connection = mysqli_query($connect, $find_customer_connection_sql);
                    $total_connections = mysqli_num_rows($find_connection); ?>

                    <tr>
                        <td><?php echo $customer['name']; ?></td>
                        <td><?php echo $customer['address']; ?></td>
                        <td><?php echo $customer['phone']; ?></td>
                        <td><?php echo $customer['email']; ?></td>
                        <td><?php echo $total_connections; ?></td>
                        <td>
                            <a class='btn btn-success' href='customer_details.php?c_id=<?php echo $customer['id']; ?>'>Details</a>
                        </td>
                    </tr>

                <?php endfor; ?>

            </tbody>
        </table>
    </div>

<?php
endif;
//Close the database connection
mysqli_close($connect);
?>


</body>
</html>
