<?php
require '../../_database_connect.php';

// Extract values from $_POST request
$id = substr($_POST['tran_id'], 0, 19);
$error = $_POST['error'];

// Prepare SQL statements
$cancel_sql = "UPDATE bill SET `payment_id` = NULL WHERE `payment_id` = ?";
$delete_sql = "DELETE FROM `payments` WHERE id = ?";

// Update bill table
$stmt = mysqli_prepare($connect, $cancel_sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);

// Delete from payments table
$stmt = mysqli_prepare($connect, $delete_sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);

//Close the connections
$stmt->close();
$connect->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment failed</title>
</head>
<body>
   <div>
    <h2>Payment failed</h2>
    <p>
        <b>The payment has been <?php echo $error; ?>. You will be redirected shortly.<br>
        Thank You!</b>
    </p>
   </div> 
</body>
</html>

<?php
//redirection to 'customer_info.php?#bill_history'
echo '<script>window.location.replace("../../customer_info.php?#bill_history");</script>';
die();
?>