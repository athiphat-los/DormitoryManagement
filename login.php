<?php
session_start();
include 'condb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $sql = "SELECT Emp_ID, Password FROM Employee_Account WHERE Username = '$username'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if ($password === $row['Password']) { 
            $_SESSION['Emp_ID'] = $row['Emp_ID'];
            $_SESSION['Username'] = $username;

            echo "<script>window.location.href='dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง!');</script>";
        }
    } else {
        echo "<script>alert('ชื่อผู้ใช้ไม่ถูกต้อง!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4">เข้าสู่ระบบ</h2>
    <form action="login.php" method="POST">
        <div class="mb-3">
            <label class="form-label">ชื่อผู้ใช้</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">รหัสผ่าน</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>