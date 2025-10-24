<?php
include '../condb.php';
session_start();

if (isset($_GET['Payment_ID']) && isset($_SESSION['Emp_ID'])) {
    $Payment_ID = $_GET['Payment_ID'];
    $Emp_ID = $_SESSION['Emp_ID'];

    // แสดงกล่องข้อความยืนยัน
    echo "<script>
            if (confirm('คุณแน่ใจหรือไม่ว่าต้องการยืนยันการชำระเงินนี้?')) {
                window.location.href = 'confirm_payment.php?Payment_ID=$Payment_ID&Emp_ID=$Emp_ID';
            } else {
                window.location.href = 'utility_billing.php';
            }
          </script>";
} else {
    echo "ไม่พบข้อมูล Payment_ID หรือพนักงานไม่ได้เข้าสู่ระบบ";
}

$con->close();
?>