<?php
include('koneksi.php');
session_start(); // Pastikan session sudah dimulai

// Ambil ID Pemesanan dan Penyewa dengan validasi input
$idPemesanan = isset($_GET['idPemesanan']) ? $_GET['idPemesanan'] : '';
$idPenyewa = isset($_GET['idPenyewa']) ? $_GET['idPenyewa'] : '';
$durasiBaru = isset($_POST['durasi_baru']) ? $_POST['durasi_baru'] : 0;

// Pastikan ID Pemesanan dan Penyewa valid
if (empty($idPemesanan) || empty($idPenyewa)) {
    die('ID Pemesanan atau Penyewa tidak valid.');
}

// Ambil data pemesanan dari database dengan prepared statements
$sql = "SELECT p.id_pemesanan, p.uang_muka, p.status_uang_muka, p.mulai_menempati_kos, p.batas_menempati_kos, p.tenggat_uang_muka, p.status, 
               k.namaKamar, k.harga, k.idKamar, k.status AS statusKamar, p.sisa_pembayaran
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

$durasiSewa = isset($_SESSION['durasiSewa']) ? $_SESSION['durasiSewa'] : 1;

// Cek jika pemesanan telah dibatalkan
if ($pemesanan['status'] === 'Dibatalkan') {
    echo "<script>
            alert('Pemesanan telah dibatalkan. Tidak dapat melanjutkan pembayaran.');
            window.location.href='index.php';
          </script>";
    exit;
}

// Periksa tenggat waktu pembayaran untuk DP 30%
date_default_timezone_set('Asia/Jakarta');  // Atur zona waktu sesuai lokasi Anda
$tanggalSekarang = time();  // Debugging: periksa tanggal sekarang
// echo "Tanggal Sekarang: " . date('Y-m-d H:i:s', $tanggalSekarang) . "<br>"; 

$tenggatUangMuka = strtotime($pemesanan['tenggat_uang_muka']); // Tenggat waktu uang muka
$tanggalMulaiKos = strtotime($pemesanan['mulai_menempati_kos']);
$tanggalBatasKos = strtotime($pemesanan['batas_menempati_kos']);

if (in_array($pemesanan['status'], ['Menunggu Dikonfirmasi', 'Dikonfirmasi'])) {
    // Izinkan pembayaran hanya jika sudah mencapai tanggal `batas_menempati_kos`
    if ($tanggalSekarang < $tanggalBatasKos) {
        echo "<script>
                alert('Tidak dapat melakukan pembayaran. Pemesanan masih dalam status {$pemesanan['status']} dan belum mencapai batas waktu menempati kos.');
                window.location.href='index.php';
              </script>";
        exit;
    } else {
        // Tampilkan modal untuk perpanjangan kos
        echo "<script>
                $(document).ready(function() {
                    $('#modalPerpanjanganKos').modal('show');
                });
              </script>";
    }
}

// Periksa apakah sudah melewati batas waktu menempati kos
if ($tanggalSekarang > $tanggalBatasKos) {
    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    try {
        // Update status pembayaran menjadi 'Belum Lunas'
        $sqlUpdateStatusPembayaran = "UPDATE pembayaran SET StatusPembayaran = 'Belum Lunas' 
                                      WHERE id_pemesanan = ?";
        $stmtPembayaran = mysqli_prepare($koneksi, $sqlUpdateStatusPembayaran);
        mysqli_stmt_bind_param($stmtPembayaran, 's', $idPemesanan);
        mysqli_stmt_execute($stmtPembayaran);

        // Update status pemesanan menjadi 'Menunggu Pembayaran'
        $sqlUpdateStatusPemesanan = "UPDATE pemesanan SET status = 'Menunggu Pembayaran' WHERE id_pemesanan = ?";
        $stmtPemesanan = mysqli_prepare($koneksi, $sqlUpdateStatusPemesanan);
        mysqli_stmt_bind_param($stmtPemesanan, 's', $idPemesanan);
        mysqli_stmt_execute($stmtPemesanan);

        // Perbarui tenggat_uang_muka ke periode baru (misalnya, 3 hari setelah tanggal sekarang)
        $tenggatBaru = strtotime("+3 days", $tanggalSekarang);
        $tenggatBaruFormatted = date('Y-m-d', $tenggatBaru);  

        // // Debugging: Pastikan tanggal yang dihasilkan benar
        // echo "Tenggat Baru: " . $tenggatBaruFormatted . "<br>";

        // Update tenggat_uang_muka di tabel pemesanan
        $updateTenggat = "UPDATE pemesanan SET tenggat_uang_muka = ? WHERE id_pemesanan = ?";
        $stmtTenggat = mysqli_prepare($koneksi, $updateTenggat);
        mysqli_stmt_bind_param($stmtTenggat, 'ss', $tenggatBaruFormatted, $idPemesanan);
        mysqli_stmt_execute($stmtTenggat);

        // Commit perubahan jika semua berhasil
        mysqli_commit($koneksi);
        echo "<script>alert('Status pembayaran, pemesanan, dan tenggat uang muka telah diperbarui karena melewati batas waktu.');</script>";
    } catch (Exception $e) {
        // Rollback jika terjadi kesalahan
        mysqli_rollback($koneksi);
        echo "<script>alert('Terjadi kesalahan saat memperbarui status: " . $e->getMessage() . "');</script>";
    }
}

