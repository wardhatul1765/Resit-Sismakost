<?php
  $idFasilitas = $_GET['idFasilitas'];
  $sql = $koneksi->query("SELECT * FROM fasilitas where idFasilitas = '$idFasilitas'");

  $tampil = $sql->fetch_assoc();

?>


<div class="panel panel-default">
    <div class="panel-heading">
        Ubah data petugas
    </div>

<div class="panel-body">
    <div class="row">
         <div class="col-md-12">
             <form method="POST">
                
                <div class="form-group">
                    <label>Nama Fasilitas</label>
                    <input class="form-control" name="namaFasilitas" value="<?php echo $tampil['namaFasilitas'];?>"/>
                </div>

                <div class="form-group">
                    <label>Biaya Tambahan</label>
                    <input class="form-control" name="biayaTambahan" value="<?php echo $tampil['biayaTambahan'];?>"/>
                </div>

              


                <div>
                <input type="submit" name="simpan" value="ubah" class="btn btn-primary">
                </div>
              


                </div>
            </form>
            </div>
</div>
</div>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['simpan'])) {

        $namaFasilitas = $_POST['namaFasilitas'];
        $biayaTambahan = $_POST['biayaTambahan'];

        $sql = $koneksi->query("UPDATE fasilitas SET namaFasilitas='$namaFasilitas',biayaTambahan='$biayaTambahan' WHERE idFasilitas='$idFasilitas'");

        if ($sql) {
            echo '
            <script type="text/javascript">
                alert("Data berhasil disimpan");
                window.location.href="?page=fasilitas";
            </script>';
        }
    }
}
?>

               