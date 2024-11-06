<?php
session_start();
require 'koneksi.php'; // Pastikan koneksi ke database

// Cek apakah penyewa sudah login
if (!isset($_SESSION['idPenyewa'])) {
    header("Location: login.php"); // Redirect ke login jika belum login
    exit;
}

// Ambil idPenyewa dari sesi dan idKamar dari form pemesanan
$idPenyewa = $_SESSION['idPenyewa'];
$idKamar = $_POST['idKamar'];
$message = '';

// Proses unggah foto jaminan
if (isset($_FILES['fotoJaminan'])) {
    $fotoJaminan = $_FILES['fotoJaminan']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($fotoJaminan);

    // Cek apakah file dapat diunggah
    if (move_uploaded_file($_FILES['fotoJaminan']['tmp_name'], $target_file)) {
        // Update kolom idKamar dan fotoJaminan di tabel penyewa
        $sql = "UPDATE penyewa SET idKamar = ?, fotoJaminan = ? WHERE idPenyewa = ?";
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("ssi", $idKamar, $target_file, $idPenyewa);

        if ($stmt->execute()) {
            $message = "Pemesanan kamar berhasil!";
        } else {
            $message = "Gagal melakukan pemesanan kamar: " . $koneksi->error;
        }
    } else {
        $message = "Gagal mengunggah foto jaminan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemesanan Kamar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <form method="post" enctype="multipart/form-data">
            <h1>Pemesanan Kamar</h1>

            <input type="hidden" name="idKamar" value="<?php echo htmlspecialchars($idKamar); ?>">

            <div class="input-box">
                <label for="fotoJaminan">Unggah Foto Jaminan:</label>
                <input type="file" name="fotoJaminan" required>
            </div>

            <button type="submit" class="btn">Konfirmasi Pemesanan</button>

            <?php if ($message): ?>
                <p style="color: red;"><?php echo $message; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
