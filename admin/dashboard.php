<?php
include('conn.php');
session_start();
if (!isset($_SESSION['username'])) {
    header('Location:../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="../css/sweetalert2.min.css" />
    <link rel="stylesheet" href="../css/all.min.css" />
    <script src="../js/jquery-3.7.1.js"></script>
    <script src="../js/all.min.js"></script>
    <script src="../js/sweetalert2.all.min.js"></script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <h4 style="font-family: Cambria;">
                    <i class="fa-solid fa-circle-user"></i>Hai, <?= $_SESSION['username']; ?>
                </h4>
            </a>
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <form action="logout.php">
                    <button class="btn btn-danger" type="submit">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-12">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php?p=dekorasi">Dekorasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php?p=customer">Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php?p=transaksi">Transaksi</a>
                    </li>
                </ul>
            </div>
            <div class="col-lg-10 col-md-8 col-sm-12">
                <?php
                if (isset($_GET['p'])) {
                    if ($_GET['p'] == 'dekorasi') {
                        include 'dekorasi.php';
                    } else if ($_GET['p'] == 'customer') {
                        include 'customer.php';
                    } else if ($_GET['p'] == 'transaksi') {
                        include 'transaksi.php';
                    }
                } else {
                    include 'home.php';
                }
                ?>
            </div>
        </div>
    </div>
    <!-- <nav class="ts-sidebar">
        <ul class="ts-sidebar-menu">

            <li class="ts-label">Main</li>
            <li><a href=""><i class="fa fa-dashboard"></i> Dashboard</a></li>

            <li><a href="#"><i class="fa fa-files-o"></i> Brands</a>
                <ul>
                    <li><a href="">Create Brand</a></li>
                    <li><a href="">Manage Brands</a></li>
                </ul>
            </li>

            <li><a href="#"><i class="fa fa-sitemap"></i> Vehicles</a>
                <ul>
                    <li><a href="">Post a Vehicle</a></li>
                    <li><a href="">Manage Vehicles</a></li>
                </ul>
            </li>
            <li><a href=""><i class="fa fa-users"></i> Manage Booking</a></li>

            <li><a href=""><i class="fa fa-table"></i> Manage Testimonials</a></li>
            <li><a href=""><i class="fa fa-desktop"></i> Manage Conatctus Query</a></li>
            <li><a href=""><i class="fa fa-users"></i> Reg Users</a></li>
            <li><a href=""><i class="fa fa-files-o"></i> Manage Pages</a></li>
            <li><a href=""><i class="fa fa-files-o"></i> Update Contact Info</a></li>

            <li><a href=""><i class="fa fa-table"></i> Manage Subscribers</a></li>

        </ul>
    </nav> -->
</body>

</html>