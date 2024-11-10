<?php
session_start();
include 'koneksi.php';

$namaAdmin = 'Pengguna'; // Default value jika session idAdmin tidak ditemukan

if (isset($_SESSION['idAdmin'])) {
    $idAdmin = $_SESSION['idAdmin'];

    $query = "SELECT namaAdmin FROM admin WHERE idAdmin = ?";
    $stmt = $koneksi->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $idAdmin);
        $stmt->execute();
        $stmt->bind_result($namaAdmin);
        $stmt->fetch();
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elisa Kost</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar-custom {
            background-color: #2E236C;
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white;
            transition: transform 0.3s ease, font-style 0.3s ease;
        }
        .navbar-custom .nav-link:hover {
            color: #ffccff;
            font-style: italic;
            transform: scale(1.1);
        }
        .navbar-nav .nav-item {
            margin-right: 30px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <a class="navbar-brand" href="index.php?page=dashboard">Elisa Kost</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link<?php echo (isset($_GET['page']) && $_GET['page'] === 'dashboard') ? ' active' : ''; ?>" href="dashboard.php?page=dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php?page=fasilitas">Fasilitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php?page=kamar">Kamar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php?page=penyewa">Penyewa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php?page=pemesanan">Pemesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php?page=pembayaran">Pembayaran</a>
                </li>
                <!-- Tampilkan Halo, [namaAdmin] dan Logout -->
                <li class="nav-item d-flex align-items-center">
                    <span class="nav-link text-white">Halo, <?php echo htmlspecialchars($namaAdmin); ?></span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary nav-link" href="login.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-5">
        <?php
        // Check if the 'page' parameter is set, if not, default to 'dashboard'
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
        $aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

        // Load the appropriate content based on the page parameter and aksi
        switch ($page) {
            case "dashboard":
                include "page/dashboard/adminhome.php";
                break;
            case "fasilitas":
                if ($aksi == "") {
                    include "page/fasilitas/fasilitas.php";
                } elseif ($aksi == "tambah") {
                    include "page/fasilitas/tambah.php";
                } elseif ($aksi == "edit") {
                    include "page/fasilitas/edit.php";
                } elseif ($aksi == "hapus") {
                    include "page/fasilitas/hapus.php";
                }
                break;
            case "kamar":
                if ($aksi == "") {
                    include "page/kamar/kamar.php";
                } elseif ($aksi == "tambah") {
                    include "page/kamar/tambahkamar.php";
                } elseif ($aksi == "edit") {
                    include "page/kamar/edit.php";
                } elseif ($aksi == "hapus") {
                    include "page/kamar/hapus.php";
                }
                break;
            case "penyewa":
                if ($aksi == "") {
                    include "page/penyewa/datapenyewa.php";
                } elseif ($aksi == "tambah") {
                    include "page/penyewa/tambahpenyewa.php";
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
                break;
            default:
                echo "<h1>404 - Page Not Found</h1><p>Halaman yang Anda cari tidak ditemukan.</p>";
                break;
        }
        ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
