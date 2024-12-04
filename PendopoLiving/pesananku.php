<?php
session_start();
include('koneksi.php');

// Pastikan penyewa sudah login
if (!isset($_SESSION['idPenyewa'])) {
    echo "<script>alert('Harap login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit;
}

$idPenyewa = $_SESSION['idPenyewa'];

// Query untuk mendapatkan daftar pesanan penyewa
$sql = "SELECT p.id_pemesanan, p.pemesanan_kamar, p.uang_muka, p.sisa_pembayaran, p.status, p.status_uang_muka, 
                p.tenggat_uang_muka,p.mulai_menempati_kos, 
                p.batas_menempati_kos, k.namaKamar, b.namaBlok,pm.idPembayaran 
            FROM 
            pemesanan p
            JOIN 
            kamar k ON p.idKamar = k.idKamar
            JOIN 
            blok b ON k.idBlok = b.idBlok
            LEFT JOIN 
            pembayaran pm ON pm.id_pemesanan = p.id_pemesanan -- Join dengan tabel pembayaran
            WHERE 
            p.id_penyewa = '$idPenyewa'";

$result = mysqli_query($koneksi, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}

// Proses upload bukti transfer
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unggah_bukti'])) {

    $idPemesanan = $_POST['idPemesanan'];
    $idPembayaran = $_POST['idPembayaran'];
    $metodePembayaran = $_POST['metode_pembayaran'];
    
    // Proses unggah file
    if (isset($_FILES['bukti_transfer'])) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['bukti_transfer']['name']);
        $targetFilePath = $targetDir . $fileName;

        if (move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $targetFilePath)) {
            // Query untuk memperbarui status pembayaran dan menyimpan bukti transfer
            $update = "UPDATE pemesanan 
                       SET sisa_pembayaran = 0, 
                           status = 'Menunggu Dikonfirmasi', 
                           status_uang_muka = 'Bayar Penuh', 
                           bukti_transfer = '$fileName'
                       WHERE id_pemesanan = '$idPemesanan'";

            if (mysqli_query($koneksi, $update)) {
                echo "<script>alert('Pembayaran berhasil!'); window.location.href='pesananku.php';</script>";
            } else {
                echo "<script>alert('Kesalahan saat memperbarui data pembayaran.');</script>";
            }
        } else {
            echo "<script>alert('Gagal mengunggah bukti transfer.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="http://localhost/ProjectAkhirS3/Resit-Sismakost/PendopoLiving/index.php" class="btn btn-primary mb-4">
            Kembali
        </a>
    <h2 class="text-center mb-4">Pesanan Anda</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Pemesanan</th>
                <th>Kamar</th>
                <th>Blok</th>
                <th>Durasi</th>
                <th>Uang Muka</th>
                <th>Status Uang Muka</th>
                <th>Tenggat Uang Muka</th>
                <th>Total Biaya</th>
                <th>Sisa Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $idPemesanan = $row['id_pemesanan'];
                    $idPembayaran = $row['idPembayaran'];
                    $tanggalPemesanan = $row['pemesanan_kamar'];
                    $namaKamar = $row['namaKamar'];
                    $namaBlok = $row['namaBlok'];
                    $durasi = date_diff(date_create($row['mulai_menempati_kos']), date_create($row['batas_menempati_kos']))->m;
                    $uangMuka = $row['uang_muka'];
                    $statusUangMuka = $row['status_uang_muka'];  // Menampilkan status uang muka
                    $tenggatUangMuka = $row['tenggat_uang_muka'];
                    $uangMuka = $row['uang_muka'];
                    $sisaPembayaran = $row['sisa_pembayaran'];
                    $status = $row['status'];  // Menampilkan status pemesanan
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $tanggalPemesanan ?></td>
                        <td><?= $namaKamar ?></td>
                        <td><?= $namaBlok ?></td>
                        <td><?= $durasi ?> Bulan</td>
                        <td>Rp. <?= number_format($uangMuka, 0, ',', '.') ?></td>
                        <td><?= $statusUangMuka ?></td> <!-- Status Uang Muka -->
                        <td>
                            <?= $status === 'Menunggu Pembayaran' && $tenggatUangMuka ? $tenggatUangMuka : '-' ?>
                        </td>
                        <td>Rp. <?= number_format($uangMuka + $sisaPembayaran, 0, ',', '.') ?></td>
                        <td>Rp. <?= number_format($sisaPembayaran, 0, ',', '.') ?></td>
                        <td><?= $status ?></td> <!-- Status Pembayaran -->
                        <td>
                        <?php if ($status !== 'Dibatalkan'): ?>
                            <!-- Tombol untuk pembayaran awal jika status uang muka 'DP 30%' dan status pemesanan 'Menunggu Pembayaran' -->
                            <?php if ($statusUangMuka === 'DP 30%' && $status === 'Menunggu Pembayaran'): ?>
                                <a href="pembayaran.php?idPemesanan=<?= $idPemesanan; ?>&idPenyewa=<?= $idPenyewa; ?>" class="btn btn-success btn-sm">
                                    Bayar Pembayaran Awal
                                </a>
                            <?php endif; ?>

                            <!-- Jika status uang muka 'DP 30%' dan status pemesanan 'Menunggu Dikonfirmasi', tampilkan tombol 'Bayar Sisa Pembayaran' -->
                            <?php if ($statusUangMuka === 'DP 30%' && $status === 'Menunggu Dikonfirmasi'): ?>
                                <a href="javascript:void(0)" onclick="showPaymentModal(<?= $idPemesanan ?>)" class="btn btn-warning btn-sm">
                                    Bayar Sisa Pembayaran
                                </a>
                            <?php endif; ?>

                            <!-- Tombol Bayar untuk status pemesanan 'Menunggu Pembayaran' atau 'Bayar Penuh' -->
                            <?php if ($status === 'Menunggu Pembayaran' && $statusUangMuka !== 'DP 30%') : ?>
                                <a href="pembayaran.php?idPemesanan=<?= $idPemesanan; ?>&idPenyewa=<?= $idPenyewa; ?>" class="btn btn-success btn-sm">
                                    Bayar
                                </a>
                            <?php elseif ($statusUangMuka === 'Bayar Penuh'): ?>
                                <a href="pembayaran.php?idPemesanan=<?= $idPemesanan; ?>&idPenyewa=<?= $idPenyewa; ?>" class="btn btn-success btn-sm">
                                    Bayar
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Jika status "Dibatalkan", tidak ada aksi -->
                            <span class="badge bg-danger">Dibatalkan</span>
                        <?php endif; ?>
                    </td>
                    </tr>
                    <?php
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>Tidak ada pesanan.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Pembayaran -->
<div id="paymentModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; background: white; padding: 20px; border: 1px solid #ccc;">
    <h3>Pilih Metode Pembayaran</h3>
    <form id="metodePembayaranForm" onsubmit="showPaymentInstructions(event)">
        <label for="pembayaran">Metode Pembayaran:</label>
        <select name="pembayaran" id="pembayaran" required>
            <option value="QRIS">QRIS</option>
            <option value="Bank Transfer">Bank Transfer</option>
        </select>
        <button type="submit" name="pilih_metode">Pilih Metode</button>
    </form>
    <div id="paymentInstructions"></div>
    <button onclick="closePaymentModal()">Tutup</button>
</div>

<!-- Overlay Modal -->
<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 999;"></div>

<script>
let currentIdPemesanan = null;

function showPaymentModal(idPemesanan) {
    currentIdPemesanan = idPemesanan;
    document.getElementById('paymentModal').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

function closePaymentModal() {
    document.getElementById('paymentModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

function showPaymentInstructions(event) {
    event.preventDefault(); // Mencegah form reload halaman
    const metode = document.getElementById('pembayaran').value;
    const instructionsDiv = document.getElementById('paymentInstructions');
    
    let instructions = '';
    if (metode === 'QRIS') {
        instructions = `
            <h3>Silakan scan QRIS di bawah ini untuk melakukan pembayaran</h3>
            <img src='qris_code.png' alt='QRIS Code' style='width:200px;'>
        `;
    } else if (metode === 'Bank Transfer') {
        instructions = `
            <h3>Silakan transfer ke rekening berikut:</h3>
            <p>Bank ABC<br>Nomor Rekening: 1234567890<br>Atas Nama: Kos XYZ</p>
        `;
    }

    instructions += `
        <form action="pesananku.php" method="post" enctype="multipart/form-data">
            <label for="bukti_transfer">Unggah Bukti Transfer:</label>
            <input type="file" name="bukti_transfer" id="bukti_transfer" required>
            <input type="hidden" name="metode_pembayaran" value="${metode}">
            <input type="hidden" name="idPemesanan" value="${currentIdPemesanan}">
            <button type="submit" name="unggah_bukti">Unggah Bukti</button>
        </form>
    `;
    
    instructionsDiv.innerHTML = instructions;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
