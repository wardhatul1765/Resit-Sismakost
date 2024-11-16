<?php
// Koneksi database
include('koneksi.php');

// Ambil parameter dari URL
$idKamar = isset($_GET['idKamar']) ? $_GET['idKamar'] : '';
$bringElectronics = isset($_POST['bringElectronics']) ? $_POST['bringElectronics'] : 0;
$paymentOption = isset($_POST['paymentOption']) ? $_POST['paymentOption'] : 'dp'; // default ke 'dp'

// Query untuk mendapatkan informasi kamar
$sql = "SELECT k.idKamar, k.namaKamar, k.harga, b.namaBlok, f.namaFasilitas, f.biayaTambahan, k.status
        FROM kamar k
        JOIN kamar_fasilitas kf ON k.idKamar = kf.idKamar
        JOIN fasilitas f ON kf.idFasilitas = f.idFasilitas
        JOIN blok b ON k.idBlok = b.idBlok
        WHERE k.idKamar = '$idKamar'";
$result = mysqli_query($koneksi, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die('Kamar tidak ditemukan.');
}

$hargaKamar = $row['harga'];
$namaKamar = $row['namaKamar'];
$namaBlok = $row['namaBlok'];
$namaFasilitas = $row['namaFasilitas'];
$biayaTambahan = $row['biayaTambahan'];
$statusKamar = $row['status'];

// Biaya tambahan untuk listrik
$biayaListrik = 15000 * $bringElectronics;

// Total biaya
$totalBiaya = $hargaKamar + $biayaTambahan + $biayaListrik;
$dpBiaya = $totalBiaya * 0.30; // 30% dari total biaya

// Sesuaikan dengan metode pembayaran
if ($paymentOption == 'full') {
    $finalBiaya = $totalBiaya; // Bayar Penuh
} else {
    $finalBiaya = $dpBiaya; // DP 30%
}

