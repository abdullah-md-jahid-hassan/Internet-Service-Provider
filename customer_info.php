<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="footer.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="customer_details.css">
    <!-- Link End -->

</head>
<body>
    <?php
        //Defining Page
        $page_type = $page_name = "Profile";
        
        //Navbar
        require '_nav_customer.php';

        // connect to the database
        require '_database_connect.php';

        // Sanitize session ID
        $customer_id = $_SESSION['id'];

        //getting Customer Info
        $find_customer_sql = "SELECT `photo`, `name`, `address`, `email`, `phone`, `nid` FROM `customer` WHERE `id` = '{$customer_id}'";
        $find_customer = mysqli_query($connect, $find_customer_sql);
        $customer = mysqli_fetch_assoc($find_customer);

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
 
        // Fetch complaints from the database
        $complaint_sql = "SELECT
            title,
            type,
            state,
            comments,
            details,
            DATE_FORMAT(complaining_date, '%D %M, %Y') AS date
            FROM complaint WHERE customer_id = '{$customer_id}'";
        $find_complains = mysqli_query($connect, $complaint_sql);
        $complaint_num = mysqli_num_rows($find_complains);

        // Close the database connection
        mysqli_close($connect);
    ?>

    <div class="container">
        <div class="row row-cols-auto bg-light rounded justify-content-center my-4">
            <!-- profile picture -->
            <div class="col-auto m-2">
                <img class="pp_img" src="<?php echo $customer['photo']?>" alt="Profile Picture">
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

        <!-- Complaint History Section -->
        <div class='container my-5' id = 'complaint_history'>
            <div class='p-2 bg-danger rounded-top text-white d-flex justify-content-between'>
                <?php if ($complaint_num == 0): ?>
                    <p class='text-center text-muted m-0'>No Complaint History Found</p>
                    <a class="btn btn-warning p-2 fa-solid fa-plus" href="customer_complaint.php"><b> New Complain</b></a>
                <?php else: ?>
                    <h5 class="my-2">Total Complaints: <?php echo $complaint_num; ?></h5>
                    <a class="btn btn-warning p-2 fa-solid fa-plus" href="customer_complaint.php"><b> New Complain</b></a>
            </div>

                <div class='table-responsive' style='max-height: 400px; overflow-y: auto;'>

                    <table class='table table-info table-striped table-hover text-center'>
                        <thead>
                            <tr>
                                <th>Complain Title</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Complaint Date</th>
                                <th>Details</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($find_complains)): ?>
                                <tr>
                                    <td><?php echo $row['title']; ?></td>
                                    <td><?php echo $row['type']; ?></td>
                                    <td><?php echo $row['state']; ?></td>
                                    <td><?php echo $row['date']; ?></td>
                                    <td><?php echo nl2br($row['details']); ?></td>
                                    <td><?php echo $row['comments'] ? $row['comments'] : "<span class='text-muted'>No Comments</span>"; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>

    

<?php 
    //Footer
    include '_footer_common.php';
?>


</body>
</html>
