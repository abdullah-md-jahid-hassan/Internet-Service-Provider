<?php
session_start();
include 'config/config.php';
include '../_database_connect.php';
include 'lib/SslCommerzNotification.php';

use SslCommerz\SslCommerzNotification;

$customer_id = $_SESSION['id'];  // Ensure session ID is set
$pay = mysqli_real_escape_string($connect, $_POST['pay']); // Amount from pay.php

// Query to get the total due amount
$info_sql = "SELECT
    customer.name AS cu_name,
    customer.email AS cu_email,
    customer.phone AS cu_phone,
    customer.address AS cu_address,
    SUM(bill.amount) AS total_due,
    COUNT(bill.id) AS bill_count 
    FROM connections 
    JOIN bill ON connections.id = bill.connection_id 
    JOIN customer ON connections.customer_id = customer.id
    WHERE connections.customer_id = '{$customer_id}' AND bill.state != 'Paid'";

if($pay!='all'){
    $info_sql .= " AND bill.id = '$pay'";
}
$info_sql .= " GROUP BY customer.id";

$info_result = mysqli_query($connect, $info_sql);
$info = mysqli_fetch_assoc($info_result);
$total_due = $info['total_due'] ?? 0; // Default to 0 if null

//Create the payment
//insert to get id and transaction id
$insert_payment_sql = "INSERT INTO `payments` (`customer_id`) VALUES ('Im ThE UnIqE.?')";
mysqli_query($connect, $insert_payment_sql);

$pay_id_sql = "SELECT id AS py_id FROM payments WHERE customer_id = 'Im ThE UnIqE.?'";
$pay_id_result = mysqli_query($connect, $pay_id_sql);
$pay_id = mysqli_fetch_assoc($pay_id_result);

//Insert the other info
$insert_other_sql = "UPDATE `payments` SET `customer_id`='$customer_id', `amount`='$total_due', `state`='Pending' WHERE id = '$pay_id[py_id]'";

if(mysqli_query($connect, $insert_other_sql)){
    $py_id_to_bill_sql = "UPDATE bill 
    JOIN connections ON connections.id = bill.connection_id 
    SET bill.payment_id = '{$pay_id['py_id']}' 
    WHERE connections.customer_id = '{$customer_id}' AND bill.state != 'Paid'";    

    if($pay!='all'){
        $py_id_to_bill_sql .= " AND bill.id = '$pay'";
    }

    $py_id_to_bill_result = mysqli_query($connect, $py_id_to_bill_sql);
}


// Close the database connection
mysqli_close($connect);


$post_data = array();
$post_data['total_amount'] = $total_due;
$post_data['currency'] = "BDT";
$post_data['tran_id'] = $pay_id['py_id'] . uniqid();
$post_data['success_url'] = "payment/success.php";
$post_data['fail_url'] = "payment/fail.php";
$post_data['cancel_url'] = "payment/cancel.php";
$post_data['cus_name'] = $info['cu_name'];  // Fetch from DB
$post_data['cus_email'] = $info['cu_email'];  // Fetch from DB
$post_data['cus_phone'] = $info['cu_phone'];  // Fetch from DB
$post_data['product_name'] = "Internet Bill";
$post_data['product_category'] = "ISP";
$post_data['product_profile'] = "general";
$post_data['num_of_item'] = $info['bill_count'];
$post_data['ship_name'] = $info['cu_name'];
$post_data['ship_add1'] = $info['cu_address'];
$post_data['ship_city'] = $info['cu_address'];

// Start payment
$sslcommerz = new SslCommerzNotification();
$paymentOptions = $sslcommerz->makePayment($post_data, 'hosted');

if (!is_array($paymentOptions)) {
    echo "SSLCommerz Payment Failed!";
}
?>