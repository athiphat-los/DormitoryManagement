<?php
include '../condb.php';

$sql = "SELECT t.Lease_ID, t.Room_number, t.Lease_start, t.Lease_end, t.Emp_ID, t.lease_pdf, t.Lease_status, c.Cus_name, c.Cus_lastname, c.Cus_tel, c.Cus_email, c.Cus_address
        FROM Tenants t
        JOIN Customer c ON t.Cus_ID = c.Cus_ID";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลผู้เช่า</title>

    <style>
        .room-col { width: 80px; }
        .name-col { min-width: 150px; }
        .lastname-col { min-width: 150px; }
        .tel-col { width: 120px; }
        .email-col { min-width: 200px; }
        .address-col { min-width: 250px; }
        .date-col { width: 100px; }
        .action-col { width: 180px; }
    
        .table-container {
            width: 100%;
            overflow-x: auto;
        }
        
        .container-fluid {
            padding: 20px;
        }
    </style>
</head>

<body class="bg-light">
<?php
include 'menu_tenant.php';
?>
    <div class="container-fluid py-4">
        <div class="card rounded-3 shadow">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="mb-0"><i class="fas fa-users me-2"></i>ข้อมูลผู้เช่าทั้งหมด</h3>
                    <a href="add_tenant.php" class="btn btn-light"><i class="fas fa-plus me-1"></i>เพิ่มผู้เช่าใหม่</a>
                </div>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-container">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr class="text-center">
                                    <th class="room-col">หมายเลขห้อง</th>
                                    <th class="name-col">ชื่อผู้เช่า</th>
                                    <th class="lastname-col">นามสกุล</th>
                                    <th class="tel-col">เบอร์โทร</th>
                                    <th class="email-col">อีเมล</th>
                                    <th class="address-col">ที่อยู่</th>
                                    <th class="date-col">วันเริ่มเช่า</th>
                                    <th class="date-col">วันสิ้นสุดการเช่า</th>
                                    <th class="action-col">การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $row['Room_number']; ?></td>
                                        <td><?php echo $row['Cus_name']; ?></td>
                                        <td><?php echo $row['Cus_lastname']; ?></td>
                                        <td><?php echo $row['Cus_tel']; ?></td>
                                        <td><?php echo $row['Cus_email']; ?></td>
                                        <td><?php echo $row['Cus_address']; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['Lease_start'])); ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($row['Lease_end'])); ?></td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <?php if (!empty($row['lease_pdf'])): ?>
                                                    <a href="lease_details.php?room=<?php echo $row['Room_number']; ?>" class="btn btn-info btn-sm text-white">
                                                        <i class="fas fa-file-contract me-1"></i>ดูสัญญา
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-file-excel me-1"></i>ไม่มีไฟล์
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($row['Lease_status'] == 'ใช้งานอยู่'): ?>
                                                    <a href="change_status.php?Lease_ID=<?php echo $row['Lease_ID']; ?>&Room_number=<?php echo $row['Room_number']; ?>" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check me-1"></i>เปลี่ยนสถานะเป็นสิ้นสุดแล้ว
                                                    </a>
                                                <?php else: ?>
                                                    <button class="btn btn-secondary btn-sm" disabled>
                                                        <i class="fas fa-check me-1"></i>สิ้นสุดแล้ว
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center p-4 rounded">
                        <i class="fas fa-exclamation-triangle me-2"></i>ไม่พบข้อมูลการเช่า
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">แสดงข้อมูลทั้งหมด <?php echo $result ? $result->num_rows : 0; ?> รายการ</small>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php $con->close(); ?>