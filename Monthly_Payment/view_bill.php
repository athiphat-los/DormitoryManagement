<?php
include '../condb.php';

if (isset($_GET['Payment_ID'])) {
    $Payment_ID = $_GET['Payment_ID'];

    // ดึงข้อมูลบิล
    $sql = "SELECT 
                mp.Payment_ID,
                mp.Payment_date,
                mp.Payment_amount,
                mp.Payment_status,
                t.Room_number,
                c.Cus_name,
                c.Cus_lastname,
                SUM(ur.Total_price) AS Total_utility_cost,
                (mp.Payment_amount + SUM(ur.Total_price)) AS Total_amount_due
            FROM 
                Monthly_Payment mp
            JOIN 
                Tenants t ON mp.Lease_ID = t.Lease_ID
            JOIN 
                Customer c ON t.Cus_ID = c.Cus_ID
            LEFT JOIN 
                Usage_Record ur ON mp.Payment_ID = ur.Payment_ID
            WHERE 
                mp.Payment_ID = '$Payment_ID'
            GROUP BY 
                mp.Payment_ID";
    $result = $con->query($sql);
    $row = $result->fetch_assoc();

    // ดึงข้อมูลค่าน้ำค่าไฟ (ไฟฟ้าและน้ำ)
    $utility_sql = "SELECT 
                        ur.Usage_units, 
                        ur.Usage_date, 
                        ur.Rate_ID, 
                        ur.Total_price, 
                        u.Rate_type 
                    FROM 
                        Usage_Record ur
                    JOIN 
                        UnitRate u ON ur.Rate_ID = u.Rate_ID
                    WHERE 
                        ur.Payment_ID = '$Payment_ID'";
    $utility_result = $con->query($utility_sql);

    // ดึงข้อมูลค่าซ่อมที่ยังไม่ชำระ
    $repair_sql = "SELECT 
                        Repair_detail, 
                        Repair_cost, 
                        Repair_date 
                    FROM 
                        Repair 
                    WHERE 
                        Room_number = '" . $row['Room_number'] . "' 
                        AND Repair_status = 'ยังไม่ชำระ'";
    $repair_result = $con->query($repair_sql);

    // ดึงข้อมูลค่าบริการเพิ่มเติม
    $extra_sql = "SELECT 
                        Extra_name, 
                        Extra_price 
                    FROM 
                        Rooms_Extra 
                    WHERE 
                        Room_number = '" . $row['Room_number'] . "'";
    $extra_result = $con->query($extra_sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ดูบิล</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .bill-details {
            margin-bottom: 20px;
        }
        .bill-details h5 {
            margin-bottom: 10px;
        }
        .bill-details ul {
            list-style-type: none;
            padding-left: 0;
        }
        .bill-details ul li {
            margin-bottom: 5px;
        }
        .action-buttons {
            margin-top: 20px;
            text-align: center;
        }
        .action-buttons .btn {
            margin: 5px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="card rounded-3 shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-file-invoice me-2"></i>บิลรายเดือน</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>หมายเลขห้อง:</strong> <?php echo $row['Room_number']; ?></p>
                        <p><strong>ชื่อผู้เช่า:</strong> <?php echo $row['Cus_name'] . ' ' . $row['Cus_lastname']; ?></p>
                        <p><strong>วันที่ออกบิล:</strong> <?php echo date('d/m/Y', strtotime($row['Payment_date'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>ค่าเช่า:</strong> <?php echo number_format($row['Payment_amount'], 2); ?></p>
                        <p><strong>รวมทั้งหมด:</strong> <?php echo number_format($row['Total_amount_due'], 2); ?></p>
                        <p><strong>สถานะการชำระ:</strong> <?php echo $row['Payment_status']; ?></p>
                    </div>
                </div>
                <hr>

                <!-- แสดงค่าน้ำค่าไฟ -->
                <div class="bill-details">
                    <h5>ค่าน้ำค่าไฟ</h5>
                    <ul>
                        <?php while($utility_row = $utility_result->fetch_assoc()): ?>
                            <li>
                                <?php 
                                    if ($utility_row['Rate_ID'] == 1) {
                                        echo "ไฟฟ้า: " . $utility_row['Usage_units'] . " หน่วย - " . number_format($utility_row['Total_price'], 2) . " บาท";
                                    } elseif ($utility_row['Rate_ID'] == 2) {
                                        echo "น้ำ: " . $utility_row['Usage_units'] . " หน่วย - " . number_format($utility_row['Total_price'], 2) . " บาท";
                                    }
                                ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <!-- แสดงค่าซ่อม -->
                <div class="bill-details">
                    <h5>ค่าซ่อม</h5>
                    <ul>
                        <?php while($repair_row = $repair_result->fetch_assoc()): ?>
                            <li>
                                <?php echo $repair_row['Repair_detail'] . " - " . number_format($repair_row['Repair_cost'], 2) . " บาท"; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <!-- แสดงค่าบริการเพิ่มเติม -->
                <div class="bill-details">
                    <h5>ค่าบริการเพิ่มเติม</h5>
                    <ul>
                        <?php while($extra_row = $extra_result->fetch_assoc()): ?>
                            <li>
                                <?php echo $extra_row['Extra_name'] . " - " . number_format($extra_row['Extra_price'], 2) . " บาท"; ?>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

                <!-- ปุ่มชำระเงินและพิมพ์บิล -->
                <div class="action-buttons">
                    <?php if ($row['Payment_status'] == 'ยังไม่ชำระ'): ?>
                        <a href="pay_bill.php?Payment_ID=<?php echo $row['Payment_ID']; ?>" class="btn btn-success">
                            <i class="fas fa-money-bill me-1"></i>ชำระเงิน
                        </a>
                    <?php else: ?>
                        <button class="btn btn-secondary" disabled>
                            <i class="fas fa-check me-1"></i>ชำระแล้ว
                        </button>
                    <?php endif; ?>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print me-1"></i>พิมพ์บิล
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
} else {
    echo "<div class='alert alert-danger text-center p-4 rounded'>
            <i class='fas fa-exclamation-triangle me-2'></i>ไม่พบ Payment_ID
          </div>";
}

$con->close();
?>