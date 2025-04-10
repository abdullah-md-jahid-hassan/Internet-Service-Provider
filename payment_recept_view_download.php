<?php
// Setting Time Zone
date_default_timezone_set('Asia/Dhaka');

// Import View and download recept functions
require_once 'function\pdf_view_download.php';

//Get data form $_POST
if (isset($_POST['view'])) {
    $action = 'view';
} elseif (isset($_POST['download'])) {
    $action = 'download';
}else {
    if (isset($_SESSION['user'])  && 
    isset($_SESSION['user'])){
        if ($_SESSION['user']=='customer'){
            echo '<script> window.location.replace("customer_info.php?#bill_history");</script>';
        } elseif ($_SESSION['user']=='admin'  || $_SESSION['user']=='sup_admin'){
            echo '<script> window.location.replace("dash_admin.php");</script>';
        } else {
            echo '<script> window.location.replace("logout.php");</script>';
        }
        die();
    }
}
$tran_id = $_POST['tran_id'];

//Current date and month
$current_dt_ti = new DateTime();
$now = $current_dt_ti->format('jS F, Y - h:i A');

// connect to the database
require '_database_connect.php';

// Get Org info
$org_info_sql = "SELECT address_text, phone_text, mail_text FROM footer_data WHERE 1";
$find_org_info = mysqli_query($connect, $org_info_sql);
$org_info = mysqli_fetch_assoc($find_org_info);

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

// Getting connection and payment Info
$cu_py_info_sql = "SELECT 
    customer.id AS cu_id,
    customer.name AS cu_name,
    customer.address AS cu_address,
    customer.phone AS cu_phone,
    customer.email AS cu_mail,
    DATE_FORMAT(payments.pay_date, '%D %M, %Y - %h:%i %p') AS dt_ti,
    payments.amount AS amount,
    payments.currency AS currency,
    payments.card_type AS method
    FROM payments
    JOIN customer ON payments.customer_id = customer.id
    WHERE payments.tran_id = ?";
$stmt = $connect->prepare($cu_py_info_sql);
$stmt->bind_param('s', $tran_id);
$stmt->execute();
$cu_py_info_result = $stmt->get_result();
$cu_py_info = $cu_py_info_result->fetch_assoc();

// Getting bill info
$bill_info_sql = "SELECT DISTINCT
    connections.name AS con_name,
    connections.type AS con_type,
    DATE_FORMAT(bill.due_date, '%M, %Y') AS bill_month,
    bill.amount AS bill_amount
    FROM bill
    JOIN payments ON bill.payment_id = payments.id
    JOIN connections ON bill.connection_id = connections.id
    WHERE payments.tran_id = ? AND connections.customer_id = ?";

$stmt = $connect->prepare($bill_info_sql);
$stmt->bind_param('ss', $tran_id, $cu_py_info['cu_id']);  // Both parameters as strings
$stmt->execute();
$bill_info_result = $stmt->get_result();

// Close the database connection
mysqli_close($connect);

