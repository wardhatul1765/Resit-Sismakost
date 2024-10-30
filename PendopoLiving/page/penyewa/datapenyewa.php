<?php
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>


<div class="row">
    <div class="col-md-12">
        <!-- Advanced Tables -->
        <div class="panel panel-default">
            <div class="panel-heading">
                Data Fasilitas
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Fasilitas</th>
                                <th>Biaya Tambahan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $sql = $koneksi->query("SELECT * FROM penyewa");

                                if ($sql === false) {
                                    echo "<tr><td colspan='4'>Error: " . $koneksi->error . "</td></tr>";
                                } else {
                                  
                                    while ($data = $sql->fetch_assoc()) {
                                    ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $data['namaFasilitas']; ?></td>
                                            <td><?php echo formatRupiah($data['biayaTambahan']); ?></td>
                                            <td>
                                                <a href="?page=fasilitas&aksi=ubah&idFasilitas=<?php echo $data['idFasilitas'];?>" class="btn btn-primary">Edit</a>
                                                <a onclick="return confirm('Apakah Anda Yakin Akan Menghapus Data ini..?')" 
                                                href="?page=fasilitas&aksi=hapus&idFasilitas=<?php echo $data['idFasilitas'];?>" class="btn btn-danger">Hapus</a>

                                            </td>
                                        </tr>
                            <?php 
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Buttons for Adding and Exporting -->
                <a href="?page=fasilitas&aksi=tambah" class="btn btn-primary" style="margin-bottom: 8px;">
                    <i class="fa fa-plus"></i> Tambah
                </a>
                
        </div>
        <!-- End Advanced Tables -->
    </div>
</div>


