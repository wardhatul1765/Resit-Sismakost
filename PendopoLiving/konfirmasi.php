<?php
include 'koneksi.php';

// Ambil data dari URL atau POST
$id_pemesanan = $_GET['id_pemesanan']; // ID pemesanan yang dikonfirmasi
$id_kamar = $_GET['id_kamar']; // ID kamar yang terkait
$id_penyewa = $_GET['id_penyewa']; // ID penyewa yang melakukan pemesanan
$pdf_path = $_GET['pdf_path']; // Path PDF kuitansi yang telah dibuat

// Update status pemesanan menjadi "Dikonfirmasi"
$queryUpdatePemesanan = "UPDATE pemesanan SET status = 'Dikonfirmasi' WHERE id_pemesanan = ?";
$stmt = $koneksi->prepare($queryUpdatePemesanan);
$stmt->bind_param("i", $id_pemesanan);
$stmt->execute();
$stmt->close();

// Update status kamar menjadi "Kosong"
$queryUpdateKamar = "UPDATE kamar SET status = 'Kosong' WHERE idKamar = ?";
$stmt = $koneksi->prepare($queryUpdateKamar);
$stmt->bind_param("i", $id_kamar);
$stmt->execute();
$stmt->close();

// Ambil nomor telepon penyewa
$queryPenyewa = "SELECT noTelepon FROM penyewa WHERE idPenyewa = ?";
$stmt = $koneksi->prepare($queryPenyewa);
$stmt->bind_param("i", $id_penyewa);
$stmt->execute();
$stmt->bind_result($noTelepon);
$stmt->fetch();
$stmt->close();

if (!$noTelepon) {
    echo "Nomor telepon tidak ditemukan untuk ID penyewa $id_penyewa.";
    exit;
}

// Ubah nomor telepon yang dimulai dengan '08' menjadi '62' (format internasional)
if (substr($noTelepon, 0, 2) == '08') {
    // Mengganti '08' menjadi '62' untuk nomor telepon Indonesia
    $noTelepon = '62' . substr($noTelepon, 1);
}

// Buat pesan kuitansi
// $kuitansi = "Pemesanan Anda telah dikonfirmasi. Terima kasih telah memilih kami. Kamar Anda telah berhasil dipesan. Klik link berikut untuk melihat kuitansi: " . urlencode($pdf_path);
$kuitansi = "Pemesanan Anda telah dikonfirmasi. Terima kasih telah memilih kami. Kamar Anda telah berhasil dipesan. Silakan hubungi kami untuk mengatur waktu bertemu dan mengambil kunci.";
// Mengarahkan ke WhatsApp dengan nomor telepon dan pesan
$whatsappURL = "https://api.whatsapp.com/send/?phone=$noTelepon&text=" . urlencode($kuitansi);

// Redirect ke WhatsApp
header("Location: $whatsappURL");
exit;
?>
