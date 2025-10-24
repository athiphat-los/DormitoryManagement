<?php
include '../condb.php';
session_start();

if (!isset($_SESSION['Emp_ID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location.href='../login.php';</script>";
    exit();
}

$customer_query = "SELECT * FROM Customer";
$customer_result = mysqli_query($con, $customer_query);
$customers = [];
while ($row = mysqli_fetch_assoc($customer_result)) {
    $customers[] = $row;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการลูกค้าทั้งหมด</title>
</head>
<body class="bg-light">
<?php
include 'menu_customer.php';
?>
    <div class="container py-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary bg-gradient text-white py-3">
                <h3 class="card-title mb-0">รายการลูกค้าทั้งหมด</h3>
            </div>
            <div class="card-body p-4">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>รหัสลูกค้า</th>
                            <th>ชื่อ</th>
                            <th>นามสกุล</th>
                            <th>เบอร์โทรศัพท์</th>
                            <th>อีเมล</th>
                            <th>ที่อยู่</th>
                            <th>อื่นๆ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $customer) { ?>
                            <tr>
                                <td><?php echo $customer['Cus_ID']; ?></td>
                                <td><?php echo $customer['Cus_name']; ?></td>
                                <td><?php echo $customer['Cus_lastname']; ?></td>
                                <td><?php echo $customer['Cus_tel']; ?></td>
                                <td><?php echo $customer['Cus_email']; ?></td>
                                <td><?php echo $customer['Cus_address']; ?></td>
                                <td>
                                    <a href="add_lease.php?customer_id=<?php echo $customer['Cus_ID']; ?>" class="btn btn-primary btn-sm">
                                        <i class="bi bi-file-earmark-plus me-1"></i>ทำสัญญาใหม่
                                    </a>
                                    <a href="edit_customer.php?customer_id=<?php echo $customer['Cus_ID']; ?>" class="btn btn-primary disabled btn-sm" btn-sm">
                                        <i class="bi bi-file-earmark-plus me-1"></i>แก้ไขข้อมูลลูกค้า
                                    </a>
                                </td>
                                
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>