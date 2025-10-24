<?php
include '../condb.php';

$sql = "SELECT t.Lease_ID, t.Room_number, c.Cus_name, c.Cus_lastname
        FROM Tenants t
        JOIN Customer c ON t.Cus_ID = c.Cus_ID";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างบิลใหม่และรวมบิลเก่า</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <div class="card rounded-3 shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-plus me-2"></i>สร้างบิลรายเดือน</h3>
            </div>
            <div class="card-body">
                <form action="save_new_bill.php" method="POST">
                    <div class="mb-3">
                        <label for="Lease_ID" class="form-label">เลือกผู้เช่า</label>
                        <select class="form-select" id="Lease_ID" name="Lease_ID" required>
                            <option value="">เลือกผู้เช่า</option>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <option value="<?php echo $row['Lease_ID']; ?>"><?php echo $row['Room_number'] . ' - ' . $row['Cus_name'] . ' ' . $row['Cus_lastname']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="Electric_units" class="form-label">จำนวนหน่วยไฟฟ้า</label>
                        <input type="number" step="0.01" class="form-control" id="Electric_units" name="Electric_units" required>
                    </div>
                    <div class="mb-3">
                        <label for="Water_units" class="form-label">จำนวนหน่วยน้ำ</label>
                        <input type="number" step="0.01" class="form-control" id="Water_units" name="Water_units" required>
                    </div>
                    <div class="mb-3">
                        <label for="Usage_date" class="form-label">วันที่จด</label>
                        <input type="date" class="form-control" id="Usage_date" name="Usage_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>สร้างบิลใหม่</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<?php $con->close(); ?>