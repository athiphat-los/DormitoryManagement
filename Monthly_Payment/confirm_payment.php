<?php
include '../condb.php';
session_start();

if (isset($_GET['Payment_ID']) && isset($_GET['Emp_ID'])) {
    $Payment_ID = $_GET['Payment_ID'];
    $Emp_ID = $_GET['Emp_ID'];

    $sql = "UPDATE Monthly_Payment 
            SET Payment_status = 'ชำระแล้ว', Emp_ID = '$Emp_ID'
            WHERE Payment_ID = '$Payment_ID'";

    if ($con->query($sql)) {
        header("Location: utility_billing.php");
    } else {
        echo "เกิดข้อผิดพลาด: " . $con->error;
    }
} else {
    echo "ไม่พบข้อมูล Payment_ID หรือพนักงานไม่ได้เข้าสู่ระบบ";
}

$con->close();
?>