// Proses form submission untuk pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan kamar tersedia sebelum melakukan pemesanan
    if ($statusKamar != 'Tersedia') {
        echo "<script>alert('Kamar sudah tidak tersedia.'); window.location.href='index.php';</script>";
        exit;
    }

    $idPenyewa = 1; // ID penyewa, diganti sesuai implementasi sebenarnya
    // Menentukan status uang muka berdasarkan pilihan pembayaran
    $statusUangMuka = ($paymentOption == 'full') ? 'Sudah Bayar' : 'Menunggu Pembayaran';
    $statusPemesanan = ($paymentOption == 'full') ? 'Menunggu Pembayaran' : 'Dibatalkan';

    $startDate = $_POST['startDate']; // Tanggal mulai menempati kos
    $endDate = $_POST['endDate']; // Tanggal batas menempati kos

    $tenggatWaktu = date('Y-m-d H:i:s', strtotime('+2 days')); // Tenggat waktu pembayaran uang muka 2 hari

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);
    
    try {
        // Perubahan status kamar menjadi "booking"
        $updateStatusKamar = "UPDATE kamar SET status = 'booking' WHERE idKamar = '$idKamar'";
        mysqli_query($koneksi, $updateStatusKamar);

        // Query untuk pemesanan
        $sqlPemesanan = "INSERT INTO pemesanan (pemesanan_kamar, uang_muka, status_uang_muka, tenggat_uang_muka, mulai_menempati_kos, batas_menempati_kos, status, id_penyewa, idKamar)
                 VALUES (NOW(), '$finalBiaya', '$statusUangMuka', '$tenggatWaktu', '$startDate', '$endDate', '$statusPemesanan', '$idPenyewa', '$idKamar')";


        if (mysqli_query($koneksi, $sqlPemesanan)) {
            $idPemesanan = mysqli_insert_id($koneksi);
            mysqli_commit($koneksi); // Commit transaksi

            echo "<script>alert('Pemesanan berhasil! Silakan lakukan pembayaran.'); window.location.href='pembayaran.php?idPemesanan=$idPemesanan&idPenyewa=$idPenyewa';</script>";
        } else {
            throw new Exception("Error in pemesanan query: " . mysqli_error($koneksi));
        }
    } catch (Exception $e) {
        mysqli_rollback($koneksi); // Rollback transaksi jika terjadi error
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Kamar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styling untuk form pemesanan */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            padding: 20px;
        }

        .order-container {
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

        .order-summary {
            margin: 20px 0;
        }

        .order-summary p {
            font-size: 18px;
            margin: 5px 0;
        }

        .order-summary .price {
            color: #FF5722;
            font-weight: bold;
        }

        .order-form input[type="number"], .order-form input[type="date"] {
            width: 60px;
            padding: 5px;
            margin: 10px 0;
        }

        .order-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .order-form button:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        // JavaScript untuk menghitung biaya listrik dan total biaya secara dinamis
        function updateBiayaListrik() {
            var barangElektronik = document.getElementById("bringElectronics").value;
            var biayaListrik = 15000 * barangElektronik;
            var hargaKamar = <?= $hargaKamar ?>;
            var biayaTambahan = <?= $biayaTambahan ?>;
            var totalBiaya = hargaKamar + biayaTambahan + biayaListrik;

            var paymentOption = document.getElementById("paymentOption").value;
            var finalBiaya = (paymentOption == "full") ? totalBiaya : (totalBiaya * 0.30);

            document.getElementById("biayaListrik").innerText = "Rp. " + biayaListrik.toLocaleString();
            document.getElementById("totalBiaya").innerText = "Rp. " + finalBiaya.toLocaleString();
        }

        window.onload = function() {
            document.getElementById("bringElectronics").addEventListener("input", updateBiayaListrik);
            document.getElementById("paymentOption").addEventListener("change", updateBiayaListrik);

            // Update tanggal batas menempati kos berdasarkan tanggal mulai
            document.getElementById("startDate").addEventListener("change", function() {
                var startDate = document.getElementById("startDate").value;
                if (startDate) {
                    var endDate = new Date(startDate);
                    endDate.setDate(endDate.getDate() + 30); // Tambahkan 30 hari
                    var dd = String(endDate.getDate()).padStart(2, '0');
                    var mm = String(endDate.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
                    var yyyy = endDate.getFullYear();

                    endDate = yyyy + '-' + mm + '-' + dd;
                    document.getElementById("endDate").value = endDate;
                }
            });
        }
    </script>
</head>
<body>
<div class="order-container">
    <h2>Detail Pemesanan Kamar</h2>

    <div class="order-summary">
        <p><strong>Nama Kamar:</strong> <?= $namaKamar ?></p>
        <p><strong>Blok:</strong> <?= $namaBlok ?></p>
        <p><strong>Fasilitas:</strong> <?= $namaFasilitas ?></p>
        <p><strong>Harga Kamar:</strong> Rp. <?= number_format($hargaKamar, 0, ',', '.') ?></p>
        <p><strong>Biaya Tambahan (Fasilitas):</strong> Rp. <?= number_format($biayaTambahan, 0, ',', '.') ?></p>
        <p><strong>Biaya Listrik:</strong> <span id="biayaListrik">Rp. 0</span></p>
        <p><strong>Total Biaya:</strong> <span id="totalBiaya">Rp. <?= number_format($dpBiaya, 0, ',', '.') ?></span></p>
    </div>

    <form class="order-form" method="POST">
        <label for="bringElectronics">Jumlah Barang Elektronik:</label>
        <input type="number" id="bringElectronics" name="bringElectronics" value="0" min="0">

        <label for="paymentOption">Pilih Metode Pembayaran:</label>
        <select id="paymentOption" name="paymentOption">
            <option value="dp">Uang Muka (30%)</option>
            <option value="full">Bayar Penuh</option>
        </select>

        <label for="startDate">Tanggal Mulai Menempati:</label>
        <input type="date" id="startDate" name="startDate" required>

        <label for="endDate">Tanggal Batas Menempati:</label>
        <input type="date" id="endDate" name="endDate" required>

        <button type="submit">Pesan Kamar</button>
    </form>
</div>
</body>
</html>
