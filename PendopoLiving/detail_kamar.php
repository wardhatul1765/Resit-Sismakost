<?php
// Koneksi database
include('koneksi.php');

// Ambil parameter ID kamar dari URL
$idKamar = isset($_GET['idKamar']) ? $_GET['idKamar'] : '';

// Query untuk mendapatkan detail kamar berdasarkan ID
$sql = "SELECT k.idKamar, k.namaKamar, f.namaFasilitas, f.biayaTambahan, b.namaBlok, k.harga, k.status, k.foto, k.deskripsi 
        FROM kamar k
        JOIN kamar_fasilitas kf ON k.idKamar = kf.idKamar
        JOIN fasilitas f ON kf.idFasilitas = f.idFasilitas
        JOIN blok b ON k.idBlok = b.idBlok
        WHERE k.idKamar = '$idKamar'";

$result = mysqli_query($koneksi, $sql);
if (!$result) {
    die("Error Query: " . mysqli_error($koneksi));
}
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die('Kamar tidak ditemukan.');
}

// Ambil data kamar
$namaKamar = $row['namaKamar'];
$namaFasilitas = $row['namaFasilitas'];
$biayaTambahan = $row['biayaTambahan'];
$namaBlok = $row['namaBlok'];
$harga = $row['harga'];
$status = $row['status'];
$foto = $row['foto']; // Path foto yang disimpan di database
$deskripsi = $row['deskripsi'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kamar - <?= $namaKamar ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styling dasar */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
            margin-bottom: 30px;
        }

        .detail-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .room-photo {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .room-details {
            margin-bottom: 20px;
        }

        .room-details p {
            font-size: 18px;
            margin: 5px 0;
        }

        .price {
            font-size: 20px;
            color: #FF5722;
            font-weight: bold;
            margin-top: 10px;
        }

        .status {
            font-size: 16px;
            color: #888;
            margin-top: 5px;
        }

        .description {
            margin-top: 20px;
            font-size: 16px;
            color: #333;
        }

        .btn-reservasi {
            display: inline-block;
            background-color: #FF5722;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin-top: 20px;
            text-align: center;
        }

        .btn-reservasi:hover {
            background-color: #FF3D00;
        }
    </style>
</head>
<body>

<div class="detail-container">
    <h2>Detail Kamar: <?= $namaKamar ?></h2>

    <!-- Foto Kamar -->
    <?php if (!empty($foto)): ?>
        <!-- Menampilkan foto dari path yang disimpan di database -->
        <img src="uploads/<?= $foto ?>" alt="<?= $namaKamar ?>" class="room-photo" />
    <?php else: ?>
        <img src="default-image.jpg" alt="Foto tidak tersedia" class="room-photo" />
    <?php endif; ?>

    <!-- Detail Kamar -->
    <div class="room-details">
        <p><strong>Nama Kamar:</strong> <?= $namaKamar ?></p>
        <p><strong>Fasilitas:</strong> <?= $namaFasilitas ?></p>
        <p><strong>Blok:</strong> <?= $namaBlok ?></p>
        <p><strong>Status:</strong> <?= $status ?></p>
        <p><strong>Harga:</strong> Rp. <?= number_format($harga, 0, ',', '.') ?></p>
        <p><strong>Biaya Tambahan:</strong> Rp. <?= number_format($biayaTambahan, 0, ',', '.') ?></p>
    </div>

    <!-- Deskripsi Kamar -->
    <div class="description">
        <h3>Deskripsi Kamar:</h3>
        <p><?= nl2br($deskripsi) ?></p>
    </div>

    <!-- Tombol Pemesanan -->
    <a href="pemesanan.php?idKamar=<?= $idKamar ?>" class="btn-reservasi">
        <i class="fas fa-shopping-cart"></i> Pesan Kamar
    </a>
</div>

</body>
</html>
