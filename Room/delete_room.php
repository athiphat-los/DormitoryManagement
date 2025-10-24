<?php
session_start();
include '../condb.php';

if (!isset($_SESSION['Emp_ID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location.href='login.php';</script>";
    exit();
}

if (isset($_GET['room_number'])) {
    $roomNumber = mysqli_real_escape_string($con, $_GET['room_number']);

    $deleteExtrasQuery = "DELETE FROM Rooms_Extra WHERE Room_number = '$roomNumber'";
    mysqli_query($con, $deleteExtrasQuery);

    $deleteRoomQuery = "DELETE FROM Rooms WHERE Room_number = '$roomNumber'";
    $result = mysqli_query($con, $deleteRoomQuery);

    if ($result) {
        echo "<script>alert('ลบห้องเรียบร้อยแล้ว'); window.location.href='manage_rooms.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการลบห้อง: " . mysqli_error($con) . "'); window.location.href='manage_rooms.php';</script>";
    }
} else {
    echo "<script>alert('ไม่พบหมายเลขห้องที่ต้องการลบ'); window.location.href='manage_rooms.php';</script>";
}

mysqli_close($con);
?>