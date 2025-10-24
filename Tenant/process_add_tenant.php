<?php
include '../condb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $room_number = $_POST['room_number'];
    $lease_start = $_POST['lease_start'];
    $lease_end = $_POST['lease_end'];
    $lease_status = $_POST['lease_status'];
    $emp_id = $_POST['emp_id'];
    $pdf_path = "";
    $target_dir = "../Lease_pdf/";

    if (isset($_FILES["pdf_file"]) && $_FILES["pdf_file"]["error"] == UPLOAD_ERR_OK) {
        $target_dir = "../Lease_pdf/"; 

    // ตรวจสอบและสร้างโฟลเดอร์หากยังไม่มี
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

$file_name = time() . "_" . basename($_FILES["pdf_file"]["name"]);
$target_file = $target_dir . $file_name;
$file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

if ($file_type != "pdf") {
    die("อัปโหลดเฉพาะไฟล์ PDF เท่านั้น");
}

// อัปไฟล์
if (move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $target_file)) {
    $pdf_path = "../Lease_pdf/" . $file_name; 
} else {
    die("อัปโหลดไฟล์ล้มเหลว");
}
    }


    $sqlCus = "INSERT INTO Customer (Cus_name, Cus_lastname, Cus_tel, Cus_email, Cus_address) 
                VALUES ('$name', '$lastname', '$tel', '$email', '$address')";

    if ($con->query($sqlCus) === TRUE) {
        $cus_id = $con->insert_id;

        $sqlTenants = "INSERT INTO Tenants (Room_number, Lease_start, Lease_end, Emp_ID, lease_pdf, Cus_ID) 
                        VALUES ('$room_number', '$lease_start', '$lease_end', '$emp_id', '$target_file', '$cus_id')";

        if ($con->query($sqlTenants) === TRUE) {
            $updateRoom = "UPDATE Rooms SET Room_status = 'ไม่ว่าง' WHERE Room_number = '$room_number'";

            if ($con->query($updateRoom) === TRUE) {
                echo "<script>alert('เพิ่มผู้เช่าสำเร็จ!'); window.location.href='show_tenants.php';</script>";
            } else {
                echo "<script>alert('อัปเดตสถานะห้องล้มเหลว'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('เพิ่ม Tenants ล้มเหลว'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('เพิ่ม Customer ล้มเหลว'); window.history.back();</script>";
        exit;
    }

    $con->close();
}
?>
