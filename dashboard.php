<?php
session_start();
include 'condb.php';

// ตรวจสอบการเข้าสู่ระบบ
if (!isset($_SESSION['Emp_ID'])) {
    header("Location: login.php"); // เปลี่ยนหน้าไปที่ login.php
    exit();
}

// ดึงข้อมูลสถิติ
$sqlTenants = "SELECT COUNT(*) AS tenant_count FROM Tenants";
$resultTenants = mysqli_query($con, $sqlTenants);
$tenantCount = mysqli_fetch_assoc($resultTenants)['tenant_count'];

$sqlCustomers = "SELECT COUNT(*) AS customer_count FROM Customer";
$resultCustomers = mysqli_query($con, $sqlCustomers);
$customerCount = mysqli_fetch_assoc($resultCustomers)['customer_count'];

$sqlVacantRooms = "SELECT COUNT(*) AS vacant_rooms FROM Rooms WHERE Room_status = 'ว่าง'";
$resultVacantRooms = mysqli_query($con, $sqlVacantRooms);
$vacantRoomsCount = mysqli_fetch_assoc($resultVacantRooms)['vacant_rooms'];

$sqlOccupiedRooms = "SELECT COUNT(*) AS occupied_rooms FROM Rooms WHERE Room_status = 'ไม่ว่าง'";
$resultOccupiedRooms = mysqli_query($con, $sqlOccupiedRooms);
$occupiedRoomsCount = mysqli_fetch_assoc($resultOccupiedRooms)['occupied_rooms'];

$sqlLatestTenants = "SELECT * FROM Tenants ORDER BY Lease_ID DESC LIMIT 5";
$resultLatestTenants = mysqli_query($con, $sqlLatestTenants);

$currentEmpID = $_SESSION['Emp_ID'];
$sqlCurrentEmp = "SELECT Emp_name, Emp_lastname FROM Employee WHERE Emp_ID = $currentEmpID";
$resultCurrentEmp = $con->query($sqlCurrentEmp);
$currentEmp = $resultCurrentEmp->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #343a40 !important;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .dashboard-box {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .dashboard-box h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .dashboard-box p {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php
include 'menu.php';
?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body dashboard-box">
                        <h3><i class="fas fa-users"></i> จำนวนผู้เช่า</h3>
                        <p class="lead"><?php echo $tenantCount; ?> คน</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body dashboard-box">
                        <h3><i class="fas fa-user-friends"></i> จำนวนลูกค้า</h3>
                        <p class="lead"><?php echo $customerCount; ?> คน</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body dashboard-box">
                        <h3><i class="fas fa-door-open"></i> ห้องพักที่ว่าง</h3>
                        <p class="lead"><?php echo $vacantRoomsCount; ?> ห้อง</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body dashboard-box">
                        <h3><i class="fas fa-door-closed"></i> ห้องพักที่ถูกจอง</h3>
                        <p class="lead"><?php echo $occupiedRoomsCount; ?> ห้อง</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4><i class="fas fa-list"></i> รายการผู้เช่าล่าสุด</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>รหัสสัญญา</th>
                                    <th>ชื่อผู้เช่า</th>
                                    <th>วันที่เริ่มสัญญา</th>
                                    <th>วันที่สิ้นสุดสัญญา</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($resultLatestTenants)): ?>
                                <tr>
                                    <td><?php echo $row['Lease_ID']; ?></td>
                                    <td><?php echo $row['Cus_ID']; ?></td>
                                    <td><?php echo $row['Lease_start']; ?></td>
                                    <td><?php echo $row['Lease_end']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<?php
$con->close(); 
?>