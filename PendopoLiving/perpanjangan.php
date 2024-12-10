<?php
session_start();
include('koneksi.php');

// Pastikan penyewa sudah login
if (!isset($_SESSION['idPenyewa'])) {
    echo "<script>alert('Harap login terlebih dahulu.'); window.location.href='login.php';</script>";
    exit;
}

$idPenyewa = $_SESSION['idPenyewa'];

if (isset($_GET['idPemesanan'])) {
    $idPemesanan = $_GET['idPemesanan'];

    // Ambil data pemesanan
    $sql = "SELECT p.id_pemesanan, p.mulai_menempati_kos, p.batas_menempati_kos, k.namaKamar, k.harga
            FROM pemesanan p
            JOIN kamar k ON p.idKamar = k.idKamar
            WHERE p.id_pemesanan = '$idPemesanan' AND p.id_penyewa = '$idPenyewa'";
    
    $result = mysqli_query($koneksi, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        echo "<script>alert('Pesanan tidak ditemukan.'); window.location.href='pesananku.php';</script>";
        exit;
    }

    // Proses perpanjangan
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $durasiPerpanjangan = $_POST['durasi_perpanjangan'];
        $metodePembayaran = $_POST['metode_pembayaran'];

        // Validasi dan unggah bukti transfer
        if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] == 0) {
            $targetDir = "uploads/";
            $fileName = basename($_FILES['bukti_transfer']['name']);
            $targetFilePath = $targetDir . $fileName;
            $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

            $validExtensions = ["jpg", "jpeg", "png", "gif", "pdf"];
            if (in_array($fileType, $validExtensions)) {
                if (move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $targetFilePath)) {
                    // Hitung biaya perpanjangan
                    $biayaPerpanjangan = $row['harga'] * $durasiPerpanjangan;

                    // Perpanjang batas masa sewa
                    $batasMenempatiKos = date_create($row['batas_menempati_kos']);
                    date_add($batasMenempatiKos, date_interval_create_from_date_string("$durasiPerpanjangan months"));
                    $batasMenempatiKosBaru = date_format($batasMenempatiKos, 'Y-m-d');

                    // Update batas masa sewa dan ubah status menjadi Perpanjangan
                    $update = "UPDATE pemesanan 
                               SET batas_menempati_kos = '$batasMenempatiKosBaru', 
                                   status = 'Perpanjangan', 
                                   bukti_transfer = '$fileName' 
                               WHERE id_pemesanan = '$idPemesanan'";

                    if (mysqli_query($koneksi, $update)) {
                        echo "<script>alert('Perpanjangan masa sewa berhasil. Metode pembayaran: $metodePembayaran.'); window.location.href='pesananku.php';</script>";
                    } else {
                        echo "<script>alert('Gagal memperpanjang masa sewa. Silakan coba lagi.');</script>";
                    }
                } else {
                    echo "<script>alert('Gagal mengunggah bukti pembayaran. Silakan coba lagi.');</script>";
                }
            } else {
                echo "<script>alert('Format file tidak valid. Hanya JPG, PNG, JPEG, GIF, dan PDF yang diperbolehkan.');</script>";
            }
        } else {
            echo "<script>alert('Harap unggah bukti pembayaran.');</script>";
        }
    }
} else {
    echo "<script>alert('ID Pemesanan tidak ditemukan.'); window.location.href='pesananku.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpanjang Sewa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Perpanjang Sewa Kamar</h2>
    <p>Anda ingin memperpanjang sewa kamar <strong><?= htmlspecialchars($row['namaKamar']) ?></strong>?</p>
    <p>Masa sewa Anda saat ini berakhir pada: <?= htmlspecialchars($row['batas_menempati_kos']) ?></p>

    <form method="post">
        <div class="mb-3">
            <label for="durasi_perpanjangan" class="form-label">Durasi Perpanjangan (Bulan):</label>
            <input type="number" class="form-control" id="durasi_perpanjangan" name="durasi_perpanjangan" required min="1" onchange="updateHarga(<?= $row['harga'] ?>)">
        </div>
        <button type="button" class="btn btn-primary" onclick="showPaymentModal()">Bayar Sekarang</button>
    </form>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Pilih Metode Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Pembayaran:</label>
                        <select name="metode_pembayaran" id="metode_pembayaran" class="form-select" required>
                            <option value="" selected disabled>Pilih metode pembayaran</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                        </select>
                    </div>
                    <div id="paymentInstructions" class="mb-3">
                        <p><strong>Silakan pilih metode pembayaran untuk melihat instruksi.</strong></p>
                    </div>
                    <div class="mb-3">
                        <label for="durasi_perpanjangan_modal" class="form-label">Durasi Perpanjangan (Bulan):</label>
                        <input type="number" class="form-control" id="durasi_perpanjangan_modal" name="durasi_perpanjangan" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Biaya Perpanjangan:</label>
                        <p id="biaya_perpanjangan_modal">Rp0</p>
                        <label for="bukti_transfer" class="form-label">Unggah Bukti Transfer:</label>
                        <input type="file" class="form-control" id="bukti_transfer" name="bukti_transfer" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Konfirmasi Pembayaran</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Perhitungan biaya berdasarkan durasi perpanjangan
    const biayaPerBulan = 300000; // Sesuaikan harga per bulan
    const durasiInput = document.getElementById("durasi_perpanjangan_modal");
    const biayaDisplay = document.getElementById("biaya_perpanjangan_modal");
    const metodeSelect = document.getElementById("metode_pembayaran");
    const instructionsDiv = document.getElementById("paymentInstructions");

    metodeSelect.addEventListener("change", function () {
        const method = metodeSelect.value;

        if (method === "QRIS") {
            instructionsDiv.innerHTML = `
                <h5>Scan QRIS berikut untuk pembayaran:</h5>
                <img src="qris_code.png" alt="QRIS Code" class="img-fluid" style="max-width: 200px;">
            `;
        } else if (method === "Bank Transfer") {
            instructionsDiv.innerHTML = `
                <h5>Transfer ke rekening berikut:</h5>
                <p>Bank ABC<br>Nomor Rekening: 1234567890<br>Atas Nama: Kos Elisa</p>
            `;
        } else {
            instructionsDiv.innerHTML = '<p><strong>Silakan pilih metode pembayaran untuk melihat instruksi.</strong></p>';
        }
    });

    // Simulasi pengisian durasi dan biaya
    durasiInput.value = 3;
    const totalBiaya = biayaPerBulan * durasiInput.value;
    biayaDisplay.textContent = `Rp${totalBiaya.toLocaleString("id-ID")}`;

    function goBack() {
        window.history.back();
    }

    document.querySelector('form').addEventListener('submit', function (e) {
        const metode = document.getElementById('metode_pembayaran').value;
        if (!metode) {
            e.preventDefault();
            alert('Harap pilih metode pembayaran terlebih dahulu!');
        }
    });

    const hargaPerBulan = <?= $row['harga'] ?>;

    function updateHarga(hargaPerBulan) {
        const durasi = parseInt(document.getElementById('durasi_perpanjangan').value) || 0;
        const totalBiaya = durasi * hargaPerBulan;

        document.getElementById('durasi_perpanjangan_modal').value = durasi;
        document.getElementById('biaya_perpanjangan_modal').textContent = `Rp${totalBiaya.toLocaleString('id-ID')}`;
    }

    function showPaymentModal() {
        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        paymentModal.show();
    }
</script>
</body>
</html>
