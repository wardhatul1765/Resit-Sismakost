<?php
include('koneksi.php');
session_start(); // Pastikan session sudah dimulai

// Ambil ID Pemesanan dan Penyewa
$idPemesanan = isset($_GET['idPemesanan']) ? $_GET['idPemesanan'] : '';
$idPenyewa = isset($_GET['idPenyewa']) ? $_GET['idPenyewa'] : '';

// Ambil data pembayaran dari database
$sql = "SELECT p.id_pembayaran, p.tanggalPembayaran, p.batasPembayaran, p.durasiSewa, p.StatusPembayaran, p.jatuh_tempo, p.id_pemesanan, pm.namaKamar
        FROM pembayaran p
        JOIN pemesanan pm ON p.id_pemesanan = pm.id_pemesanan
        WHERE p.id_pemesanan = '$idPemesanan' AND p.idPenyewa = '$idPenyewa'";

$result = mysqli_query($koneksi, $sql);
$pembayaran = mysqli_fetch_assoc($result);

if (!$pembayaran) {
    die('Pembayaran tidak ditemukan.');
}

// Tampilkan informasi pembayaran
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f9; }
        .payment-container { max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        h2 { text-align: center; color: #4CAF50; }
        .status-summary { margin: 20px 0; }
        .status-summary p { margin: 5px 0; }
        .status { font-weight: bold; color: green; }
        .status-cancelled { color: red; }
    </style>
</head>
<body>
<div class="payment-container">
    <h2>Status Pembayaran</h2>

    <div class="status-summary">
        <p><strong>Nama Kamar:</strong> <?= $pembayaran['namaKamar'] ?></p>
        <p><strong>Tanggal Pembayaran:</strong> <?= date('d-m-Y H:i:s', strtotime($pembayaran['tanggalPembayaran'])) ?></p>
        <p><strong>Batas Pembayaran:</strong> <?= date('d-m-Y', strtotime($pembayaran['batasPembayaran'])) ?></p>
        <p><strong>Durasi Sewa:</strong> <?= $pembayaran['durasiSewa'] ?> bulan</p>
        <p><strong>Status Pembayaran:</strong> 
            <?php 
            if ($pembayaran['StatusPembayaran'] == 'Lunas') {
                echo "<span class='status'>Lunas</span>";
            } elseif ($pembayaran['StatusPembayaran'] == 'Belum Lunas') {
                echo "<span class='status-cancelled'>Belum Lunas</span>";
            } else {
                echo "<span class='status-cancelled'>Status Pembayaran Tidak Ditemukan</span>";
            }
            ?>
        </p>
        <p><strong>Jatuh Tempo:</strong> <?= date('d-m-Y', strtotime($pembayaran['jatuh_tempo'])) ?></p>
    </div>

    <a href="index.php">Kembali ke Halaman Utama</a>
</div>
</body>
</html>
