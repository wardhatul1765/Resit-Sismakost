<?php
$id_pemesan = $_GET['id_pemesanan'];
$sql = $koneksi->query("SELECT * FROM pemesanan WHERE id_pemesanan = '$id_pemesanan'");
$tampil = $sql->fetch_assoc();
?>

<div class="panel panel-default">
    <div class="panel-heading">
        Ubah Data Pemesanan
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <form method="POST">
                    <div class="form-group">
                        <label>Pemesanan Kamar</label>
                        <input class="form-control" name="pemesanan_kamar" value="<?php echo $tampil['pemesanan_kamar']; ?>" required/>
                    </div>

                    <div class="form-group">
                        <label>Uang Muka</label>
                        <input class="form-control" name="uang_muka" value="<?php echo $tampil['uang_muka']; ?>" required/>
                    </div>

                    <div class="form-group">
                        <label>Status Uang Muka</label>
                        <select class="form-control" name="status_uang_muka">
                            <option value="Belum Dibayar" <?php if ($tampil['status_uang_muka'] == 'Belum Dibayar') echo 'selected'; ?>>Belum Dibayar</option>
                            <option value="Sudah Dibayar" <?php if ($tampil['status_uang_muka'] == 'Sudah Dibayar') echo 'selected'; ?>>Sudah Dibayar</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tenggat Uang Muka</label>
                        <input type="date" class="form-control" name="tenggat_uang_muka" value="<?php echo $tampil['tenggat_uang_muka']; ?>" required/>
                    </div>

                    <div class="form-group">
                        <label>Mulai Menempati Kos</label>
                        <input type="date" class="form-control" name="mulai_menempati_kos" value="<?php echo $tampil['mulai_menempati_kos']; ?>" required/>
                    </div>

                    <div class="form-group">
                        <label>Batas Menempati Kos</label>
                        <input type="date" class="form-control" name="batas_menempati_kos" value="<?php echo $tampil['batas_menempati_kos']; ?>" required/>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="Aktif" <?php if ($tampil['status'] == 'Aktif') echo 'selected'; ?>>Aktif</option>
                            <option value="Tidak Aktif" <?php if ($tampil['status'] == 'Tidak Aktif') echo 'selected'; ?>>Tidak Aktif</option>
                        </select>
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
        $pemesanan_kamar = $_POST['pemesanan_kamar'];
        $uang_muka = $_POST['uang_muka'];
        $status_uang_muka = $_POST['status_uang_muka'];
        $tenggat_uang_muka = $_POST['tenggat_uang_muka'];
        $mulai_menempati_kos = $_POST['mulai_menempati_kos'];
        $batas_menempati_kos = $_POST['batas_menempati_kos'];
        $status = $_POST['status'];

        $sql = $koneksi->query("UPDATE pemesanan SET 
            pemesanan_kamar='$pemesanan_kamar',
            uang_muka='$uang_muka',
            status_uang_muka='$status_uang_muka',
            tenggat_uang_muka='$tenggat_uang_muka',
            mulai_menempati_kos='$mulai_menempati_kos',
            batas_menempati_kos='$batas_menempati_kos',
            status='$status'
            WHERE id_pemesan='$id_pemesanan'");

        if ($sql) {
            echo '
            <script type="text/javascript">
                alert("Data berhasil disimpan");
                window.location.href="?page=pemesanan";
            </script>';
        } else {
            echo '<script>alert("Data gagal disimpan");</script>';
        }
    }
}
?>
