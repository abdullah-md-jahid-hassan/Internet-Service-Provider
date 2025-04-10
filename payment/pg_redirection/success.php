<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>
<body>
<div class="container">
    <div class="card text-center shadow-lg border-0 mt-5 mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <div class="text-success mb-3">
                <i class="fa fa-check-circle fa-5x"></i>
            </div>
            <h3 class="card-title font-weight-bold">Thank You!</h3>
            <p class="card-text text-muted">Your payment was successful.</p>
            <hr>
            <p class="font-weight-bold">Transaction ID:</p>
            <p class="text-primary" id="transaction_id"><?php echo $_POST['tran_id'] ; ?></p>
        </div>
    </div>
</div>
</body>
</html>


<?php
require '../../_database_connect.php';

// Retrieve and sanitize values from $_POST
$id = substr($_POST['tran_id'], 0, 19);
$tran_id = $_POST['tran_id'];
$val_id = $_POST['val_id'];
$card_type = $_POST['card_type'];
$store_amount = $_POST['store_amount'];
$bank_tran_id = $_POST['bank_tran_id'];
$tran_status = $_POST['status'];
$tran_date = $_POST['tran_date'];
$card_issuer = $_POST['card_issuer'];
$card_brand = $_POST['card_brand'];
$card_sub_brand = $_POST['card_sub_brand'];
$card_issuer_country = $_POST['card_issuer_country'];
$card_issuer_country_code = $_POST['card_issuer_country_code'];
$store_id = $_POST['store_id'];
$verify_sign = $_POST['verify_sign'];
$verify_sign_sha2 = $_POST['verify_sign_sha2'];
$risk_level = $_POST['risk_level'];
$risk_title = $_POST['risk_title'];

// Prepare the SQL query
$stmt = $connect->prepare("UPDATE payments
    JOIN bill ON payments.id = bill.payment_id
    SET
    payments.state = 'Paid',
    payments.tran_id = ?,
    payments.val_id = ?,
    payments.card_type = ?,
    payments.store_amount = ?,
    payments.bank_tran_id = ?,
    payments.tran_status = ?,
    payments.pay_date = ?,
    payments.card_issuer = ?,
    payments.card_brand = ?,
    payments.card_sub_brand = ?,
    payments.card_issuer_country = ?,
    payments.card_issuer_country_code = ?,
    payments.store_id = ?,
    payments.verify_sign = ?,
    payments.verify_sign_sha2 = ?,
    payments.risk_level = ?,
    payments.risk_title = ?,
    bill.state = 'Paid'
    WHERE payments.id = ?");

// Bind parameters (18 parameters = 18 's' characters)
$stmt->bind_param(
    'ssssssssssssssssss',
    $tran_id, $val_id, $card_type, $store_amount, $bank_tran_id, $tran_status, 
    $tran_date, $card_issuer, $card_brand, $card_sub_brand, $card_issuer_country, 
    $card_issuer_country_code, $store_id, $verify_sign, $verify_sign_sha2, 
    $risk_level, $risk_title, $id
);

// Execute
if ($stmt->execute()) {
    //Get customer id
    $stmt = $connect->prepare("SELECT `customer_id` FROM `payments` WHERE `id`=?");
    $stmt->bind_param('s', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cus_id = $result->fetch_assoc();

    //Close the connections
    $stmt->close();
    $connect->close();

    //Set login data
    session_start();
    $_SESSION['user'] = "customer";
    $_SESSION['id'] = "$cus_id[customer_id]";
    
    //redirection to the payment_recept with tran_id
    echo '<script>window.location.replace("../../payment_recept.php?tran_id=' . urlencode($tran_id) . '");</script>';
    die();
} else {
    echo "Error: " . $stmt->error;
}

//Close the connections
$stmt->close();
$connect->close();
?>