// Validasi apakah tenggat waktu uang muka tersedia dan sudah terlewati
if (isset($pemesanan['tenggat_uang_muka'])) {
    // Cek jika status 'Menunggu Pembayaran' dan tenggat waktu baru sudah terlewati
    if ($pemesanan['status'] === 'Menunggu Pembayaran' && strtotime($pemesanan['tenggat_uang_muka']) < $tanggalSekarang) {
        // Pembatalan pemesanan
        $updateStatusPemesanan = "UPDATE pemesanan SET status = 'Dibatalkan' WHERE id_pemesanan = ? AND status = 'Menunggu Pembayaran'";
        $stmtUpdate = mysqli_prepare($koneksi, $updateStatusPemesanan);
        mysqli_stmt_bind_param($stmtUpdate, 's', $idPemesanan);
        
        // Cek apakah pembatalan berhasil
        if (mysqli_stmt_execute($stmtUpdate)) {
            // Ubah status kamar menjadi 'Tersedia'
            $updateStatusKamar = "UPDATE kamar SET status = 'Tersedia' WHERE idKamar = ? AND status = 'Booking'";
            $stmtKamar = mysqli_prepare($koneksi, $updateStatusKamar);
            mysqli_stmt_bind_param($stmtKamar, 's', $pemesanan['idKamar']);
            
            // Cek apakah pengubahan status kamar berhasil
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



// Ambil sisa pembayaran langsung dari database, bukan dari session
$sisaBiaya = $pemesanan['sisa_pembayaran']; // Ganti dengan data dari database

// Periksa sisa biaya (sisaPembayaran)
// Jika metode pembayaran dipilih
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pilih_metode'])) {
    $metodePembayaran = $_POST['pembayaran'];
    $_SESSION['metodePembayaran'] = $metodePembayaran;

    // Tampilkan instruksi pembayaran
    if ($metodePembayaran === 'QRIS') {
        echo "<h3>Silakan scan QRIS di bawah ini untuk melakukan pembayaran</h3>";
        echo "<img src='qris_code.png' alt='QRIS Code' style='width:200px;'>";
    } elseif ($metodePembayaran === 'Bank Transfer') {
        echo "<h3>Silakan transfer ke rekening berikut:</h3><p>Bank ABC<br>Nomor Rekening: 1234567890<br>Atas Nama: Kos XYZ</p>";
    }

    echo "<form action='' method='post' enctype='multipart/form-data'>
            <label for='bukti_transfer'>Unggah Bukti Transfer:</label>
            <input type='file' name='bukti_transfer' id='bukti_transfer' required>
            <button type='submit' name='unggah_bukti'>Unggah Bukti</button>
          </form>";
    exit;
}

    $pembayaran = isset($_POST['pembayaran']) ? $_POST['pembayaran'] : '';
    $tanggalPembayaran = date('Y-m-d H:i:s');
    $batasPembayaran = $pemesanan['batas_menempati_kos'];
    $sisaBiaya = isset($_POST['sisaBiaya']) ? $_POST['sisaBiaya'] : $sisaBiaya;
    $batasMenempatiKos = $pemesanan['batas_menempati_kos'];


    // Tentukan durasi sewa
    $durasiSewa = $_SESSION['durasiSewa'] ?? 1;
    // Menghitung jatuh tempo (misalnya 3 hari setelah batas_menempati_kos)
    $jatuhTempo = null;
    if (strtotime($tanggalPembayaran) > strtotime($batasPembayaran)) {
        $selisihHari = (strtotime($tanggalPembayaran) - strtotime($batasPembayaran)) / (60 * 60 * 24);
        $jatuhTempo = (int)$selisihHari; // Konversi ke integer
    }

    if ($pemesanan['status_uang_muka'] === 'Bayar Penuh') {
        $statusPembayaran = 'Lunas';  // Pembayaran penuh langsung lunas
    } else {
        // Cek jika status uang muka adalah DP 30% dan masih ada sisa pembayaran
        if ($pemesanan['status_uang_muka'] === 'DP 30%' && $pemesanan['sisa_pembayaran'] > 0) {
            $statusPembayaran = 'Belum Lunas';  // Jika masih ada sisa pembayaran, statusnya belum lunas
        } else {
            $statusPembayaran = 'Lunas';  // Jika tidak ada sisa pembayaran atau status lainnya, maka lunas
        }
    }
    
    if (strtotime($tanggalPembayaran) > strtotime($batasPembayaran)) {
        $tampilkanDurasiSewa = true;
    } else {
        $tampilkanDurasiSewa = false;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unggah_bukti'])) {
        $targetDir = "uploads/";  // Direktori tempat menyimpan file
        $fileName = basename($_FILES['bukti_transfer']['name']);  // Ambil nama file
        $targetFilePath = $targetDir . $fileName;  // Tentukan path lengkap file
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));  // Mendapatkan ekstensi file
    
        // Validasi ekstensi file
        $validExtensions = array("jpg", "jpeg", "png", "gif", "pdf");
        if (!in_array($fileType, $validExtensions)) {
            echo "<script>alert('Format file tidak valid. Hanya file JPG, PNG, JPEG, GIF, dan PDF yang diperbolehkan.');</script>";
            exit;
        }
        
        // Cek apakah file berhasil diunggah
        if (move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $targetFilePath)) {
            // Mulai transaksi
            mysqli_begin_transaction($koneksi);  // Pastikan transaksi dimulai
    
            try {
                // Jika sudah melewati batas_menempati_kos, lakukan perpanjangan
                if (strtotime($tanggalPembayaran) > strtotime($batasMenempatiKos)) {
                    // Hitung selisih hari jika sudah melewati batas_menempati_kos
                    $selisihHari = (strtotime($tanggalPembayaran) - strtotime($batasMenempatiKos)) / (60 * 60 * 24);
                    $jatuhTempo = (int)$selisihHari;  // Konversi ke integer
    
                    // Ambil durasiSewa dan uang_muka lama
                    $sqlGetOldDurasiSewa = "SELECT pm.uang_muka, pb.durasiSewa 
                                            FROM pemesanan pm
                                            JOIN pembayaran pb ON pm.id_pemesanan = pb.id_pemesanan
                                            WHERE pm.id_pemesanan = ?";
                    $stmtGetOld = mysqli_prepare($koneksi, $sqlGetOldDurasiSewa);
                    mysqli_stmt_bind_param($stmtGetOld, 's', $idPemesanan);
                    mysqli_stmt_execute($stmtGetOld);
                    mysqli_stmt_bind_result($stmtGetOld, $oldUangMuka, $oldDurasiSewa);
                    mysqli_stmt_fetch($stmtGetOld);
                    mysqli_stmt_close($stmtGetOld);
    
                    // Ambil durasiSewa baru dari inputan pengguna
                    $durasiBaru = $_POST['durasi_baru'];  // Durasi sewa baru yang dimasukkan oleh pengguna
    
                    // Uang muka per bulan dihitung dari uang muka lama dibagi durasi lama
                    $uangMukaPerBulan = $oldUangMuka / $oldDurasiSewa;
    
                    // Hitung uang muka baru berdasarkan durasi baru
                    $uangMukaBaru = $uangMukaPerBulan * $durasiBaru;
    
                    // Jika durasi sewa baru lebih pendek, hitung pengurangan uang muka
                    if ($durasiBaru < $oldDurasiSewa) {
                        // Pengurangan uang muka dihitung berdasarkan selisih durasi sewa
                        $selisihDurasi = $oldDurasiSewa - $durasiBaru;
                        $uangMukaBaru = $uangMukaPerBulan * $durasiBaru;  // Uang muka yang sudah dibayar dikurangi sesuai dengan durasi baru
                    }
    
                    // Update pemesanan untuk perpanjangan (update tanggal mulai dan batas sewa)
                    $sqlUpdatePemesananPerpanjangan = "UPDATE pemesanan SET mulai_menempati_kos = ?, batas_menempati_kos = ?, uang_muka = ? WHERE id_pemesanan = ?";
                    $stmtUpdatePerpanjangan = mysqli_prepare($koneksi, $sqlUpdatePemesananPerpanjangan);
                    $tanggalPerpanjangan = date('Y-m-d', strtotime($batasMenempatiKos . ' +1 day')); // Update mulai_menempati_kos
                    $batasPerpanjangan = date('Y-m-d', strtotime($batasMenempatiKos . ' +30 day')); // Tambah durasi sewa 30 hari
                    mysqli_stmt_bind_param($stmtUpdatePerpanjangan, 'ssss', $tanggalPerpanjangan, $batasPerpanjangan, $uangMukaBaru, $idPemesanan);
    
                    if (mysqli_stmt_execute($stmtUpdatePerpanjangan)) {
                        // Update data pembayaran untuk perpanjangan (update tanggalPembayaran dan batasPembayaran)
                        $sqlUpdatePembayaran = "UPDATE pembayaran SET tanggalPembayaran = ?, batasPembayaran = ?, durasiSewa = ?, jatuh_tempo = ?, StatusPembayaran = ? WHERE id_pemesanan = ?";
                        $batasPembayaran = $batasPerpanjangan;  // Update batas pembayaran sesuai perpanjangan
                        mysqli_stmt_bind_param($stmtInsert, 'ssisss', $tanggalPembayaran, $batasPembayaran, $durasiBaru, $jatuhTempo, $statusPembayaran, $idPemesanan);
    
                        if (mysqli_stmt_execute($stmtInsert)) {
                            // Update status pemesanan menjadi 'Menunggu Dikonfirmasi'
                            $sqlUpdatePemesananStatus = "UPDATE pemesanan SET status = 'Menunggu Dikonfirmasi' WHERE id_pemesanan = ?";
                            $stmtUpdatePemesananStatus = mysqli_prepare($koneksi, $sqlUpdatePemesananStatus);
                            mysqli_stmt_bind_param($stmtUpdatePemesananStatus, 's', $idPemesanan);
                            if (mysqli_stmt_execute($stmtUpdatePemesananStatus)) {
                                // Commit transaksi jika semuanya sukses
                                mysqli_commit($koneksi);
                                echo "<script>alert('Perpanjangan pembayaran berhasil diproses. Status pemesanan kini Menunggu Konfirmasi.'); window.location.href='status_pembayaran.php';</script>";
                            } else {
                                throw new Exception("Gagal memperbarui status pemesanan. Silakan coba lagi.");
                            }
                        } else {
                            throw new Exception("Gagal memperbarui pembayaran perpanjangan. Pastikan informasi pembayaran telah benar.");
                        }
                    } else {
                        throw new Exception("Gagal memperbarui pemesanan untuk perpanjangan.");
                    } 
                } else {
                    // Proses untuk pembayaran awal (jika belum melewati batas_menempati_kos)
                    $jatuhTempo = null;  // Jatuh tempo diatur null jika belum melewati batas
                    // Update data pemesanan dengan bukti transfer
                    $sqlUpdatePemesanan = "UPDATE pemesanan SET bukti_transfer = ? WHERE id_pemesanan = ?";
                    $stmtUpdatePemesanan = mysqli_prepare($koneksi, $sqlUpdatePemesanan);
                    mysqli_stmt_bind_param($stmtUpdatePemesanan, 'ss', $fileName, $idPemesanan);
                    if (mysqli_stmt_execute($stmtUpdatePemesanan)) {
                        // Insert data pembayaran untuk pembayaran awal
                        $sqlInsertPembayaran = "INSERT INTO pembayaran (tanggalPembayaran, batasPembayaran, durasiSewa, StatusPembayaran, idPenyewa, jatuh_tempo, id_pemesanan) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $durasiSewa = '30';  // Durasi sewa (misal 30 hari)
                        $batasPembayaran = date('Y-m-d', strtotime($tanggalPembayaran . ' +30 day')); // Tentukan batas pembayaran untuk pembayaran awal
                        mysqli_stmt_bind_param($stmtInsert, 'sssssss', $tanggalPembayaran, $batasPembayaran, $durasiSewa, $statusPembayaran, $idPenyewa, $jatuhTempo, $idPemesanan);
    
                        if (mysqli_stmt_execute($stmtInsert)) {
                            // Update status pemesanan menjadi 'Menunggu Dikonfirmasi'
                            $sqlUpdatePemesananStatus = "UPDATE pemesanan SET status = 'Menunggu Dikonfirmasi' WHERE id_pemesanan = ?";
                            $stmtUpdatePemesananStatus = mysqli_prepare($koneksi, $sqlUpdatePemesananStatus);
                            mysqli_stmt_bind_param($stmtUpdatePemesananStatus, 's', $idPemesanan);
                            if (mysqli_stmt_execute($stmtUpdatePemesananStatus)) {
                                // Commit transaksi jika semuanya sukses
                                mysqli_commit($koneksi);
                                echo "<script>alert('Pembayaran berhasil diproses. Status pemesanan kini Menunggu Konfirmasi.'); window.location.href='status_pembayaran.php';</script>";
                            } else {
                                throw new Exception("Gagal memperbarui status pemesanan. Silakan coba lagi.");
                            }
                        } else {
                            throw new Exception("Gagal menyimpan pembayaran. Pastikan informasi pembayaran telah benar.");
                        }
                    } else {
                        throw new Exception("Gagal memperbarui pemesanan. Silakan coba lagi.");
                    }
                }
            } catch (Exception $e) {
                // Jika ada error, rollback transaksi
                mysqli_rollback($koneksi);
                echo "<script>alert('Terjadi kesalahan: " . $e->getMessage() . "');</script>";
            }
        }
    }    
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 10px;
            background-color: #f4f4f9;
            margin: 0;
            box-sizing: border-box;
        }
        .payment-container {
            max-width: 100%;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 100%;
            box-sizing: border-box;
        }
        h2 {
            text-align: center;
            color: #4CAF50;
            font-size: 1.8rem;
        }
        .payment-summary {
            margin: 20px 0;
        }
        .payment-summary p {
            font-size: 1rem;
            margin: 8px 0;
        }
        .payment-form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .payment-form button {
            padding: 12px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .payment-form button:hover {
            background-color: #45a049;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            box-sizing: border-box;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover, .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Styling for responsiveness */
        @media (max-width: 768px) {
            .payment-container {
                padding: 15px;
            }
            h2 {
                font-size: 1.5rem;
            }
            .payment-summary p {
                font-size: 0.9rem;
            }
            .payment-form button {
                font-size: 14px;
                padding: 10px;
            }
        }

        /* Modal Content Adaptation */
        #paymentInstructions h4 {
            font-size: 1.2rem;
        }
        #fileUploadSection {
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="payment-summary">
        <p><strong>Kamar:</strong> <?php echo htmlspecialchars($pemesanan['namaKamar']); ?></p>
        <p><strong>Harga:</strong> <?php echo number_format($pemesanan['harga'], 0, ',', '.'); ?> IDR</p>
        <p><strong>Uang Muka:</strong> Rp. <?= number_format($pemesanan['uang_muka'], 0, ',', '.') ?></p>
        <p><strong>Durasi Sewa:</strong> <?= $durasiSewa ?> bulan</p>
        <!-- Cek apakah status pembayaran sudah ada dan tentukan apakah lunas atau belum -->
        <?php if (!isset($pemesanan['statusPembayaran']) || $pemesanan['statusPembayaran'] == 'Belum Lunas'): ?>
            <p><strong>Status Pembayaran:</strong> Belum Lunas. Silakan lakukan pembayaran.</p>
        <?php elseif ($pemesanan['statusPembayaran'] == 'Lunas'): ?>
            <p><strong>Status Pembayaran:</strong> Pembayaran telah lunas. Anda dapat mulai menempati kos.</p>
        <?php endif; ?>
        <!-- Jika ada sisa pembayaran (misalnya DP 30%) tampilkan notifikasi -->
        <?php if ($pemesanan['sisa_pembayaran'] > 0): ?>
            <p><strong>Notifikasi:</strong> Anda masih memiliki sisa pembayaran yang harus diselesaikan sebelum tanggal mulai menempati kos.</p>
        <?php endif; ?>
        <p><strong>Status Uang Muka:</strong> <?= $pemesanan['status_uang_muka'] ?></p>
        <p><strong>Batas Menempati Kos:</strong> <?= $pemesanan['batas_menempati_kos'] ?></p>
        
        <?php if ($pemesanan['status_uang_muka'] == 'DP 30%' && $pemesanan['sisa_pembayaran'] > 0): ?>
            <p><strong>Sisa Pembayaran:</strong> Rp. <?= number_format($pemesanan['sisa_pembayaran'], 0, ',', '.') ?></p>
        <?php endif; ?>
        
    </div>
   
    <!-- Tombol untuk membuka Modal -->
    <button id="openModalBtn">Pilih Metode Pembayaran</button>

    <!-- Modal -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Pilih Metode Pembayaran</h3>
            <!-- Form untuk memilih metode pembayaran dan mengunggah bukti transfer -->
                 <form action="pembayaran.php?idPemesanan=<?php echo $idPemesanan; ?>&idPenyewa=<?php echo $idPenyewa; ?>" method="post" enctype="multipart/form-data" id="paymentForm">
                <label for="pembayaran">Metode Pembayaran</label>
                <select name="pembayaran" id="pembayaran" required>
                    <option value="QRIS" selected>QRIS</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>

                <!-- Payment Instructions Section -->
                <div id="paymentInstructions">
                    <p><strong>Silakan pilih metode pembayaran untuk melihat instruksi.</strong></p>
                </div>
                <?php if ($tampilkanDurasiSewa): ?>
                    <!-- Input untuk Durasi Sewa Baru -->
                    <label for="durasi_baru">Durasi Sewa Baru (dalam bulan):</label>
                    <input type="number" name="durasi_baru" id="durasi_baru" min="1" required>
                <?php endif; ?>
                 <!-- File Upload for Transfer Proof -->
                <div id="fileUploadSection" style="display:none;">
                    <label for="bukti_transfer">Unggah Bukti Transfer:</label>
                    <input type="file" name="bukti_transfer" id="bukti_transfer" required>
                    <button type="submit" name="unggah_bukti">Unggah Bukti</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript untuk Modal dan Dinamis Metode Pembayaran -->
    <script>
    // Dapatkan modal dan tombol
    var modal = document.getElementById("paymentModal");
    var btn = document.getElementById("openModalBtn");
    var span = document.getElementsByClassName("close")[0];

    // Event listener untuk membuka modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Event listener untuk menutup modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Event listener untuk menutup modal jika klik di luar modal
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    // Handle perubahan pilihan metode pembayaran
    document.getElementById('pembayaran').addEventListener('change', function() {
        var selectedMethod = this.value;
        var instructionsDiv = document.getElementById('paymentInstructions');
        var fileUploadSection = document.getElementById('fileUploadSection');

        if (selectedMethod === 'QRIS') {
            instructionsDiv.innerHTML = "<h4>Silakan scan QRIS di bawah ini untuk melakukan pembayaran:</h4><img src='qris_code.png' alt='QRIS Code' style='width:200px;'>";
        } else if (selectedMethod === 'Bank Transfer') {
            instructionsDiv.innerHTML = "<h4>Silakan transfer ke rekening berikut:</h4><p>Bank ABC<br>Nomor Rekening: 1234567890<br>Atas Nama: Kos XYZ</p>";
        }
        fileUploadSection.style.display = 'block';
    });
    </script>

</body>
</html>
