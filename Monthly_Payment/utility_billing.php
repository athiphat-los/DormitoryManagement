<?php
include '../condb.php';

$sql = "SELECT 
            mp.Payment_ID,
            mp.Payment_date,
            mp.Payment_amount,
            mp.Payment_status,
            mp.Emp_ID,
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
        GROUP BY 
            mp.Payment_ID";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ค่าน้ำค่าไฟและบิลรายเดือน</title>
    <style>
        .table-container {
            width: 100%;
            overflow-x: auto;
        }
    </style>
</head>
<body class="bg-light">
<?php
include 'menu_Monthly.php';
?>
    <div class="container-fluid py-4">
        <div class="card rounded-3 shadow">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-file-invoice me-2"></i>บิลรายเดือน</h3>
                    <a href="add_utility.php" class="btn btn-light"><i class="fas fa-plus me-1"></i>เพิ่มค่าน้ำค่าไฟ</a>
                </div>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-container">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr class="text-center">
                                    <th>หมายเลขห้อง</th>
                                    <th>ชื่อผู้เช่า</th>
                                    <th>นามสกุล</th>
                                    <th>วันที่ออกบิล</th>
                                    <th>ค่าน้ำค่าไฟ</th>
                                    <th>ค่าเช่า</th>
                                    <th>รวมทั้งหมด</th>
                                    <th>สถานะการชำระ</th>
                                    <th>พนักงานรับเงิน</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $row['Room_number']; ?></td>
                                        <td><?php echo $row['Cus_name']; ?></td>
                                        <td><?php echo $row['Cus_lastname']; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['Payment_date'])); ?></td>
                                        <td><?php echo number_format($row['Total_utility_cost'], 2); ?></td>
                                        <td><?php echo number_format($row['Payment_amount'], 2); ?></td>
                                        <td><?php echo number_format($row['Total_amount_due'], 2); ?></td>
                                        <td><?php echo $row['Payment_status']; ?></td>
                                        <td>
                                            <?php
                                            if ($row['Emp_ID']) {
                                                $emp_sql = "SELECT Emp_name, Emp_lastname FROM Employee WHERE Emp_ID = " . $row['Emp_ID'];
                                                $emp_result = $con->query($emp_sql);
                                                if ($emp_result->num_rows > 0) {
                                                    $emp_row = $emp_result->fetch_assoc();
                                                    echo $emp_row['Emp_name'] . ' ' . $emp_row['Emp_lastname'];
                                                } else {
                                                    echo 'ไม่พบข้อมูล';
                                                }
                                            } else {
                                                echo 'ยังไม่ชำระ';
                                            }
                                            ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <?php if ($row['Payment_status'] == 'ยังไม่ชำระ'): ?>
                                                    <a href="pay_bill.php?Payment_ID=<?php echo $row['Payment_ID']; ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-money-bill me-1"></i>ชำระเงิน
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-check me-1"></i>ชำระแล้ว
                                                    </button>
                                                <?php endif; ?>
                                                <a href="view_bill.php?Payment_ID=<?php echo $row['Payment_ID']; ?>" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye me-1"></i>ดูบิล
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center p-4 rounded">
                        <i class="fas fa-exclamation-triangle me-2"></i>ไม่พบข้อมูลบิล
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

<?php $con->close(); ?>