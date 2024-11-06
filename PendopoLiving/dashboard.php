<?php
// Initialize connection
$koneksi = new mysqli("localhost", "root", "", "pendopo_living");

// Check for connection errors
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

// Uncomment these lines if session management is needed
// session_start();
// if (!isset($_SESSION['admin']) && !isset($_SESSION['user'])) {
//     header("Location: login.php"); // Redirect to login if not authenticated
// }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Elisa Kost</title>
    <!-- Bootstrap Styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- Font Awesome Styles -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles -->
<<<<<<< HEAD
     <link rel="stylesheet" href="style.css">
=======
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- Data Tables Styles -->
    <link href="assets/js/dataTables/dataTables.bootstrap.css" rel="stylesheet" />
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
<<<<<<< HEAD
                    <span class="icon-bar">jul</span>
=======
                    <span class="icon-bar"></span>
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Elisa Kost</a>
            </div>
            <div style="color: white; padding: 15px 50px; float: right; font-size: 16px;">
                Last access: 30 May 2014 &nbsp; <a href="index.php" class="btn btn-primary square-btn-adjust">Logout</a>
            </div>
        </nav>

        <!-- Side Navigation -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center">
                        <img src="assets/img/kos.jpeg
                        " class="user-image img-responsive" />
                    </li>
                    <li>
                        <a href="index.php"><i class="fa fa-dashboard fa-2x"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="?page=fasilitas"><i class="fa fa-user fa-2x"></i> Fasilitas</a>
                    </li>
                    <li>
                        <a href="?page=kamar"><i class="fa fa-book fa-2x"></i> Kamar</a>
                    </li>
                    <li>
                        <a href="?page=penyewa"><i class="fa fa-book fa-2x"></i> Penyewa</a>
                    </li>
                    <li>
<<<<<<< HEAD
                        <a href="?page=pemesanan"><i class="fa fa-book fa-2x"></i> Pemesanan</a>
                    </li>
                    <li>
                        <a href="?page=pembayaran"><i class="fa fa-book fa-2x"></i> Pembayaran</a>
=======
                        <a href="?page=penyewa"><i class="fa fa-book fa-2x"></i> Pemesanan</a>
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <?php 
                        $page = isset($_GET['page']) ? $_GET['page'] : '';
                        $aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

                        // Include corresponding page content based on the 'page' and 'aksi' parameters
                        switch ($page) {
                            case "fasilitas":
                                if ($aksi == "") {
                                    include "page/fasilitas/fasilitas.php";
                                } elseif ($aksi == "tambah") {
                                    include "page/fasilitas/tambah.php";
<<<<<<< HEAD
                                } elseif ($aksi == "edit") {
=======
                                } elseif ($aksi == "ubah") {
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
                                    include "page/fasilitas/edit.php";
                                } elseif ($aksi == "hapus") {
                                    include "page/fasilitas/hapus.php";
                                }
                                break;
                            case "kamar":
                                if ($aksi == "") {
                                    include "page/kamar/kamar.php";
                                } elseif ($aksi == "tambah") {
                                    include "page/kamar/tambah.php";
<<<<<<< HEAD
                                } elseif ($aksi == "edeit") {
                                    include "page/kamar/edit.php";
=======
                                } elseif ($aksi == "ubah") {
                                    include "page/kamar/ubah.php";
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
                                } elseif ($aksi == "hapus") {
                                    include "page/kamar/hapus.php";
                                }
                                break;
                            case "penyewa":
                                if ($aksi == "") {
                                    include "page/penyewa/datapenyewa.php";
                                } elseif ($aksi == "tambah") {
                                    include "page/penyewa/tambahpenyewa.php";
<<<<<<< HEAD
                                } elseif ($aksi == "edit") {
                                    include "page/penyewa/edit.php";
                                } elseif ($aksi == "hapus") {
                                    include "page/penyewa/hapus.php";
                                }
                                break;
                            case "pemesanan":
                                if ($aksi == "") {
                                    include "page/pemesanan/pemesanan.php";
                                } elseif ($aksi == "tambah") {
                                    include "page/pemesanan/tambah.php";
                                } elseif ($aksi == "edit") {
                                    include "page/pemesanan/edit.php";
                                } elseif ($aksi == "hapus") {
                                    include "page/pemesanan/hapus.php";
                                }
                                break;
                            case "pembayaran":
                                if ($aksi == "") {
                                    include "page/pembayaran/pembayaran.php";
                                } elseif ($aksi == "tambah") {
                                    include "page/pembayaran/tambah.php";
                                } elseif ($aksi == "edit") {
                                    include "page/pembayaran/edit.php";
                                } elseif ($aksi == "hapus") {
                                    include "page/pembayaran/hapus.php";
                                }
=======
                                } elseif ($aksi == "ubah") {
                                    include "page/penyewa/ubah.php";
                                } elseif ($aksi == "hapus") {
                                    include "page/penyewa/hapus.php";
                                }
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
                        }
                        ?>
                    </div>
                </div>
                <hr />
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="assets/js/dataTables/dataTables.bootstrap.js"></script>
    <script>
        $(document).ready(function () {
            $('#dataTables-example').dataTable();
        });
    </script>
    <script src="assets/js/custom.js"></script>
</body>
</html>
