<?php
include '../condb.php';
session_start();

if (!isset($_SESSION['Emp_ID'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อน'); window.location.href='../login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Lease_ID = $_POST['Lease_ID'];
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $tel = $_POST['tel'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $lease_start = $_POST['lease_start'];
    $lease_end = $_POST['lease_end'];
    $pdf_path = "";
    $target_dir = "../Lease_pdf/";
    
    $sql = "SELECT lease_pdf, Cus_ID FROM Tenants WHERE Lease_ID = '$Lease_ID'";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $old_pdf = $row["lease_pdf"];
        $cus_id = $row["Cus_ID"];
    } else {
        echo "<script>alert('ไม่พบข้อมูลผู้เช่า'); window.history.back();</script>";
        exit();
    }

    if (isset($_FILES["pdf_file"]) && $_FILES["pdf_file"]["error"] == UPLOAD_ERR_OK) {
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["pdf_file"]["name"]);
        $target_file = $target_dir . $file_name;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($file_type != "pdf") {
            echo "<script>alert('อัปโหลดได้เฉพาะไฟล์ PDF เท่านั้น!'); window.history.back();</script>";
            exit();
        }

        if (move_uploaded_file($_FILES["pdf_file"]["tmp_name"], $target_file)) {
            $pdf_path = $target_file;

            if (!empty($old_pdf) && file_exists($old_pdf)) {
                unlink($old_pdf);
            }
        } else {
            echo "<script>alert('อัปโหลดไฟล์ล้มเหลว!'); window.history.back();</script>";
            exit();
        }
    } else {
        $pdf_path = $old_pdf;
    }

    $sqlCus = "UPDATE Customer SET Cus_name = '$name', Cus_lastname = '$lastname', Cus_tel = '$tel', 
               Cus_email = '$email', Cus_address = '$address' WHERE Cus_ID = '$cus_id'";

    if (!$con->query($sqlCus)) {
        echo "<script>alert('อัปเดตข้อมูลลูกค้าล้มเหลว'); window.history.back();</script>";
        exit();
    }

    $sqlTenants = "UPDATE Tenants SET Lease_start = '$lease_start', Lease_end = '$lease_end', 
                   lease_pdf = '$pdf_path' WHERE Lease_ID = '$Lease_ID'";

    if ($con->query($sqlTenants)) {
        echo "<script>alert('แก้ไขข้อมูลผู้เช่าสำเร็จ!'); window.location.href='show_tenants.php';</script>";
    } else {
        echo "<script>alert('อัปเดตข้อมูลผู้เช่าล้มเหลว'); window.history.back();</script>";
    }

    $con->close();
}
?>
