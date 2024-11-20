<?php
session_start();
include 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['namaPenyewa'])) {
    header("Location: login.php"); // Jika belum login, alihkan ke halaman login
    exit();
}

// Ambil idPenyewa dari session
$idPenyewa = $_SESSION['idPenyewa']; // Pastikan idPenyewa ada di session

// Query untuk mengambil data pemesanan dan informasi kamar berdasarkan idPenyewa
$query_pemesanan = "
    SELECT 
        pemesanan.*, 
        kamar.namaKamar, 
        kamar.nomorKamar 
    FROM 
        pemesanan 
    JOIN 
        kamar ON pemesanan.idKamar = kamar.idKamar  -- Sesuaikan dengan kolom yang benar
    WHERE 
        pemesanan.id_penyewa = '$idPenyewa'";

$result_pemesanan = mysqli_query($koneksi, $query_pemesanan);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        h1 {
            text-align: center;
            margin-top: 20px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .action-button {
            padding: 8px 16px;
            background-color: #ff5733;
            color: white;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .action-button:hover {
            background-color: #ff4520;
        }

        .no-data {
            text-align: center;
            font-size: 16px;
            color: #555;
        }
    </style>
</head>

<body>
    <h1>Pesanan Saya</h1>

    <table>
        <thead>
            <tr>
                <th>Tanggal Pemesanan</th>
                <th>Nama Kamar</th>
                <th>Nomor Kamar</th>
                <th>Uang Muka</th>
                <th>Status Pembayaran</th>
                <th>Tenggat Uang Muka</th>
                <th>Status Pemesanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Cek apakah ada data pemesanan yang ditemukan
            if (mysqli_num_rows($result_pemesanan) > 0) {
                // Menampilkan setiap baris data pemesanan
                while ($row = mysqli_fetch_assoc($result_pemesanan)) {
                    echo "<tr>";
                    echo "<td>" . $row['pemesanan_kamar'] . "</td>";
                    echo "<td>" . $row['namaKamar'] . "</td>";
                    echo "<td>" . $row['nomorKamar'] . "</td>";
                    echo "<td>" . number_format($row['uang_muka'], 2) . "</td>"; // Format uang muka dengan 2 decimal
                    echo "<td>" . $row['status_uang_muka'] . "</td>";
                    echo "<td>" . $row['tenggat_uang_muka'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    // Tampilkan link hanya jika status pemesanan memungkinkan untuk melihat detail
                    echo "<td>
                            <a href='pembayaran.php?idPemesanan={$row['id_pemesanan']}&idPenyewa={$row['id_penyewa']}'>Pembayaran</a> 
                          </td>";
                    echo "</tr>";
                }
            } else {
                // Menampilkan pesan jika tidak ada pemesanan
                echo "<tr><td colspan='8' class='no-data'>Tidak ada pemesanan ditemukan</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>

</html>
