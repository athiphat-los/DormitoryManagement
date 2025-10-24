<?php
include '../condb.php';

if (isset($_GET['Lease_ID']) && isset($_GET['Room_number'])) {
    $lease_id = $_GET['Lease_ID'];
    $room_number = $_GET['Room_number'];

    $update_room_sql = "UPDATE Rooms SET Room_status = 'ว่าง' WHERE Room_number = ?";
    $stmt = $con->prepare($update_room_sql);
    $stmt->bind_param("s", $room_number);
    $stmt->execute();

    $update_lease_sql = "UPDATE Tenants SET Lease_status = 'สิ้นสุดแล้ว' WHERE Lease_ID = ?";
    $stmt = $con->prepare($update_lease_sql);
    $stmt->bind_param("i", $lease_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('เปลี่ยนสถานะห้องเป็นว่างและอัปเดตสถานะสัญญาเรียบร้อยแล้ว'); window.location.href='show_tenants.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการเปลี่ยนสถานะ'); window.location.href='show_tenants.php';</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('ข้อมูลไม่ครบถ้วน'); window.location.href='show_tenants.php';</script>";
}

$con->close();
?>