<?php
include '../condb.php';
session_start();

if (!isset($_SESSION['Emp_ID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location.href='../login.php';</script>";
    exit();
}

if (!isset($_GET['Lease_ID']) || empty($_GET['Lease_ID'])) {
    echo "<script>alert('ไม่พบข้อมูลผู้เช่า'); window.location.href='show_tenants.php';</script>";
    exit();
}

$Lease_ID = $_GET['Lease_ID'];

$sql = "
    SELECT t.Lease_ID, c.Cus_name, c.Cus_lastname, c.Cus_tel, c.Cus_email, c.Cus_address, 
           t.Room_number, t.Lease_start, t.Lease_end, t.lease_pdf
    FROM Tenants t
    INNER JOIN Customer c ON t.Cus_ID = c.Cus_ID
    WHERE t.Lease_ID = '$Lease_ID'
";

$result = $con->query($sql);

if ($result->num_rows == 0) {
    echo "<script>alert('ไม่พบข้อมูลผู้เช่า'); window.location.href='show_tenants.php';</script>";
    exit();
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้เช่า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<?php
include 'menu_tenant.php';
?>
    <div class="container py-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-warning bg-gradient text-white py-3">
                <h3 class="card-title mb-0">แก้ไขข้อมูลผู้เช่า</h3>
            </div>
            <div class="card-body p-4">
                <form action="process_edit_tenant.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="Lease_ID" value="<?php echo $row['Lease_ID']; ?>">

                    <div class="row g-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-person-fill me-2"></i>ข้อมูลส่วนตัว
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ชื่อ</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $row['Cus_name']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">นามสกุล</label>
                            <input type="text" name="lastname" class="form-control" value="<?php echo $row['Cus_lastname']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">เบอร์โทรศัพท์</label>
                            <input type="tel" name="tel" class="form-control" value="<?php echo $row['Cus_tel']; ?>" pattern="[0-9]{10}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">อีเมล</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $row['Cus_email']; ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">ที่อยู่</label>
                            <textarea name="address" class="form-control" rows="3" required><?php echo $row['Cus_address']; ?></textarea>
                        </div>

                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3 mt-2">
                                <i class="bi bi-house-fill me-2"></i>ข้อมูลการเช่า
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">หมายเลขห้อง</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $row['Room_number']; ?>" disabled>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">วันเริ่มสัญญา</label>
                            <input type="date" name="lease_start" class="form-control" value="<?php echo $row['Lease_start']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">วันสิ้นสุดสัญญา</label>
                            <input type="date" name="lease_end" class="form-control" value="<?php echo $row['Lease_end']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ไฟล์สัญญาปัจจุบัน</label>
                            <div>
                                <?php if (!empty($row['lease_pdf'])) { ?>
                                    <a href="<?php echo $row['lease_pdf']; ?>" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye"></i> ดูไฟล์
                                    </a>
                                <?php } else { ?>
                                    <span class="text-danger">ไม่มีไฟล์แนบ</span>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">อัปโหลดไฟล์ใหม่ (ถ้ามี)</label>
                            <input type="file" name="pdf_file" class="form-control" accept=".pdf">
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-warning btn-lg px-5 me-2">
                                <i class="bi bi-save me-2"></i>บันทึกการแก้ไข
                            </button>
                            <a href="show_tenants.php" class="btn btn-secondary btn-lg px-5">
                                <i class="bi bi-x-circle me-2"></i>ยกเลิก
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
