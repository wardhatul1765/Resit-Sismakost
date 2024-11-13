<?php
include 'koneksi.php'; // Pastikan ini sesuai dengan file koneksi database Anda

// Mendapatkan data penyewa berdasarkan idPenyewa dari URL
$idPenyewa = isset($_GET['idPenyewa']) ? intval($_GET['idPenyewa']) : 0;
$query = $koneksi->query("SELECT * FROM penyewa WHERE idPenyewa = $idPenyewa");
$dataPenyewa = $query->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $namaPenyewa = $_POST['namaPenyewa'];
    $noTelepon = $_POST['noTelepon'];
    $email = $_POST['email'];
    $idKamar = $_POST['idKamar'];

    // Update data penyewa
    $updateQuery = $koneksi->prepare("UPDATE penyewa SET namaPenyewa = ?, noTelepon = ?, email = ?, idKamar = ? WHERE idPenyewa = ?");
    $updateQuery->bind_param("sssii", $namaPenyewa, $noTelepon, $email, $idKamar, $idPenyewa);
    
    if ($updateQuery->execute()) {
        echo "Data berhasil diperbarui!";
        header("Location: ?page=penyewa"); // Kembali ke halaman data penyewa
        exit;
    } else {
        echo "Gagal memperbarui data: " . $koneksi->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Penyewa</title>
</head>
<body>
    <h2>Edit Data Penyewa</h2>
    <form action="" method="POST">
        <label>Nama Penyewa:</label>
        <input type="text" name="namaPenyewa" value="<?php echo $dataPenyewa['namaPenyewa']; ?>" required><br><br>

        <label>No Telepon:</label>
        <input type="text" name="noTelepon" value="<?php echo $dataPenyewa['noTelepon']; ?>" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo $dataPenyewa['email']; ?>" required><br><br>

        <label>Pilih Kamar:</label>
        <select name="idKamar">
            <option value="">Tidak Terpilih</option>
            <?php
            // Ambil daftar kamar dari database
            $queryKamar = $koneksi->query("SELECT idKamar, namaKamar FROM kamar");
            while ($kamar = $queryKamar->fetch_assoc()) {
                $selected = $dataPenyewa['idKamar'] == $kamar['idKamar'] ? 'selected' : '';
                echo "<option value='{$kamar['idKamar']}' $selected>{$kamar['namaKamar']}</option>";
            }
            ?>
        </select><br><br>

        <button type="submit">Update</button>
    </form>
</body>
</html>
