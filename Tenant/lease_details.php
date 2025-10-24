<?php
include '../condb.php';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดการเช่า</title>
    
</head>
<body class="bg-light">
    <div class="container py-4">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard.php">หน้าหลัก</a></li>
                <li class="breadcrumb-item"><a href="show_tenants.php">ข้อมูลผู้เช่า</a></li>
                <li class="breadcrumb-item active" aria-current="page">รายละเอียดการเช่า</li>
            </ol>
        </nav>
        <?php
        if (isset($_GET['room'])) {
            $room_number = mysqli_real_escape_string($con, $_GET['room']); 

            $sql = "SELECT t.Lease_ID, t.Room_number, t.Lease_start, t.Lease_end, t.Emp_ID, t.lease_pdf,
                    c.Cus_name, c.Cus_lastname, c.Cus_tel, c.Cus_email, c.Cus_address,
                    GROUP_CONCAT(CONCAT(re.Extra_name, ' (', re.Extra_price, ' บาท)') SEPARATOR ', ') AS Rooms_Extra
                    FROM Tenants t
                    JOIN Customer c ON t.Cus_ID = c.Cus_ID
                    LEFT JOIN Rooms_Extra re ON t.Room_number = re.Room_number
                    WHERE t.Room_number = '$room_number'
                    GROUP BY t.Room_number";
            $result = mysqli_query($con, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                ?>
                
                <div class="card shadow rounded-3 mb-4">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-file-contract me-2"></i>รายละเอียดการเช่า ห้อง <?php echo $row['Room_number']; ?></h4>
                    </div>
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row mb-4">
                                    <div class="col-md-12 mb-3">
                                        <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-user me-2"></i>ข้อมูลผู้เช่า</h5>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-2"><strong>ชื่อ-นามสกุล:</strong></p>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-primary"><i class="fas fa-id-card"></i></span>
                                            <input type="text" class="form-control bg-white" value="<?php echo $row['Cus_name'] . ' ' . $row['Cus_lastname']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-2"><strong>เบอร์โทร:</strong></p>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-primary"><i class="fas fa-phone"></i></span>
                                            <input type="text" class="form-control bg-white" value="<?php echo $row['Cus_tel']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-2"><strong>อีเมล:</strong></p>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-primary"><i class="fas fa-envelope"></i></span>
                                            <input type="text" class="form-control bg-white" value="<?php echo $row['Cus_email']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-2"><strong>รหัสห้อง:</strong></p>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-primary"><i class="fas fa-home"></i></span>
                                            <input type="text" class="form-control bg-white" value="<?php echo $row['Room_number']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p class="mb-2"><strong>ที่อยู่:</strong></p>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-primary"><i class="fas fa-map-marker-alt"></i></span>
                                            <textarea class="form-control bg-white" rows="2" readonly><?php echo $row['Cus_address']; ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12 mb-3">
                                        <h5 class="border-bottom pb-2 text-primary"><i class="fas fa-calendar-alt me-2"></i>ข้อมูลการเช่า</h5>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-2"><strong>วันเริ่มเช่า:</strong></p>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-primary"><i class="fas fa-calendar-plus"></i></span>
                                            <input type="text" class="form-control bg-white" value="<?php echo date('d/m/Y', strtotime($row['Lease_start'])); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-2"><strong>วันสิ้นสุดการเช่า:</strong></p>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-primary"><i class="fas fa-calendar-times"></i></span>
                                            <input type="text" class="form-control bg-white" value="<?php echo date('d/m/Y', strtotime($row['Lease_end'])); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <p class="mb-2"><strong>สิ่งอำนวยความสะดวกเพิ่มเติม:</strong></p>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light text-primary"><i class="fas fa-plus-circle"></i></span>
                                            <textarea class="form-control bg-white" rows="2" readonly><?php echo (!empty($row['Rooms_Extra']) ? $row['Rooms_Extra'] : "ไม่มี"); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- ส่วนแสดง PDF และปุ่มดำเนินการ 4 คอลัมน์ -->
                            <div class="col-md-4">
                                <div class="card border-primary h-100">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-file-pdf me-2"></i>ไฟล์สัญญาเช่า</h5>
                                    </div>
                                    <div class="card-body text-center d-flex flex-column align-items-center justify-content-center">
                                        <?php if (!empty($row['lease_pdf'])) { ?>
                                            <div class="mb-4">
                                            <iframe src="<?php echo $row['lease_pdf']; ?>" width="100%" height="600px"></iframe>
                                            </div>
                                            <div class="mt-auto">                                    
                                                <a href="<?php echo $row['lease_pdf']; ?>" target="_blank" class="btn btn-primary btn-lg w-100 mb-2">
                                                    <i class="fas fa-eye me-2"></i>เปิดไฟล์สัญญา
                                                </a>
                                                <a href="<?php echo $row['lease_pdf']; ?>" download class="btn btn-outline-primary w-100">
                                                    <i class="fas fa-download me-2"></i>ดาวน์โหลดสัญญา
                                                </a>
                                            </div>
                                        <?php } else { ?>
                                            <div class="text-center my-5">
                                                <i class="fas fa-exclamation-circle text-warning fa-5x mb-3"></i>
                                                <h5>ไม่พบไฟล์สัญญา</h5>
                                                <p class="text-muted">ยังไม่มีการอัพโหลดไฟล์สัญญาสำหรับห้องนี้</p>
                                            </div>
                                            <div class="mt-auto">
                                                <button type="button" class="btn btn-outline-primary w-100">
                                                    <i class="fas fa-upload me-2"></i>อัพโหลดสัญญา
                                                </button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="show_tenants.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>กลับสู่หน้ารายการ
                            </a>
                            <div>
                            <button type="button" class="btn btn-warning me-2"
                                onclick="window.location.href='edit_tenant.php?Lease_ID=<?php echo $row['Lease_ID']; ?>'">
                                <i class="fas fa-edit me-2"></i>แก้ไขข้อมูล
                            </button>
                                <button type="button" class="btn btn-danger">
                                    <i class="fas fa-trash-alt me-2"></i>ยกเลิกสัญญา
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php
            } else {
                ?>
                <div class="alert alert-warning shadow-sm">
                    <div class="text-center p-4">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                        <h4>ไม่พบข้อมูลการเช่า</h4>
                        <p>ไม่พบข้อมูลการเช่าสำหรับห้องหมายเลข <?php echo $room_number; ?></p>
                        <a href="tenants.php" class="btn btn-warning mt-2">
                            <i class="fas fa-arrow-left me-2"></i>กลับสู่หน้ารายการ
                        </a>
                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-info shadow-sm">
                <div class="text-center p-4">
                    <i class="fas fa-info-circle fa-3x mb-3 text-info"></i>
                    <h4>กรุณาเลือกห้อง</h4>
                    <p>คุณยังไม่ได้เลือกหมายเลขห้องที่ต้องการดูข้อมูล</p>
                    <a href="tenants.php" class="btn btn-info mt-2 text-white">
                        <i class="fas fa-list me-2"></i>ไปยังหน้ารายการห้อง
                    </a>
                </div>
            </div>
            <?php
        }
        mysqli_close($con);
        ?>
    </div>

</body>
</html>