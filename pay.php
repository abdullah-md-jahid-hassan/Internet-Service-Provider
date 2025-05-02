<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
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
        $page_type = $page_name = "Payment";

        if (!isset($_POST['pay']) || empty($_POST['pay'])) {
            echo '<script> window.location.replace("customer_info.php?#bill_history");</script>';
            die(); // Stop further execution
        }
        $pay = $_POST['pay'];
        
        //Navbar
        require '_nav_customer.php';

        // connect to the database
        require '_database_connect.php';

        // Sanitize session ID
        $customer_id = $_SESSION['id'];

        // Getting Payment Info
        $con_pay_sql = "SELECT 
            connections.name AS name,
            connections.type AS type,
            DATE_FORMAT(bill.due_date, '%M, %Y') AS month,
            bill.amount AS amount,
            bill.state AS state,
            bill.due_date AS due_date,
            bill.id AS bill_id
            FROM bill 
            JOIN connections ON bill.connection_id = connections.id
            WHERE connections.customer_id = '{$customer_id}' 
            AND bill.state != 'Paid'";
    
        if($pay!='all'){
            $con_pay_sql .= " AND bill.id = '$pay'";
        }

        $find_con_pay = mysqli_query($connect, $con_pay_sql);

        // Query to get the total due amount
        $total_due_sql = "SELECT 
        SUM(bill.amount) AS total_due
        FROM connections 
        JOIN bill ON connections.id = bill.connection_id 
        WHERE connections.customer_id = '{$customer_id}' 
        AND bill.state != 'Paid'";
    
        if($pay!='all'){
            $total_due_sql .= " AND bill.id = '$pay'";
        }

        $total_due_result = mysqli_query($connect, $total_due_sql);
        $total_due_row = mysqli_fetch_assoc($total_due_result);
        $total_due = $total_due_row['total_due'] ?? 0;

        // Close the database connection
        mysqli_close($connect);
    ?>

        <!-- Payment info -->
    <div class="container my-2">
        <div class='p-2 bg-success rounded-top text-white d-flex justify-content-between align-items-center'>
            <div>
                <h5>Payment Amount: <?php echo number_format($total_due, 2); ?> BDT</h5>
                <p>N.T: Late fees are added if any bill is Late</p>
            </div>
            <form action="payment/checkout_hosted.php" method="POST" class='mb-2'>
                <input type="hidden" name="pay" value="<?php echo $pay; ?>">
                <button type="submit" class="btn btn-primary p-2" id="sslczPayBtn">
                    <b><i class='fa-solid fa-arrow-right fa-shake'></i> Proceed to Pay</b>
                </button>
            </form>
        </div>

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
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    

<?php
    //Footer
    include '_footer_common.php';
?>

</body>
</html>
