<?php
session_start();
include '../condb.php';

if (!isset($_SESSION['Emp_ID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_GET['room_number'])) {
    $roomNumber = $_GET['room_number'];
} else {
    echo "<script>alert('ไม่พบหมายเลขห้อง'); window.location.href='manage_rooms.php';</script>";
    exit();
}

$sqlRoom = "SELECT * FROM Rooms WHERE Room_number = '$roomNumber'";
$resultRoom = mysqli_query($con, $sqlRoom);
$roomData = mysqli_fetch_assoc($resultRoom);

if (!$roomData) {
    echo "<script>alert('ไม่พบข้อมูลห้องพัก'); window.location.href='manage_rooms.php';</script>";
    exit();
}

$sqlExtras = "SELECT * FROM Rooms_Extra WHERE Room_number = '$roomNumber'";
$resultExtras = mysqli_query($con, $sqlExtras);
$extras = [];
while ($row = mysqli_fetch_assoc($resultExtras)) {
    $extras[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomPrice = $_POST['room_price'];
    $extraNames = $_POST['extra_name'] ?? [];
    $extraPrices = $_POST['extra_price'] ?? [];
    mysqli_begin_transaction($con);

    try {
        $sqlUpdateRoom = "UPDATE Rooms SET Room_price = '$roomPrice' WHERE Room_number = '$roomNumber'";
        if (!mysqli_query($con, $sqlUpdateRoom)) {
            throw new Exception("เกิดข้อผิดพลาดในการอัปเดตห้องพัก: " . mysqli_error($con));
        }

        $sqlDeleteExtras = "DELETE FROM Rooms_Extra WHERE Room_number = '$roomNumber'";
        if (!mysqli_query($con, $sqlDeleteExtras)) {
            throw new Exception("เกิดข้อผิดพลาดในการลบสิ่งของเพิ่มเติม: " . mysqli_error($con));
        }
        for ($i = 0; $i < count($extraNames); $i++) {
            $extraName = $extraNames[$i];
            $extraPrice = $extraPrices[$i];

            if (!empty($extraName) && !empty($extraPrice)) {
                $sqlInsertExtra = "INSERT INTO Rooms_Extra (Room_number, Extra_name, Extra_price) VALUES ('$roomNumber', '$extraName', '$extraPrice')";
                if (!mysqli_query($con, $sqlInsertExtra)) {
                    throw new Exception("เกิดข้อผิดพลาดในการเพิ่มสิ่งของเพิ่มเติม: " . mysqli_error($con));
                }
            }
        }
        mysqli_commit($con);
        echo "<script>alert('แก้ไขห้องพักสำเร็จ'); window.location.href='manage_rooms.php';</script>";
    } catch (Exception $e) {
        mysqli_rollback($con);
        echo "<script>alert('" . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขห้องพัก</title>
    <script>
        function addExtraField() {
            const container = document.getElementById('extra-container');
            const newField = document.createElement('div');
            newField.classList.add('extra-field', 'mb-3');
            newField.innerHTML = `
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control" name="extra_name[]" placeholder="ชื่อสิ่งของเพิ่มเติม" required>
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="extra_price[]" placeholder="ราคา" step="0.01" required>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-danger" onclick="removeExtraField(this)">ลบ</button>
                    </div>
                </div>
            `;
            container.appendChild(newField);
        }

        function removeExtraField(button) {
            const field = button.closest('.extra-field');
            field.remove();
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2>แก้ไขห้องพัก</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="room_number" class="form-label">หมายเลขห้อง</label>
                <input type="text" class="form-control" id="room_number" name="room_number" value="<?php echo $roomData['Room_number']; ?>" disabled readonly>
            </div>
            <div class="mb-3">
                <label for="room_price" class="form-label">ราคาห้อง</label>
                <input type="number" class="form-control" id="room_price" name="room_price" value="<?php echo $roomData['Room_price']; ?>" step="0.01" required>
            </div>
            <div class="mb-3">
                <label class="form-label">สิ่งของเพิ่มเติม</label>
                <div id="extra-container">
                    <?php foreach ($extras as $extra): ?>
                        <div class="extra-field mb-3">
                            <div class="row">
                                <div class="col">
                                    <input type="text" class="form-control" name="extra_name[]" value="<?php echo $extra['Extra_name']; ?>" placeholder="ชื่อสิ่งของเพิ่มเติม" required>
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" name="extra_price[]" value="<?php echo $extra['Extra_price']; ?>" placeholder="ราคา" step="0.01" required>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-danger" onclick="removeExtraField(this)">ลบ</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="btn btn-secondary" onclick="addExtraField()">เพิ่มสิ่งของ</button>
            </div>
            <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
        </form>
    </div>
</body>
</html>

<?php
mysqli_close($con); // ปิดการเชื่อมต่อฐานข้อมูล
?>