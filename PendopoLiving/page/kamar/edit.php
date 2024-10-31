<?php
$idKamar = $_GET['idKamar'];
$sql = $koneksi->query("SELECT * FROM kamar WHERE idKamar = '$idKamar'");
$tampil = $sql->fetch_assoc();
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Ubah Data Kamar
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nama Kamar</label>
                        <input class="form-control" name="namaKamar" value="<?php echo $tampil['namaKamar']; ?>" required/>
                    </div>

                    <div class="form-group">
                        <label>Nomor Kamar</label>
                        <input class="form-control" name="nomorKamar" value="<?php echo $tampil['nomorKamar']; ?>" required/>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>
                        <input class="form-control" name="harga" value="<?php echo $tampil['harga']; ?>" required/>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="Tersedia" <?php if ($tampil['status'] == 'Tersedia') echo 'selected'; ?>>Tersedia</option>
                            <option value="Kosong" <?php if ($tampil['status'] == 'Kosong') echo 'selected'; ?>>Kosong</option>
                            <option value="Booking" <?php if ($tampil['status'] == 'Booking') echo 'selected'; ?>>Booking</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" class="form-control" name="foto"/>
                        <?php if ($tampil['foto']) { ?>
                            <img src="path/to/images/<?php echo $tampil['foto']; ?>" width="100" alt="Foto Kamar"/>
                        <?php } ?>
                    </div>

                    <div>
                        <input type="submit" name="simpan" value="Ubah" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['simpan'])) {
        $namaKamar = $_POST['namaKamar'];
        $nomorKamar = $_POST['nomorKamar'];
        $harga = $_POST['harga'];
        $status = $_POST['status'];
        $foto = $tampil['foto'];

        if (!empty($_FILES['foto']['name'])) {
            $foto = $_FILES['foto']['name'];
            $target = "path/to/images/" . basename($foto);
            move_uploaded_file($_FILES['foto']['tmp_name'], $target);
        }

        $sql = $koneksi->query("UPDATE kamar SET 
            namaKamar='$namaKamar',
            nomorKamar='$nomorKamar',
            harga='$harga',
            status='$status',
            foto='$foto'
            WHERE idKamar='$idKamar'");

        if ($sql) {
            echo '
            <script type="text/javascript">
                alert("Data berhasil disimpan");
                window.location.href="?page=kamar";
            </script>';
        }
    }
}
?>