$recept_html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'>
</head>
<body>
    <div style='width: 100%; padding-right: 12px; padding-left: 12px margin-right: auto; margin-left: auto;'>
        <!-- Header -->
        <div style='margin: 0.5rem; padding: 0.5rem; background-color: #f8f9fa; border-radius: 0.25rem; border: 1px solid #ffc107; text-align: center;'>

            <img style='width: 35px; height: 35px; border-radius: 50%; object-fit: cover;' src='images/logo/title_logo1.png'>
            <h5><b>Family Internet Service Provider</b></h5>
            <h3>Speed is our Identity</h3>
            <h2>".$org_info['address_text']."</h2>
            <h2>".$org_info['phone_text']."  ||  ".$org_info['mail_text']."</h2>
        </div>

        <!-- Customer and Payment -->
        <div style='margin: 0.5rem; padding: 0.5rem; background-color: #f8f9fa; border-radius: 0.25rem; border: 1px solid #0d6efd;'>
            <div style='margin-bottom: 0.5rem; text-align: center; border-bottom: 1px solid #0d6efd;'>
                <h5 style='margin: 0;'>Payment Receipt</h5>
                <p style='margin: 0;'>Generated no: ".$now." (GMT+6)</p>
            </div>
            <div>
                <div style='width: 50%; float: left;'>
                    <h5><u>Customer Details</u></h5>
                    <p style='margin: 0;'><b>Name:</b> ".$cu_py_info['cu_name']."</p>
                    <p style='margin: 0;'><b>Address:</b> ".$cu_py_info['cu_address']."</p>
                    <p style='margin: 0;'><b>Phone:</b> ".$cu_py_info['cu_phone']."</p>
                    <p style='margin: 0;'><b>Email:</b> ".$cu_py_info['cu_mail']."</p>
                </div>
                <div style='width: 50%; float: right;'>
                    <h5><u>Payment Details</u></h5>
                    <p style='margin: 0;'><b>Transaction ID:</b> ".$tran_id."</p>
                    <p style='margin: 0;'><b>Amount:</b> ".$cu_py_info['amount'].' '.$cu_py_info['currency']."</p>
                    <p style='margin: 0;'><b>Pay On:</b> ".$cu_py_info['dt_ti']."</p>
                    <p style='margin: 0;'><b>Payment Method:</b> ".$cu_py_info['method']."</p>
                </div>
            </div>
        </div>

        <!-- Payment info -->
        <div style='width: 100%; background-color: #f8f9fa; padding-right: 12px; padding-left: 12px margin-right: auto; margin-left: auto;'>
            <div style='padding: 0.5rem; background-color: #f8f9fa; border: 1px solid #198754; border-top-left-radius: 0.25rem; border-top-right-radius: 0.25rem; display: flex; align-items: center;'>
                <h5>Bill and Connection Information</h5>
            </div>
            <div style='background-color: #f8f9fa; border: 1px solid #198754;'>";

                $recept_html .= "
                <table style='width: 100%; border-collapse: collapse; margin-bottom: 1rem; color: #212529; vertical-align: top; border-color: #dee2e6;'>
                    <thead>
                        <tr style='background-color:rgb(160, 201, 241);'>
                            <th style='width: 30%; border: 1px solid #dee2e6; padding: 8px; text-align: left;'>Connection Name</th>
                            <th style='width: 20%; border: 1px solid #dee2e6; padding: 8px; text-align: left;'>Type</th>
                            <th style='width: 25%; border: 1px solid #dee2e6; padding: 8px; text-align: left;'>Bill Month</th>
                            <th style='width: 25%; border: 1px solid #dee2e6; padding: 8px; text-align: left;'>Amount</th>
                        </tr>
                    </thead>
                    <tbody>";
                
                while ($bill_info = $bill_info_result->fetch_assoc()) {
                    $recept_html .= "
                        <tr>
                            <td style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>" . htmlspecialchars($bill_info['con_name']) . "</td>
                            <td style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>" . 
                                ($bill_info['con_type'] == "organizational_plans" ? "Organizational" : "Residential") . "</td>
                            <td style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>" . htmlspecialchars($bill_info['bill_month']) . "</td>
                            <td style='border: 1px solid #dee2e6; padding: 8px; text-align: left;'>" . htmlspecialchars($bill_info['bill_amount']) . "</td>
                        </tr>";
                }
                
                $recept_html .= '
                    </tbody>
                </table>';

            $recept_html .= "
            </div>
        </div>

        <div style='margin-top: 1.5rem; text-align: center;'>
            <p>This bill generated automatically by the 'Family ISP Server'</p>
        </div>
    </div>
</body>
</html>";

// Choose to download or view
if ($action == 'view') {
    viewPDF($recept_html, $tran_id . date('Y-m-d'));
} elseif ($action == 'download') {
    downloadPDF($recept_html, $tran_id . date('Y-m-d'));
}

?>