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
               p.tenggat_uang_muka, p.mulai_menempati_kos, p.batas_menempati_kos, k.namaKamar, b.namaBlok, pm.idPembayaran 
        FROM pemesanan p
        JOIN kamar k ON p.idKamar = k.idKamar
        JOIN blok b ON k.idBlok = b.idBlok
        LEFT JOIN pembayaran pm ON pm.id_pemesanan = p.id_pemesanan
        WHERE p.id_penyewa = '$idPenyewa' AND p.is_hidden = FALSE";

$result = mysqli_query($koneksi, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}

if (isset($_GET['action']) && $_GET['action'] === 'batalkan' && isset($_GET['idPemesanan'])) {
    $idPemesanan = $_GET['idPemesanan'];

    // Cek apakah status pesanan adalah "Menunggu Pembayaran"
    $cekStatus = "SELECT status FROM pemesanan WHERE id_pemesanan = '$idPemesanan' AND id_penyewa = '$idPenyewa'";
    $resultCek = mysqli_query($koneksi, $cekStatus);
    $rowStatus = mysqli_fetch_assoc($resultCek);

    if ($rowStatus && $rowStatus['status'] === 'Menunggu Pembayaran') {
        // Update status pesanan menjadi "Dibatalkan"
        $queryBatalkan = "UPDATE pemesanan 
                          SET status = 'Dibatalkan', is_hidden = TRUE 
                          WHERE id_pemesanan = '$idPemesanan' AND id_penyewa = '$idPenyewa'";

        // Update status kamar menjadi 'Tersedia'
        $updateKamarStatus = "UPDATE kamar 
                              SET status = 'Tersedia' 
                              WHERE idKamar = (SELECT idKamar FROM pemesanan WHERE id_pemesanan = '$idPemesanan' AND id_penyewa = '$idPenyewa')";

        if (mysqli_query($koneksi, $queryBatalkan) && mysqli_query($koneksi, $updateKamarStatus)) {
            echo "<script>alert('Pesanan berhasil dibatalkan. Status kamar diubah menjadi Tersedia.'); window.location.href='pesananku.php';</script>";
        } else {
            echo "<script>alert('Gagal membatalkan pesanan'); window.location.href='pesananku.php';</script>";
        }
    } else {
        echo "<script>alert('Pesanan tidak dapat dibatalkan.'); window.location.href='pesananku.php';</script>";
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'keluar_kost') {
    // Update status semua pesanan penyewa menjadi "Keluar"
    $updateStatusPesanan = "UPDATE pemesanan 
                             SET status = 'Keluar' 
                             WHERE id_penyewa = '$idPenyewa' AND status NOT IN ('Dibatalkan', 'Keluar')";

    // Update status kamar yang terkait menjadi "Tersedia"
    $updateStatusKamar = "UPDATE kamar 
                          SET status = 'Tersedia' 
                          WHERE idKamar IN (
                              SELECT idKamar 
                              FROM pemesanan 
                              WHERE id_penyewa = '$idPenyewa' AND status = 'Keluar'
                          )";

    if (mysqli_query($koneksi, $updateStatusPesanan) && mysqli_query($koneksi, $updateStatusKamar)) {
        echo "<script>alert('Anda telah keluar dari kost. Semua pesanan telah diperbarui.'); window.location.href='pesananku.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status pesanan.'); window.location.href='pesananku.php';</script>";
    }
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
            // Ambil sisa pembayaran dan uang muka dari database
            $query = "SELECT sisa_pembayaran, uang_muka FROM pemesanan WHERE id_pemesanan = '$idPemesanan'";
            $resultQuery = mysqli_query($koneksi, $query);
            $row = mysqli_fetch_assoc($resultQuery);

            if ($row) {
                $sisaPembayaran = $row['sisa_pembayaran'];
                $uangMuka = $row['uang_muka'];

                // Hitung total pembayaran
                $totalPembayaran = $sisaPembayaran + $uangMuka;

                // Update status pemesanan: set sisa_pembayaran menjadi 0 dan total pembayaran
                $updatePemesanan = "UPDATE pemesanan 
                                    SET sisa_pembayaran = 0, 
                                        uang_muka = '$totalPembayaran', 
                                        status = 'Menunggu Dikonfirmasi', 
                                        status_uang_muka = 'Bayar Penuh', 
                                        bukti_transfer = '$fileName'
                                    WHERE id_pemesanan = '$idPemesanan'";

                // Update status pembayaran menjadi 'Lunas'
                $updatePembayaran = "UPDATE pembayaran 
                                     SET statusPembayaran = 'Lunas'
                                     WHERE idPembayaran = '$idPembayaran'";

                // Jalankan kedua query
                if (mysqli_query($koneksi, $updatePemesanan) && mysqli_query($koneksi, $updatePembayaran)) {
                    echo "<script>alert('Pembayaran berhasil!'); window.location.href='pesananku.php';</script>";
                } else {
                    echo "<script>alert('Kesalahan saat memperbarui data pembayaran.');</script>";
                }
                } else {
                echo "<script>alert('Data pemesanan tidak ditemukan.');</script>";
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
    <div class="d-flex justify-content-between mb-4">
            <a href="index.php" class="btn btn-primary">Kembali</a>
            <button class="btn btn-danger" onclick="showConfirmModal()">Keluar Kost</button>
    </div>
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
                                <!-- Tombol Bayar Pembayaran Awal -->
                                <?php if ($statusUangMuka === 'DP 30%' && $status === 'Menunggu Pembayaran'): ?>
                                    <a href="pembayaran.php?idPemesanan=<?= $idPemesanan; ?>&idPenyewa=<?= $idPenyewa; ?>" class="btn btn-success btn-sm">
                                        Bayar Pembayaran Awal
                                    </a>
                                <?php elseif ($statusUangMuka === 'DP 30%' && $status === 'Menunggu Dikonfirmasi'): ?>
                                    <!-- Tombol Bayar Sisa Pembayaran -->
                                    <a href="javascript:void(0)" onclick="showPaymentModal(<?= $idPemesanan ?>)" class="btn btn-warning btn-sm">
                                        Bayar Sisa Pembayaran
                                    </a>
                                <?php elseif ($statusUangMuka === 'Bayar Penuh' && $status === 'Menunggu Pembayaran'): ?>
                                    <!-- Tombol Bayar jika status uang muka Bayar Penuh -->
                                    <a href="javascript:void(0)" onclick="showPaymentModal(<?= $idPemesanan ?>)" class="btn btn-primary btn-sm">
                                        Bayar
                                    </a>
                                <?php endif; ?>

                                <!-- Tombol Batalkan Pesanan -->
                                <?php if ($status === 'Menunggu Pembayaran'): ?>
                                    <a href="pesananku.php?action=batalkan&idPemesanan=<?= $idPemesanan; ?>" 
                                    class="btn btn-danger btn-sm" 
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                        Batalkan Pesanan
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Tidak Dapat Dibatalkan</span>
                                <?php endif; ?>
                                
                                <!-- Tombol Perpanjang Sewa -->
                                <?php
                                $batasMenempatiKos = date_create($row['batas_menempati_kos']);
                                $tanggalSekarang = date_create();
                                $selisihHari = date_diff($tanggalSekarang, $batasMenempatiKos)->days;

                                if ($selisihHari <= 7): // Jika 7 hari sebelum batas menempati kos
                                ?>
                                    <a href="perpanjangan.php?idPemesanan=<?= $idPemesanan; ?>" class="btn btn-info btn-sm">
                                        Perpanjang Sewa
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
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

<!-- Modal Konfirmasi Keluar Kost -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Konfirmasi Keluar Kost</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin keluar dari kost? Anda tidak dapat menempati kamar sewa jika Anda keluar dari Kost.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="pesananku.php?action=keluar_kost" id="confirmButton" class="btn btn-danger" disabled>Konfirmasi</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pilih Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="metodePembayaranForm" onsubmit="showPaymentInstructions(event)">
                    <div class="mb-3">
                        <label for="pembayaran" class="form-label">Metode Pembayaran:</label>
                        <select name="pembayaran" id="pembayaran" class="form-select" required>
                            <option value="" selected disabled>Pilih metode pembayaran</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Pilih Metode</button>
                </form>
                <div id="paymentInstructions" class="mt-4"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>


<!-- Overlay Modal -->
<div id="overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 999;"></div>

<script>
    let currentIdPemesanan = null;

    function showPaymentModal(idPemesanan) {
        currentIdPemesanan = idPemesanan;
        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        paymentModal.show();
    }

    function showPaymentInstructions(event) {
        event.preventDefault(); // Mencegah reload halaman
        const metode = document.getElementById('pembayaran').value;
        const instructionsDiv = document.getElementById('paymentInstructions');
        
        let instructions = '';
        if (metode === 'QRIS') {
            instructions = `
                <div class="text-center">
                    <h5>Silakan scan QRIS di bawah ini untuk melakukan pembayaran</h5>
                    <img src='img/qris.jpeg' alt='QRIS Code' class="img-fluid mt-3" style="max-width:200px;">
                </div>
            `;
        } else if (metode === 'Bank Transfer') {
            instructions = `
                <div>
                    <h5>Silakan transfer ke rekening berikut:</h5>
                    <p>
                        <strong>Bank:</strong> Bank ABC<br>
                        <strong>Nomor Rekening:</strong> 1234567890<br>
                        <strong>Atas Nama:</strong> Kos XYZ
                    </p>
                </div>
            `;
        }

        instructions += `
            <form action="pesananku.php" method="post" enctype="multipart/form-data" class="mt-3">
                <div class="mb-3">
                    <label for="bukti_transfer" class="form-label">Unggah Bukti Transfer:</label>
                    <input type="file" name="bukti_transfer" id="bukti_transfer" class="form-control" required>
                    <input type="hidden" name="metode_pembayaran" value="${metode}">
                    <input type="hidden" name="idPemesanan" value="${currentIdPemesanan}">
                    <input type="hidden" name="idPembayaran" value="<?= $idPembayaran; ?>">
                </div>
                <button type="submit" name="unggah_bukti" class="btn btn-success w-100">Unggah Bukti</button>
            </form>
        `;
        
        instructionsDiv.innerHTML = instructions;
    }

    // konfirmasi
    function showConfirmModal() {
            const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
            confirmModal.show();
    }

    function showConfirmModal() {
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();

        // tombol konfirmasi
        const confirmButton = document.querySelector('#confirmModal .btn-danger');
        confirmButton.disabled = true; 
        confirmButton.style.backgroundColor = "#d6d6d6";
        confirmButton.style.cursor = "not-allowed"; 
        confirmButton.setAttribute("onclick", "return false;"); 

        let countdown = 5; // waktu hitung mundur 5 detik
        confirmButton.textContent = `Konfirmasi (${countdown})`; 

        const interval = setInterval(() => {
            countdown--;
            confirmButton.textContent = `Konfirmasi (${countdown})`; 

            if (countdown <= 0) {
                clearInterval(interval);
                confirmButton.disabled = false; 
                confirmButton.style.backgroundColor = ""; 
                confirmButton.style.cursor = ""; 
                confirmButton.textContent = "Konfirmasi";
                confirmButton.removeAttribute("onclick");
            }
        }, 1000);
    }


</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
