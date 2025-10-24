<?php
session_start();
include '../condb.php';

if (!isset($_SESSION['Emp_ID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location.href='login.php';</script>";
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomNumber = $_POST['room_number'];
    $roomPrice = $_POST['room_price'];
    $extraNames = $_POST['extra_name'] ?? [];
    $extraPrices = $_POST['extra_price'] ?? [];
    mysqli_begin_transaction($con);

    try {
        $sqlInsertRoom = "INSERT INTO Rooms (Room_number, Room_price, Room_status) VALUES ('$roomNumber', '$roomPrice', 'ว่าง')";
        if (!mysqli_query($con, $sqlInsertRoom)) {
            throw new Exception("เกิดข้อผิดพลาดในการเพิ่มห้องพัก: " . mysqli_error($con));
        }

        if (!empty($extraNames) && !empty($extraPrices)) {
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
        }
        mysqli_commit($con);
        echo "<script>alert('เพิ่มห้องพักสำเร็จ'); window.location.href='manage_rooms.php';</script>";
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
    <title>เพิ่มห้องพัก</title>
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
        <h2>เพิ่มห้องพัก</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="room_number" class="form-label">หมายเลขห้อง</label>
                <input type="text" class="form-control" id="room_number" name="room_number" required>
            </div>
            <div class="mb-3">
                <label for="room_price" class="form-label">ราคาห้อง</label>
                <input type="number" class="form-control" id="room_price" name="room_price" step="0.01" required>
            </div>
            <div class="mb-3">
                <label class="form-label">สิ่งของเพิ่มเติม</label>
                <div id="extra-container">
                </div>
                <button type="button" class="btn btn-secondary" onclick="addExtraField()">เพิ่มสิ่งของ</button>
            </div>
            <button type="submit" class="btn btn-primary">เพิ่มห้องพัก</button>
        </form>
    </div>

</body>
</html>

<?php
mysqli_close($con); 
?>