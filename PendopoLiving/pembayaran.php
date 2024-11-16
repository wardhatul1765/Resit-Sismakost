<?php
// Koneksi database
include('koneksi.php');

// Ambil parameter dari URL
$idPemesanan = isset($_GET['idPemesanan']) ? $_GET['idPemesanan'] : '';
$idPenyewa = isset($_GET['idPenyewa']) ? $_GET['idPenyewa'] : '';

// Ambil detail pemesanan
$sql = "SELECT p.id_pemesanan, p.uang_muka, p.status_uang_muka, p.tenggat_uang_muka, k.namaKamar, k.harga
        FROM pemesanan p
        JOIN kamar k ON p.idKamar = k.idKamar
        WHERE p.id_pemesanan = '$idPemesanan'";
$result = mysqli_query($koneksi, $sql);
$pemesanan = mysqli_fetch_assoc($result);

if (!$pemesanan) {
    die('Pemesanan tidak ditemukan.');
}

// Periksa tenggat waktu
$tenggatWaktu = strtotime($pemesanan['tenggat_uang_muka']);
$now = time();
if ($now > $tenggatWaktu && $pemesanan['status_uang_muka'] === 'Menunggu Pembayaran') {
    // Batalkan pemesanan jika belum membayar
    $sqlUpdate = "UPDATE pemesanan SET status = 'Dibatalkan' WHERE id_pemesanan = '$idPemesanan'";
    mysqli_query($koneksi, $sqlUpdate);
    die('Pemesanan dibatalkan karena tidak membayar tepat waktu.');
}

// Proses pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $paymentOption = $_POST['paymentOption'];
    $amountPaid = ($paymentOption === 'dp') ? $pemesanan['uang_muka'] : $pemesanan['harga'];

    // Proses upload bukti transfer
    $buktiTransfer = '';
    if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] == 0) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Buat folder jika belum ada
        }
        $targetFile = $targetDir . basename($_FILES["bukti_transfer"]["name"]);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Cek apakah file yang diupload adalah gambar
        if (in_array($fileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            if (move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $targetFile)) {
                $buktiTransfer = $targetFile;
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        } else {
            echo "Hanya file gambar yang diperbolehkan!";
            exit;
        }
    }

    // Update status pembayaran dan bukti transfer
    $statusPembayaran = ($paymentOption === 'dp') ? 'Menunggu Konfirmasi' : 'Lunas';
    $sqlPembayaran = "UPDATE pemesanan 
                      SET status_uang_muka = '$statusPembayaran', bukti_transfer = '$buktiTransfer'
                      WHERE id_pemesanan = '$idPemesanan'";

    if (mysqli_query($koneksi, $sqlPembayaran)) {
        // Jika pembayaran berhasil, arahkan ke halaman konfirmasi pembayaran
        echo "<script>alert('Pembayaran berhasil! Tunggu konfirmasi kami.'); window.location.href='status_pembayaran.php?idPemesanan=$idPemesanan';</script>";
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styling untuk form pembayaran */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .payment-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #4CAF50;
        }

        .payment-summary {
            margin: 20px 0;
        }

        .payment-summary p {
            font-size: 18px;
            margin: 5px 0;
        }

        .payment-form input[type="file"], .payment-form select, .payment-form button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
        }

        .payment-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .payment-form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="payment-container">
    <h2>Konfirmasi Pembayaran Kamar</h2>

    <div class="payment-summary">
        <p><strong>Nama Kamar:</strong> <?= $pemesanan['namaKamar'] ?></p>
        <p><strong>Harga Kamar:</strong> Rp. <?= number_format($pemesanan['harga'], 0, ',', '.') ?></p>
        <p><strong>Uang Muka:</strong> Rp. <?= number_format($pemesanan['uang_muka'], 0, ',', '.') ?></p>
        <p><strong>Status Uang Muka:</strong> <?= $pemesanan['status_uang_muka'] ?></p>
        <p><strong>Tenggat Pembayaran:</strong> <?= $pemesanan['tenggat_uang_muka'] ?></p>
    </div>

    <form class="payment-form" method="POST" enctype="multipart/form-data">
        <label for="paymentOption">Pilih Metode Pembayaran:</label>
        <select id="paymentOption" name="paymentOption">
            <option value="dp" <?= ($pemesanan['status_uang_muka'] === 'Menunggu Pembayaran' ? 'selected' : '') ?>>Uang Muka (30%)</option>
            <option value="full">Bayar Penuh</option>
        </select>

        <label for="bukti_transfer">Upload Bukti Transfer:</label>
        <input type="file" id="bukti_transfer" name="bukti_transfer" required>

        <button type="submit">Konfirmasi Pembayaran</button>
    </form>
</div>
</body>
</html>
