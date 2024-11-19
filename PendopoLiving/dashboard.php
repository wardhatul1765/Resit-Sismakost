<?php
session_start();
include 'koneksi.php';

$namaAdmin = 'Pengguna'; // Default value if session idAdmin is not found

if (isset($_SESSION['idAdmin'])) {
    $idAdmin = $_SESSION['idAdmin'];

    $query = "SELECT namaAdmin FROM admin WHERE idAdmin = ?";
    $stmt = $koneksi->prepare($query);
    
    if ($stmt) {
        $stmt->bind_param("i", $idAdmin);
        if ($stmt->execute()) {
            $stmt->bind_result($namaAdmin);
            $stmt->fetch();
            $stmt->close();
        } else {
            echo "Error: Failed to execute query.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elisa Kost</title>
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        /* Styling untuk profile container */
        .profile {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 20px;
            margin: auto;
        }

        .profile .info a {
            font-weight: bold;
            color: white; /* Nama pengguna berwarna putih */
            text-decoration: none;
            font-size: 16px;
        }

        .profile .info p {
            margin: 0;
            font-size: 12px;
            color: #ccc;
        }

        .profile .dropdown-trigger {
            cursor: pointer;
            color: white; /* Warna panah dropdown putih */
            font-size: 18px;
            margin-top: 5px; /* Jarak antara panah dan teks */
        }

        /* Styling untuk dropdown menu */
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 120%;
            left: 100%;
            transform: translateX(-50%);
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            min-width: 150px;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu a {
            display: block;
            padding: 10px;
            text-decoration: none;
            color: black;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }

        .profile.active .dropdown-menu {
            display: block;
        }
    </style>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const profile = document.querySelector('.profile');
            const dropdownTrigger = profile.querySelector('.dropdown-trigger');

            dropdownTrigger.addEventListener('click', () => {
                profile.classList.toggle('active');
            });

            document.addEventListener('click', (event) => {
                if (!profile.contains(event.target)) {
                    profile.classList.remove('active');
                }
            });
        });
    </script>



</head>
<body>

    <!-- Navbar -->
    <div class="top-container">
        <div class="nav">
            <!-- Logo -->
            <div class="logo">
                <i class='bx bxl-codepen'></i>
                <a href="index.php?page=dashboard">Kost Elisa</a>
            </div>

            <!-- Links / Navigation -->
            <div class="nav-links">
                <a href="dashboard.php?page=dashboard">Dashboard</a>
                <a href="dashboard.php?page=fasilitas">Fasilitas</a>
                <a href="dashboard.php?page=kamar">Kamar</a>
                <a href="dashboard.php?page=penyewa">Penyewa</a>
                <a href="dashboard.php?page=pemesanan">Pemesanan</a>
                <a href="dashboard.php?page=pembayaran">Pembayaran</a>
            </div>

            <!-- Right Section (Notification, Search, Profile) -->
            <div class="right-section">
                <i class='bx bx-bell'></i>
                <i class='bx bx-search'></i>

                <!-- Profile Section -->
                <div class="profile">
                    <div class="info">
                        <div>
                            <a href="#"><?php echo htmlspecialchars($namaAdmin); ?></a>
                            <p>Admin</p>
                        </div>
                        <i class='bx bx-chevron-down dropdown-trigger'></i> <!-- Tambahkan kelas "dropdown-trigger" -->
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu">
                        <a href="logout.php">Logout</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mt-5">
        <?php
        // Check if the 'page' parameter is set, if not, default to 'dashboard'
        $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
        $aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

        // Switch content based on 'page' and 'aksi' parameters
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
                echo "<h1>404 - Page Not Found</h1>";
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