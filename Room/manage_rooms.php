<?php
session_start();
include '../condb.php';

if (!isset($_SESSION['Emp_ID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location.href='login.php';</script>";
    exit();
}

$sqlRooms = "SELECT * FROM Rooms";
$resultRooms = mysqli_query($con, $sqlRooms);

if (!$resultRooms) {
    die("ข้อผิดพลาดในการดึงข้อมูล: " . mysqli_error($con));
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการห้องพัก</title>
</head>
<body>
<?php
include 'menu_room.php';
?>
    <div class="container mt-5">
        <h2>จัดการห้องพัก</h2>
        <a href="add_room.php" class="btn btn-primary mb-3">เพิ่มห้องพัก</a>
        <table class="table">
            <thead>
                <tr>
                    <th>หมายเลขห้อง</th>
                    <th>ราคาห้อง</th>
                    <th>สถานะ</th>
                    <th>เพิ่มเติม</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($resultRooms)): ?>
                <tr>
                    <td><?php echo $row['Room_number']; ?></td>
                    <td><?php echo number_format($row['Room_price'], 2); ?> บาท</td>
                    <td><?php echo $row['Room_status']; ?></td>
                    <td>
                        <?php
                        $roomNumber = $row['Room_number'];
                        $sqlExtras = "SELECT Extra_name FROM Rooms_Extra WHERE Room_number = '$roomNumber'";
                        $resultExtras = mysqli_query($con, $sqlExtras);

                        if ($resultExtras && mysqli_num_rows($resultExtras) > 0) {
                            while ($extra = mysqli_fetch_assoc($resultExtras)) {
                                echo $extra['Extra_name'] . "<br>";
                            }
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>
                    
                    <td>
                        <a href="edit_room.php?room_number=<?php echo $row['Room_number']; ?>" class="btn btn-warning">แก้ไข</a>
                        <a href="delete_room.php?room_number=<?php echo $row['Room_number']; ?>" class="btn btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบห้องนี้?');">ลบ</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>

<?php
mysqli_close($con); 
?>