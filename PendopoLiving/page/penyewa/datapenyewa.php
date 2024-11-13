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
                Data Penyewa
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Penyewa</th>
                                <th>No Telepon</th>
                                <th>Email</th>
                                <th>Kamar</th>
                                <th>Foto Jaminan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                // Query untuk mengambil data dari tabel penyewa dan kamar
                                $sql = $koneksi->query("
                                    SELECT penyewa.*, kamar.namaKamar 
                                    FROM penyewa 
                                    LEFT JOIN kamar ON penyewa.idKamar = kamar.idKamar
                                ");

                                if ($sql === false) {
                                    echo "<tr><td colspan='7'>Error: " . $koneksi->error . "</td></tr>";
                                } else {
                                    while ($data = $sql->fetch_assoc()) {
                            ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $data['namaPenyewa']; ?></td>
                                            <td><?php echo $data['noTelepon']; ?></td>
                                            <td><?php echo $data['email']; ?></td>
                                            <td><?php echo $data['namaKamar'] ?? 'Belum Terpilih'; ?></td>
                                            <td>
                                                <?php if ($data['fotoJaminan']): ?>
                                                    <img src="path/to/folder/<?php echo $data['fotoJaminan']; ?>" width="50" height="50" alt="Foto Jaminan">
                                                <?php else: ?>
                                                    Tidak ada foto
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="?page=penyewa&aksi=ubah&idPenyewa=<?php echo $data['idPenyewa'];?>" class="btn btn-primary">Edit</a>
                                                <a onclick="return confirm('Apakah Anda Yakin Akan Menghapus Data ini..?')" 
                                                href="?page=penyewa&aksi=hapus&idPenyewa=<?php echo $data['idPenyewa'];?>" class="btn btn-danger">Hapus</a>
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
                <a href="?page=penyewa&aksi=tambah" class="btn btn-primary" style="margin-bottom: 8px;">
                    <i class="fa fa-plus"></i> Tambah
                </a>
                
            </div>
        </div>
        <!-- End Advanced Tables -->
    </div>
</div>
