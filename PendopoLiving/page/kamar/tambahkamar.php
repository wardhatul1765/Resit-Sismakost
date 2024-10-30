<?php
// Memeriksa apakah formulir telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['simpan'])) {
        $namaKamar = $_POST['namaKamar'];
        $nomorKamar = $_POST['nomorKamar'];
        $harga = $_POST['harga'];
        $status = $_POST['status'];
        
        // Mengelola unggahan file untuk field 'foto'
        $foto = $_FILES['foto']['name'];
        $target = "path/to/images/" . basename($foto);

        // Menyimpan data ke tabel 'kamar'
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
            $sql = $koneksi->query("INSERT INTO kamar (namaKamar, nomorKamar, harga, status, foto) VALUES (
                '$namaKamar',
                '$nomorKamar',
                '$harga',
                '$status',
                '$foto'
            )");

            if ($sql) {
                echo '
                <script type="text/javascript">
                    alert("Data berhasil ditambahkan");
                    window.location.href="?page=kamar";
                </script>';
            } else {
                echo '<script>alert("Data gagal ditambahkan");</script>';
            }
        } else {
            echo '<script>alert("Gagal mengunggah foto");</script>';
        }
    }
}
?>

<!-- Formulir HTML untuk menambahkan data kamar baru -->
<div class="panel panel-default">
    <div class="panel-heading">
        Tambah Data Kamar
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nama Kamar</label>
                        <input class="form-control" name="namaKamar" required/>
                    </div>

                    <div class="form-group">
                        <label>Nomor Kamar</label>
                        <input class="form-control" name="nomorKamar" required/>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>
                        <input class="form-control" name="harga" required/>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="Tersedia">Tersedia</option>
                            <option value="Kosong">Kosong</option>
                            <option value="Booking">Booking</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" class="form-control" name="foto" required/>
                    </div>

                    <div>
                        <input type="submit" name="simpan" value="Tambah" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
