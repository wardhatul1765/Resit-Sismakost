<?php
include('koneksi.php');
session_start(); // Pastikan session sudah dimulai

// Ambil ID Pemesanan dan Penyewa dengan validasi input
$idPemesanan = isset($_GET['idPemesanan']) ? $_GET['idPemesanan'] : '';
$idPenyewa = isset($_GET['idPenyewa']) ? $_GET['idPenyewa'] : '';

// Pastikan ID Pemesanan dan Penyewa valid
if (empty($idPemesanan) || empty($idPenyewa)) {
    die('ID Pemesanan atau Penyewa tidak valid.');
}

// Ambil data pemesanan dari database dengan prepared statements
$sql = "SELECT p.id_pemesanan, p.uang_muka, p.status_uang_muka, p.batas_menempati_kos, p.tenggat_uang_muka, p.status, 
               k.namaKamar, k.harga, k.idKamar, k.status AS statusKamar
        FROM pemesanan p
        JOIN kamar k ON p.idKamar = k.idKamar
        WHERE p.id_pemesanan = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, 's', $idPemesanan); // 's' untuk string
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pemesanan = mysqli_fetch_assoc($result);

if (!$pemesanan) {
    die('Pemesanan tidak ditemukan.');
}

// Cek jika pemesanan telah dibatalkan
if ($pemesanan['status'] === 'Dibatalkan') {
    echo "<script>
            alert('Pemesanan telah dibatalkan. Tidak dapat melanjutkan pembayaran.');
            window.location.href='index.php';
          </script>";
    exit;
}

// Periksa tenggat waktu pembayaran untuk DP 30%
$tanggalSekarang = time(); // Waktu saat ini
$tenggatUangMuka = strtotime($pemesanan['tenggat_uang_muka']); // Tenggat waktu uang muka

