<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <!-- Links Start -->
    <?php include '_link_common.php'; ?>

    <link rel="stylesheet" href="customer_details.css">
    <!-- Link End -->

</head>
<body>
    <?php
        //Defining Page
        $page_type = $page_name = "Payment Recept";

        if (!isset($_GET['tran_id']) || empty($_GET['tran_id'])) {
            echo '<script> window.location.replace("customer_info.php?#bill_history");</script>';
            die();
        }
        //Sanitize the tran_id
        $tran_id = trim($_GET['tran_id'] ?? '');
        
        //Navbar
        require '_nav_customer.php';

        // connect to the database
        require '_database_connect.php';

        // Getting customer id
        $customer_id = $_SESSION['id'];

        //Get Payment amount
        $pay_info_sql ="SELECT 
        amount, 
        DATE_FORMAT(pay_date, '%dth %M, %Y') AS pay_date,
        card_type 
        FROM payments WHERE tran_id=?";
        $stmt = $connect->prepare($pay_info_sql);
        $stmt->bind_param('s', $tran_id);
        $stmt->execute();
        $pay_info_result = $stmt->get_result();
        $pay_info = $pay_info_result->fetch_assoc();

        // Getting bill Info
        $bill_info_sql = "SELECT DISTINCT
            connections.name AS name,
            connections.type AS type,
            DATE_FORMAT(bill.due_date, '%M, %Y') AS month,
            bill.amount AS amount,
            bill.state AS state
            FROM payments
            JOIN bill ON payments.id = bill.payment_id
            JOIN connections ON bill.connection_id = connections.id
            WHERE payments.tran_id = ? AND connections.customer_id = ?";
        $stmt = $connect->prepare($bill_info_sql);
        $stmt->bind_param('ss', $tran_id, $customer_id);
        $stmt->execute();
        $bill_info_result = $stmt->get_result();
 
        // Close the database connection
        mysqli_close($connect);
    ?>

    <div class="container">
        <!-- PDF buttons -->
        <div class='mt-4 mx-2 d-flex justify-content-end align-items-end'>
            <form class='mx-1' action='payment_recept_view_download.php' method='POST' target='_blank'>
                <input type='hidden' name='tran_id' value='<?php echo $tran_id;?>'>
                
                <!-- View button -->
                <button class='btn btn-warning' type='submit' name='view'>
                    <i class="fa-solid fa-eye"></i> 
                    View recept
                </button>

                <!-- Download button -->
                <button class='btn btn-danger' type='submit' name='download'>
                    <i class="fa-solid fa-download"></i> 
                    Download recept
                </button>
            </form>
        </div>

        <!-- Payment info -->
        <div class='container mb-4'>
            <div class='p-2 bg-success rounded-top text-white d-flex justify-content-between align-items-center'>
                <div>
                    <h5>Payment Amount: <?php echo $pay_info['amount']; ?> BDT</h5>
                    <p'>Paid On: <?php echo $pay_info['pay_date']; ?></p> 
                </div> 
                <div>
                    <p>Transaction ID: <?php echo $tran_id; ?></p>
                    <p'>Paying Method: <?php echo $pay_info['card_type']; ?></p> 
                </div>    
            </div>
                <div class='overflow-x-auto overflow-y-auto'  style='max-height: 400px;'>

                    <table class='table table-info table-striped table-hover text-center'>
                        <thead>
                            <tr>
                                <th>Connection Name</th>
                                <th>Type</th>
                                <th>Bill Month</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($bill_info = $bill_info_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $bill_info['name']; ?></td>
                                    <td>
                                        <?php 
                                            echo ($bill_info['type'] == 'organizational_plans') ? 'Organizational' : 'Residential'; 
                                        ?>
                                    </td>

                                    <td><?php echo $bill_info['month']; ?></td>
                                    <td><?php echo $bill_info['amount']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
        </div>

    </div>

    

<?php
    //Footer
    include '_footer_common.php';
?>

</body>
</html>
