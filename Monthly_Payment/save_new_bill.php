<?php
include '../condb.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Lease_ID = $_POST['Lease_ID'];
    $Electric_units = $_POST['Electric_units'];
    $Water_units = $_POST['Water_units'];
    $Usage_date = $_POST['Usage_date'];
    $sql = "SELECT Room_number FROM Tenants WHERE Lease_ID = '$Lease_ID'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $Room_number = $row['Room_number'];


    $room_sql = "SELECT Room_price FROM Rooms WHERE Room_number = '$Room_number'";
    $room_result = $con->query($room_sql);
    $room_row = $room_result->fetch_assoc();
    $Room_price = $room_row['Room_price']; 

    $sql = "SELECT SUM(Payment_amount) AS Total_unpaid_amount 
            FROM Monthly_Payment 
            WHERE Lease_ID = '$Lease_ID' AND Payment_status = 'ยังไม่ชำระ'";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();
    $total_unpaid_amount = $row['Total_unpaid_amount'] ?? 0;

    // ดึงค่าซ่อม
    $repair_sql = "SELECT SUM(Repair_cost) AS Total_repair_cost 
                   FROM Repair 
                   WHERE Room_number = '$Room_number' AND Repair_status = 'ยังไม่ชำระ'";
    $repair_result = $con->query($repair_sql);
    $repair_row = $repair_result->fetch_assoc();
    $total_repair_cost = $repair_row['Total_repair_cost'] ?? 0;

    // สร้างบิล
    $payment_date = date('Y-m-d');
    $payment_amount = $Room_price; 
    $payment_status = 'ยังไม่ชำระ';

    $sql = "INSERT INTO Monthly_Payment (Lease_ID, Payment_date, Payment_amount, Payment_status)
            VALUES ('$Lease_ID', '$payment_date', '$payment_amount', '$payment_status')";

    if ($con->query($sql)) {
        $Payment_ID = $con->insert_id;

        $sql = "INSERT INTO Usage_Record (Payment_ID, Rate_ID, Usage_date, Usage_units)
                VALUES ('$Payment_ID', 1, '$Usage_date', '$Electric_units')";
        $con->query($sql);

        $sql = "INSERT INTO Usage_Record (Payment_ID, Rate_ID, Usage_date, Usage_units)
                VALUES ('$Payment_ID', 2, '$Usage_date', '$Water_units')";
        $con->query($sql);

        if ($total_unpaid_amount > 0) {
            $update_sql = "UPDATE Monthly_Payment 
                           SET Payment_amount = Payment_amount + $total_unpaid_amount
                           WHERE Payment_ID = '$Payment_ID'";
            $con->query($update_sql);
        }

        if ($total_repair_cost > 0) {
            $update_sql = "UPDATE Monthly_Payment 
                           SET Payment_amount = Payment_amount + $total_repair_cost
                           WHERE Payment_ID = '$Payment_ID'";
            $con->query($update_sql);

            $update_repair_sql = "UPDATE Repair 
                                  SET Repair_status = 'ชำระแล้ว' 
                                  WHERE Room_number = '$Room_number' AND Repair_status = 'ยังไม่ชำระ'";
            $con->query($update_repair_sql);
        }

        header("Location: utility_billing.php");
    } else {
        echo "เกิดข้อผิดพลาด: " . $con->error;
    }
}

$con->close();
?>