// Validasi apakah tenggat waktu uang muka tersedia
if (isset($pemesanan['tenggat_uang_muka'])) {
    // Cek jika status 'Menunggu Pembayaran' dan tenggat waktu sudah terlewati
    if ($pemesanan['status'] === 'Menunggu Pembayaran' && $tenggatUangMuka < $tanggalSekarang) {
        // Pembatalan pemesanan
        $updateStatusPemesanan = "UPDATE pemesanan SET status = 'Dibatalkan' WHERE id_pemesanan = ? AND status = 'Menunggu Pembayaran'";
        $stmtUpdate = mysqli_prepare($koneksi, $updateStatusPemesanan);
        mysqli_stmt_bind_param($stmtUpdate, 's', $idPemesanan);
        if (mysqli_stmt_execute($stmtUpdate)) {
            // Ubah status kamar menjadi 'Tersedia'
            $updateStatusKamar = "UPDATE kamar SET status = 'Tersedia' WHERE idKamar = ? AND status = 'Booking'";
            $stmtKamar = mysqli_prepare($koneksi, $updateStatusKamar);
            mysqli_stmt_bind_param($stmtKamar, 's', $pemesanan['idKamar']);
            if (mysqli_stmt_execute($stmtKamar)) {
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
    }
}

// Ambil nilai sisaBiaya dari session, jika tidak ada maka atur ke 0
$sisaBiaya = isset($_SESSION['sisaBiaya']) ? $_SESSION['sisaBiaya'] : 0;
$totalBiaya = isset($_SESSION['totalBiaya']) ? $_SESSION['totalBiaya'] : 0; // Default 0 jika session kosong



// Periksa sisa biaya (sisaPembayaran) - Gantilah menjadi sisaBiaya
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan pembayaran sudah dipilih
    if (!isset($_POST['pembayaran'])) {
        echo "<script>alert('Silakan pilih metode pembayaran terlebih dahulu.'); window.history.back();</script>";
        exit;
    }

    $pembayaran = $_POST['pembayaran'];
    $tanggalPembayaran = date('Y-m-d H:i:s');
    $batasPembayaran = $pemesanan['batas_menempati_kos'];
    $sisaBiaya = isset($_POST['sisaBiaya']) ? $_POST['sisaBiaya'] : 0; // Gantilah sisaPembayaran menjadi sisaBiaya
    $_SESSION['sisaBiaya'] = $sisaBiaya; // Simpan sisa pembayaran di session

    // Tentukan status pembayaran
    $statusPembayaran = '';
    if ($pemesanan['status_uang_muka'] === 'DP 30%') {
        if ($tanggalSekarang > strtotime($batasPembayaran)) {
            $statusPembayaran = 'Belum Lunas'; // Jika sudah melewati batas waktu
        } elseif ($sisaBiaya > 0) { // Gantilah sisaPembayaran menjadi sisaBiaya
            $statusPembayaran = 'Belum Lunas'; // Jika masih ada sisa pembayaran
        } else {
            $statusPembayaran = 'Lunas';
        }
    } elseif ($pemesanan['status_uang_muka'] === 'Bayar Penuh') {
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
        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmtInsert = mysqli_prepare($koneksi, $sqlInsertPembayaran);
        mysqli_stmt_bind_param($stmtInsert, 'sssssss', $tanggalPembayaran, $batasPembayaran, $durasiSewa, $statusPembayaran, $idPenyewa, $jatuhTempo, $idPemesanan);
        
        if (mysqli_stmt_execute($stmtInsert)) {
            // Cek dan update status pemesanan
            $statusUangMuka = ($pembayaran === 'Bayar Penuh') ? 'Bayar Penuh' : 'DP 30%';
            $sqlUpdatePemesanan = "UPDATE pemesanan SET status_uang_muka = ?, status = 'Menunggu Dikonfirmasi' WHERE id_pemesanan = ?";
            $stmtUpdatePemesanan = mysqli_prepare($koneksi, $sqlUpdatePemesanan);
            mysqli_stmt_bind_param($stmtUpdatePemesanan, 'ss', $statusUangMuka, $idPemesanan);

            if (mysqli_stmt_execute($stmtUpdatePemesanan)) {
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
        mysqli_rollback($koneksi);
        echo "Error: " . $e->getMessage();
    }
    exit;
}
?>

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
        .payment-form button { padding: 10px; font-size: 16px; background-color: #4CAF50; color: white; border: none; cursor: pointer; border-radius: 5px; }
        .payment-form button:hover { background-color: #45a049; }
    </style>
</head>
<body>

<div class="payment-container">
    <h2>Konfirmasi Pembayaran</h2>
    <div class="payment-summary">
    <p><strong>Kamar:</strong> <?php echo htmlspecialchars($pemesanan['namaKamar']); ?></p>
    <p><strong>Harga:</strong> <?php echo number_format($pemesanan['harga'], 0, ',', '.'); ?> IDR</p>
    <p><strong>Uang Muka:</strong> Rp. <?= number_format($pemesanan['uang_muka'], 0, ',', '.') ?></p>
        <?php if (isset($_SESSION['durasiSewa'])): ?>
            <p><strong>Durasi Sewa :</strong> <?= $_SESSION['durasiSewa'] ?> bulan</p>
        <?php else: ?>
            <p><strong>Durasi Sewa tidak ditemukan dalam session.</strong></p>
        <?php endif; ?>

        <?php if ($pemesanan['status_uang_muka'] === 'DP 30%'): ?>
            <!-- Jika status uang muka DP 30%, tampilkan Sisa Biaya -->
            <p><strong>Sisa Biaya:</strong> <?php echo number_format($sisaBiaya, 0, ',', '.'); ?> IDR</p>
        <?php elseif ($pemesanan['status_uang_muka'] === 'Bayar Penuh'): ?>
            <!-- Jika status uang muka Bayar Penuh, tampilkan Total Pembayaran -->
            <p><strong>Total Pembayaran:</strong> Rp. <?= number_format($totalBiaya, 0, ',', '.') ?></p>
        <?php endif; ?>
    <p><strong>Status Uang Muka:</strong> <?= $pemesanan['status_uang_muka'] ?></p>
    <p><strong>Batas Menempati Kos:</strong> <?= $pemesanan['batas_menempati_kos'] ?></p>
</div>


    <form action="pembayaran.php?idPemesanan=<?php echo $idPemesanan; ?>&idPenyewa=<?php echo $idPenyewa; ?>" method="post" class="payment-form">
        <label for="pembayaran">Pilih Metode Pembayaran</label>
        <select name="pembayaran" id="pembayaran" required>
            <option value="DP 30%">QRIS</option>
            <option value="Bayar Penuh">Transfer Bank</option>
            <option value="Bayar Penuh">Dana</option>
            <option value="Bayar Penuh">GoPay</option>
        </select>

        <!-- <label for="sisaBiaya">Sisa Biaya</label>
        <input type="number" name="sisaBiaya" id="sisaBiaya" value="<?php echo htmlspecialchars($sisaBiaya); ?>" required> -->

        <button type="submit">Proses Pembayaran</button>
    </form>
</div>

</body>
</html>
