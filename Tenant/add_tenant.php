<?php
include '../condb.php';
session_start();
if (!isset($_SESSION['Emp_ID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location.href='../login.php';</script>";
    exit();
}

$emp_id = $_SESSION['Emp_ID'];

$sql = "SELECT Emp_name, Emp_lastname FROM Employee WHERE Emp_ID = $emp_id";
$result = mysqli_query($con, $sql);

$emp_name = '';
$emp_lastname = '';
if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $emp_name = $row['Emp_name'];
    $emp_lastname = $row['Emp_lastname'];
}

$room_sql = "SELECT room_number FROM Rooms WHERE Room_status = 'ว่าง'";
$room_result = mysqli_query($con, $room_sql);

$rooms = [];
while ($row = mysqli_fetch_assoc($room_result)) {
    $rooms[] = $row['room_number'];
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มผู้เช่า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    
    <div class="container py-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary bg-gradient text-white py-3">
                <h3 class="card-title mb-0">เพิ่มผู้เช่า</h3>
            </div>
            <div class="card-body p-4">
                <form action="process_add_tenant.php" method="POST" enctype="multipart/form-data">
                    <div class="row g-4">

                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="bi bi-person-fill me-2"></i>ข้อมูลส่วนตัว
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">ชื่อ</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">นามสกุล</label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">เบอร์โทรศัพท์</label>
                            <input type="tel" name="tel" class="form-control" pattern="[0-9]{10}" title="กรุณากรอกเบอร์โทรศัพท์ 10 หลัก" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">อีเมล</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">ที่อยู่</label>
                            <textarea name="address" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3 mt-2">
                                <i class="bi bi-house-fill me-2"></i>ข้อมูลการเช่า
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">หมายเลขห้อง</label>
                            <select name="room_number" class="form-select" required>
                                <option value="">-- เลือกหมายเลขห้อง --</option>
                                <?php foreach ($rooms as $room) { ?>
                                    <option value="<?php echo $room; ?>"><?php echo $room; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">วันเริ่มสัญญา</label>
                            <input type="date" name="lease_start" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">วันสิ้นสุดสัญญา</label>
                            <input type="date" name="lease_end" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">สถานะสัญญา</label>
                            <select name="lease_status" class="form-select" required>
                                <option value="ใช้งานอยู่">ใช้งานอยู่</option>
                                <option value="สิ้นสุดแล้ว">สิ้นสุดแล้ว</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">อัปโหลดสัญญาเช่า (PDF)</label>
                            <input type="file" name="pdf_file" class="form-control" accept=".pdf" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">พนักงานผู้ทำสัญญา</label>
                            <input type="text" class="form-control bg-light" value="<?php echo $emp_name . ' ' . $emp_lastname; ?>" disabled>
                            <input type="hidden" name="emp_id" value="<?php echo $emp_id; ?>">
                        </div>

                        <div class="col-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5 me-2">
                                <i class="bi bi-save me-2"></i>เพิ่มผู้เช่า
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