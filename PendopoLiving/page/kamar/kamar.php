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
                Data Kamar
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kamar</th>
                                <th>Nomor Kamar</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Foto</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $sql = $koneksi->query("SELECT * FROM kamar");

                                if ($sql === false) {
                                    echo "<tr><td colspan='7'>Error: " . $koneksi->error . "</td></tr>";
                                } else {
                                    while ($data = $sql->fetch_assoc()) {
                            ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $data['namaKamar']; ?></td>
                                            <td><?php echo $data['nomorKamar']; ?></td>
                                            <td><?php echo formatRupiah($data['harga']); ?></td>
                                            <td><?php echo $data['status']; ?></td>
                                            <td>
                                                <img src="path/to/images/<?php echo $data['foto']; ?>" alt="Foto Kamar" width="100">
                                            </td>
                                            <td>
                                                <a href="?page=kamar&aksi=ubah&idKamar=<?php echo $data['idKamar'];?>" class="btn btn-primary">Edit</a>
                                                <a onclick="return confirm('Apakah Anda Yakin Akan Menghapus Data ini..?')" 
                                                href="?page=kamar&aksi=hapus&idKamar=<?php echo $data['idKamar'];?>" class="btn btn-danger">Hapus</a>
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
                <a href="?page=kamar&aksi=tambah" class="btn btn-primary" style="margin-bottom: 8px;">
                    <i class="fa fa-plus"></i> Tambah
                </a>
                
        </div>
        <!-- End Advanced Tables -->
    </div>
</div>
