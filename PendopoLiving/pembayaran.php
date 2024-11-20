<?php
include('koneksi.php');
session_start(); // Pastikan session sudah dimulai

// Ambil ID Pemesanan dan Penyewa
$idPemesanan = isset($_GET['idPemesanan']) ? $_GET['idPemesanan'] : '';
$idPenyewa = isset($_GET['idPenyewa']) ? $_GET['idPenyewa'] : '';

// Ambil data pemesanan dari database
$sql = "SELECT p.id_pemesanan, p.uang_muka, p.status_uang_muka, p.batas_menempati_kos, p.tenggat_uang_muka, p.status, k.namaKamar, k.harga, k.idKamar, k.status AS statusKamar
        FROM pemesanan p
        JOIN kamar k ON p.idKamar = k.idKamar
        WHERE p.id_pemesanan = '$idPemesanan'";
$result = mysqli_query($koneksi, $sql);
$pemesanan = mysqli_fetch_assoc($result);

if (!$pemesanan) {
    die('Pemesanan tidak ditemukan.');
}

// Periksa tenggat waktu untuk pembatalan pemesanan
$tanggalSekarang = time(); // Dapatkan timestamp saat ini

// Validasi apakah tenggat waktu uang muka tersedia
if (isset($pemesanan['tenggat_uang_muka'])) {
    $tenggatUangMuka = strtotime($pemesanan['tenggat_uang_muka']); // Ubah ke timestamp

    // Cek jika status 'Menunggu Pembayaran' dan tenggat waktu sudah terlewati
    if ($pemesanan['status'] === 'Menunggu Pembayaran' && $tenggatUangMuka < $tanggalSekarang) {
        // Pembatalan pemesanan
        $updateStatusPemesanan = "UPDATE pemesanan SET status = 'Dibatalkan' WHERE id_pemesanan = '$idPemesanan' AND status = 'Menunggu Pembayaran'";
        if (mysqli_query($koneksi, $updateStatusPemesanan)) {
            // Ubah status kamar menjadi 'Tersedia'
            $updateStatusKamar = "UPDATE kamar SET status = 'Tersedia' WHERE idKamar = '{$pemesanan['idKamar']}' AND status = 'Booking'";
            if (mysqli_query($koneksi, $updateStatusKamar)) {
                echo "<script>
                    alert('Tenggat waktu pembayaran terlewati. Pemesanan dibatalkan dan status kamar telah diubah menjadi Tersedia.');
                    window.location.href='index.php';
                </script>";
                exit;
            } else {
                echo "Error mengubah status kamar: " . mysqli_error($koneksi);
                exit;
            }
        } else {
            echo "Error membatalkan pemesanan: " . mysqli_error($koneksi);
            exit;
        }
    } else {
        echo "Pemesanan masih dalam batas waktu pembayaran atau status tidak sesuai.<br>";
    }
} else {
    echo "Data tenggat waktu uang muka tidak ditemukan.<br>";
}

