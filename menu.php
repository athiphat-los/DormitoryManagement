
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">ABC DormitoryManagement</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Tenant/show_tenants.php">จัดการสัญญาเช่า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Customer/show_customers.php">จัดการลูกค้า</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Monthly_Payment/utility_billing.php">จัดการบิลรายเดือน</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Room/manage_rooms.php">จัดการห้อง</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i> <?php echo $currentEmp['Emp_name'] . ' ' . $currentEmp['Emp_lastname']; ?>
                        </a> 
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
