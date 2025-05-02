<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>
    <link rel="stylesheet" href="customer_details.css">
    <!-- Link End -->

</head>
<body>

<?php
    //Defining Page
    $page_type = "customers_list";
    $page_name = "Customers Details";
    
    //Navbar
    require '_nav_admin.php';

    // connect to the database
    require '_database_connect.php';

    // Import Functions
    require_once 'function/general_purpose_functions.php';
    use function general_purpose_functions\first_word;

    // Get customer ID safely (prevent SQL injection & XSS)
    $customer_id = mysqli_real_escape_string($connect, $_GET['c_id']);

    // Get Customer data
    $customer_sql = "SELECT
            customer.photo AS photo,
            customer.name AS name,
            customer.address AS address,
            customer.email AS email,
            customer.phone AS phone,
            customer.nid AS nid,
            COUNT(connections.id) as total_connections
        FROM customer
        JOIN connections ON customer.id = connections.customer_id
        WHERE customer.id = ?
        GROUP BY customer.id";

    $stmt = mysqli_prepare($connect, $customer_sql);
    mysqli_stmt_bind_param($stmt, "s", $customer_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $customer = mysqli_fetch_assoc($result);
    if (!$customer) {
        die("Customer not found");
    }

    //Get connections data
    $connection_sql = "SELECT 
            connections.name AS con_name,
            connections.address AS address, 
            connections.type AS type,
            plans.name AS plan_name,
            plans.speed AS speed,
            plans.price AS price
        FROM connections
        JOIN plans ON connections.plan_id = plans.id
        WHERE `customer_id` = ?";
    $stmt = mysqli_prepare($connect, $connection_sql);
    mysqli_stmt_bind_param($stmt, "s", $customer_id);
    mysqli_stmt_execute($stmt);
    $connection_result = mysqli_stmt_get_result($stmt);

     // Getting Bill Info
     $con_pay_sql = "SELECT 
     connections.name AS name,
     connections.type AS type,
     DATE_FORMAT(bill.due_date, '%M, %Y') AS month,
     bill.amount AS amount,
     bill.state AS state,
     bill.due_date AS due_date,
     bill.id AS bill_id 
     FROM connections 
     JOIN bill ON connections.id = bill.connection_id 
     WHERE connections.customer_id = '{$customer_id}'
     ORDER BY 
         CASE 
             WHEN bill.state = 'Late' THEN 0 
             WHEN bill.state = 'Unpaid' THEN 1
             ELSE 2
         END,
         bill.due_date DESC";

     $find_con_pay = mysqli_query($connect, $con_pay_sql);

     // Query to get the total due amount
     $total_due_sql = "SELECT SUM(bill.amount) AS total_due FROM connections 
     JOIN bill ON connections.id = bill.connection_id 
     WHERE connections.customer_id = '{$customer_id}' AND bill.state = 'Unpaid'";
     $total_due_result = mysqli_query($connect, $total_due_sql);
     $total_due_row = mysqli_fetch_assoc($total_due_result);
     $total_due = $total_due_row['total_due'] ?? 0; // Default to 0 if null

    mysqli_stmt_close($stmt);
?>

<div class="container">
    <div class="row row-cols-auto bg-light rounded justify-content-center my-4">
        <!-- profile picture -->
        <div class="col-auto m-2">
            <img class="pp_img" src="<?php echo$customer['photo']?>" alt="Profile Picture">
        </div>
        <!-- Details -->
        <div class="col-auto m-2 ms-md-3">
            <div class="bg-dark text-light rounded pb-1 pt-2 px-3 text-center"><h2><b><?php echo$customer["name"]?></b></h2></div>
            <div class="details fs-5 mt-4">
                <p>
                    <b>Address: </b><?php echo $customer['address'];?><br>
                    <b>Email: </b><?php echo $customer['email'];?><br>
                    <b>Phone: </b><?php echo $customer['phone'];?><br>
                    <b>NID: </b><?php echo $customer['nid'];?>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Connection Section -->
<div class='container <?php if($customer['total_connections']>0){echo "overflow-x-scroll";}?>'>
    <div>
        <h5 class='p-2 bg-info rounded'>Total Active Connections: <?php echo $customer['total_connections'];?></h5>
    </div>

    <!-- Showing Customer list -->
    <?php if($customer['total_connections']>0): ?>
        <table class='table table-info table-striped table-hover mx-2 p-2 text-center'>
            <thead>
                <tr>
                    <th>Connection name</th>
                    <th>Address</th>
                    <th>Plan Type</th>
                    <th>Plan Name</th>
                    <th>Speed</th>
                    <th>Monthly Bill</th>
                </tr>
            </thead>
            <tbody>
                <?php for($i=0; $i<$customer['total_connections']; $i++):
                    //Getting connection info
                    $connection = $connection_result->fetch_assoc(); ?>
                    
                    <tr>
                        <td><?php echo htmlspecialchars($connection['con_name']); ?></td>
                        <td><?php echo htmlspecialchars($connection['address']); ?></td>
                        <td><?php echo htmlspecialchars(first_word($connection['type'])); ?></td>
                        <td><?php echo htmlspecialchars($connection['plan_name']); ?></td>
                        <td><?php echo htmlspecialchars($connection['speed']); ?></td>
                        <td><?php echo htmlspecialchars($connection['price']); ?></td>
                    </tr>

                <?php endfor; ?>

            </tbody>
        </table>

    <?php
        endif;
        // Close the database connection
        mysqli_close($connect);
    ?>
</div>

<!-- Bill History section -->
<div class='container my-4' id = 'bill_history'>
    <div class='p-2 bg-success rounded-top text-white d-flex justify-content-between align-items-center'>
        <?php if (mysqli_num_rows($find_con_pay) == 0): ?>
            <p class='text-center text-muted'>No Bill History Found</p>
        <?php else: ?>
            <div class='my-2'>
                <h5>Payment Amount: <?php echo number_format($total_due, 2); ?> BDT</h5>
                <p>N.T: Late fees are added if any bill is Late</p>
            </div>
            <form id="payForm" action="pay.php" method="POST">
                <input type="hidden" name="pay" value="all">
                <button type='submit' class="btn btn-primary p-2 "><b><i class='fa-solid fa-hand-holding-dollar fa-beat'></i> Pay All</b></button>
            </form> 
    </div>

    <!-- Table -->
    <div class='overflow-x-auto overflow-y-auto'  style='max-height: 400px;'>
        <table class='table table-info table-striped table-hover text-center'>
            <thead>
                <tr>
                    <th>Connection Name</th>
                    <th>Type</th>
                    <th>Bill Month</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Last Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($find_con_pay)): ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td>
                            <?php 
                                echo ($row['type'] == 'organizational_plans') ? 'Organizational' : 'Residential'; 
                            ?>
                        </td>

                        <td><?php echo $row['month']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['state']; ?></td>
                        <td><?php echo $row['due_date']; ?></td>
                        <td>
                            <?php if ($row['state'] == 'Paid'): 
                                //Get transaction id
                                // connect to the database
                                require '_database_connect.php';
                                if ($row['state']=="Paid"){
                                    $tran_id_sql = "SELECT 
                                        payments.tran_id As tran_id
                                        FROM payments 
                                        JOIN bill ON payments.id = bill.payment_id 
                                        WHERE bill.id = '{$row['bill_id']}'";
                                    $tran_id_result = mysqli_query($connect, $tran_id_sql);
                                    $tran_id = mysqli_fetch_assoc($tran_id_result);
                                    // Close the database connection
                                    mysqli_close($connect);
                                }
                            ?>
                                <a class='btn btn-success' href = 'payment_recept.php?tran_id=<?php echo $tran_id['tran_id']; ?>'>
                                    <i class="fa-solid fa-scroll fa-flip" style="--fa-flip-x: 1; --fa-flip-y: 0;"></i>
                                        Details
                                </a>
                            <?php else: ?>
                                <form id="payForm" action="pay.php" method="POST">
                                    <input type="hidden" name="pay" value="<?php echo $row['bill_id'] ?>">
                                    <button 
                                        type='submit' 
                                        class="btn p-2
                                        <?php 
                                                if($row['state'] == 'Late') echo 'btn-danger';
                                                else  echo 'btn-warning';
                                            ?> 
                                        ">
                                        <b><i class='fa-solid fa-hand-holding-dollar fa-beat'></i> Pay now</b>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

</body>
</html>