// Proses pembayaran (sudah DP atau bayar penuh)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan pembayaran sudah dipilih
    if (!isset($_POST['pembayaran'])) {
        echo "<script>alert('Silakan pilih metode pembayaran terlebih dahulu.'); window.history.back();</script>";
        exit;
    }

    $pembayaran = $_POST['pembayaran'];
    $tanggalPembayaran = date('Y-m-d H:i:s');
    $batasPembayaran = $pemesanan['batas_menempati_kos'];

    // Tentukan status pembayaran
    $statusPembayaran = 'Belum Lunas';
    if ($tanggalPembayaran <= $batasPembayaran) {
        $statusPembayaran = 'Lunas';
    }

    // Tentukan durasi sewa
    $durasiSewa = $_SESSION['durasiSewa'] ?? 1;

    // Menghitung jatuh tempo (misalnya 3 hari setelah batas_menempati_kos)
    $jatuhTempo = date('Y-m-d', strtotime($batasPembayaran . ' + 3 days'));

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);
    try {
        // Masukkan data pembayaran ke dalam tabel pembayaran
        $sqlInsertPembayaran = "INSERT INTO pembayaran (tanggalPembayaran, batasPembayaran, durasiSewa, StatusPembayaran, idPenyewa, jatuh_tempo, id_pemesanan) 
        VALUES ('$tanggalPembayaran', '$batasPembayaran', '$durasiSewa', '$statusPembayaran', '$idPenyewa', '$jatuhTempo', '$idPemesanan')";

        if (mysqli_query($koneksi, $sqlInsertPembayaran)) {
            // Cek dan update status pemesanan
            $statusUangMuka = ($pembayaran === 'Bayar Penuh') ? 'Bayar Penuh' : 'DP 30%';
            $sqlUpdatePemesanan = "UPDATE pemesanan SET status_uang_muka = '$statusUangMuka', status = 'Menunggu Dikonfirmasi' WHERE id_pemesanan = '$idPemesanan'";

            if (mysqli_query($koneksi, $sqlUpdatePemesanan)) {
                // Commit transaksi
                mysqli_commit($koneksi);
                echo "<script>alert('Pembayaran berhasil diproses. Status pemesanan kini Menunggu Konfirmasi.'); window.location.href='status_pembayaran.php?idPemesanan=$idPemesanan&idPenyewa=$idPenyewa';</script>";
            } else {
                // Jika gagal update pemesanan, rollback transaksi
                mysqli_rollback($koneksi);
                echo "Error: " . mysqli_error($koneksi);
            }
        } else {
            // Jika gagal insert pembayaran, rollback transaksi
            mysqli_rollback($koneksi);
            echo "Error: " . mysqli_error($koneksi);
        }
    } catch (Exception $e) {
        // Jika ada error, rollback transaksi
        mysqli_rollBack($koneksi);
        echo "Error: " . $e->getMessage();
    }
    exit;
}
?>
>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f9; }
        .payment-container { max-width: 600px; margin: 0 auto; background-color: white; padding: 20px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        h2 { text-align: center; color: #4CAF50; }
        .payment-summary { margin: 20px 0; }
        .payment-form { display: flex; flex-direction: column; gap: 10px; }
        .payment-form button { padding: 10px; font-size: 16px; background-color: #4CAF50; color: white; border: none; cursor: pointer; }
        .cancelled { color: red; font-weight: bold; }
    </style>
</head>
<body>
<div class="payment-container">
    <h2>Konfirmasi Pembayaran Kamar</h2>

    <div class="payment-summary">
        <p><strong>Nama Kamar:</strong> <?= $pemesanan['namaKamar'] ?></p>
        <p><strong>Harga Kamar:</strong> Rp. <?= number_format($pemesanan['harga'], 0, ',', '.') ?></p>
        <p><strong>Uang Muka:</strong> Rp. <?= number_format($pemesanan['uang_muka'], 0, ',', '.') ?></p>
        <?php if (isset($_SESSION['durasiSewa'])): ?>
        <p><strong>Durasi Sewa :</strong> <?= $_SESSION['durasiSewa'] ?> bulan</p>
            <?php else: ?>
                <p><strong>Durasi Sewa tidak ditemukan dalam session.</strong></p>
            <?php endif; ?>
        <p><strong>Status Uang Muka:</strong> <?= $pemesanan['status_uang_muka'] ?></p>
        <p><strong>Batas Menempati Kos:</strong> <?= $pemesanan['batas_menempati_kos'] ?></p>
    </div>

    <?php if ($pemesanan['status'] === 'Dibatalkan') { ?>
        <p class="cancelled">Pemesanan dibatalkan karena tenggat waktu pembayaran telah terlewat.</p>
    <?php } elseif ($pemesanan['status'] === 'Menunggu Pembayaran') { ?>
        <form class="payment-form" method="POST">
            <input type="hidden" name="id_pemesanan" value="<?= $idPemesanan ?>">
            <input type="hidden" name="id_penyewa" value="<?= $idPenyewa ?>">

            <p><strong>Pilih Metode Pembayaran:</strong></p>
            <label>
                <input type="radio" name="pembayaran" value="QRIS"> QRIS
            </label><br>
            <label>
                <input type="radio" name="pembayaran" value="Transfer Bank"> Transfer Bank
            </label><br>
            <button type="submit">Lanjutkan Pembayaran</button>
        </form>
    <?php } elseif ($pemesanan['status'] === 'Menunggu Konfirmasi') { ?>
        <p>Status: Menunggu Konfirmasi Pembayaran.</p>
    <?php } elseif ($pemesanan['status'] === 'Lunas') { ?>
        <p>Status: Pembayaran Lunas.</p>
    <?php } ?>
</div>
</body>
</html>
