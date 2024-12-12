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
                    <table class="table table-striped table-hover table-bordered text-center" id="dataTables-example">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Nama Penyewa</th>
                                <th>No Telepon</th>
                                <th>Email</th>
                                <!-- <th>Kamar</th> -->
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
                                        $namaKamar = $data['namaKamar'] ?? 'Belum Terpilih';
                                        $fotoJaminan = $data['fotoJaminan'];
                            ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($data['namaPenyewa']); ?></td>
                                            <td><?php echo htmlspecialchars($data['noTelepon']); ?></td>
                                            <td><?php echo htmlspecialchars($data['email']); ?></td>
                                            <td>
                                                <?php if ($fotoJaminan): ?>
                                                    <!-- Gambar kecil dapat diklik untuk melihat lebih besar -->
                                                    <img src="uploads/<?php echo htmlspecialchars($fotoJaminan); ?>" width="50" height="50" class="img-thumbnail" 
                                                         alt="Foto Jaminan" data-toggle="modal" data-target="#jaminanModal<?php echo $data['idPenyewa']; ?>">
                                                <?php else: ?>
                                                    <span class="text-muted">Tidak ada foto</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="?page=penyewa&aksi=edit&idPenyewa=<?php echo $data['idPenyewa'];?>" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')" 
                                                   href="?page=penyewa&aksi=hapus&idPenyewa=<?php echo $data['idPenyewa'];?>" 
                                                   class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Hapus
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Modal untuk melihat gambar jaminan -->
                                        <div class="modal fade" id="jaminanModal<?php echo $data['idPenyewa']; ?>" tabindex="-1" role="dialog" aria-labelledby="jaminanModalLabel<?php echo $data['idPenyewa']; ?>" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="jaminanModalLabel<?php echo $data['idPenyewa']; ?>">Foto Jaminan</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="uploads/<?php echo htmlspecialchars($fotoJaminan); ?>" alt="Foto Jaminan" class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            <?php 
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tombol untuk menambah data -->
                <a href="?page=penyewa&aksi=tambah" class="btn btn-primary" style="margin-bottom: 8px;">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            </div>
        </div>
        <!-- End Advanced Tables -->
    </div>
</div>
