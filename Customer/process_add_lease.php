<?php
session_start();
include '../condb.php';

if (!isset($_SESSION['Emp_ID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location.href='../login.php';</script>";
    exit();
}

$customer_id = mysqli_real_escape_string($con, $_POST['customer_id']);
$room_number = mysqli_real_escape_string($con, $_POST['room_number']);
$lease_start = mysqli_real_escape_string($con, $_POST['lease_start']);
$lease_end = mysqli_real_escape_string($con, $_POST['lease_end']);
$lease_status = mysqli_real_escape_string($con, $_POST['lease_status']);
$emp_id = mysqli_real_escape_string($con, $_POST['emp_id']);

if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] == 0) {
    $pdf_file = $_FILES['pdf_file'];
    $pdf_name = $pdf_file['name'];
    $pdf_tmp = $pdf_file['tmp_name'];
    $pdf_size = $pdf_file['size'];

    $allowed_types = ['application/pdf'];
    if (in_array($pdf_file['type'], $allowed_types)) {
        $new_pdf_name = uniqid() . '_' . $pdf_name;
        $upload_path = 'Lease_pdf/' . $new_pdf_name;

        if (!file_exists('Lease_pdf')) {
            mkdir('Lease_pdf', 0777, true);
        }

        if (move_uploaded_file($pdf_tmp, $upload_path)) {

            $sql = "INSERT INTO Tenants (Room_number, Cus_ID, Lease_start, Lease_end, Emp_ID, lease_pdf, Lease_status)
                    VALUES ('$room_number', '$customer_id', '$lease_start', '$lease_end', '$emp_id', '$upload_path', '$lease_status')";

            if (mysqli_query($con, $sql)) {
                $update_room_sql = "UPDATE Rooms SET Room_status = 'ไม่ว่าง' WHERE Room_number = '$room_number'";
                mysqli_query($con, $update_room_sql);

                echo "<script>alert('เพิ่มสัญญาเช่าสำเร็จ'); window.location.href='show_tenants.php';</script>";
            } else {
                echo "<script>alert('เกิดข้อผิดพลาดในการเพิ่มสัญญาเช่า: " . mysqli_error($con) . "'); window.location.href='add_lease.php?customer_id=$customer_id';</script>";
            }
        } else {
            echo "<script>alert('ไม่สามารถอัปโหลดไฟล์ PDF ได้'); window.location.href='add_lease.php?customer_id=$customer_id';</script>";
        }
    } else {
        echo "<script>alert('ไฟล์ที่อัปโหลดต้องเป็น PDF เท่านั้น'); window.location.href='add_lease.php?customer_id=$customer_id';</script>";
    }
} else {
    echo "<script>alert('กรุณาอัปโหลดไฟล์ PDF'); window.location.href='add_lease.php?customer_id=$customer_id';</script>";
}

mysqli_close($con);
?>