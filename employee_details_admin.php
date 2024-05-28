<?php
session_start();
if (isset($_SESSION["employee_id_details"])) {
    $temp = $_SESSION["employee_id_details"];
    unset($_SESSION["employee_id_details"]);
}
echo $temp;

?>