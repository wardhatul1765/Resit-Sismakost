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
                                <th>Fasilitas</th>
                                <th>Blok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                // Query untuk mengambil data kamar beserta fasilitas (menggunakan GROUP_CONCAT untuk menggabungkan nama fasilitas)
                                $sql = $koneksi->query("
                                    SELECT kamar.*, 
                                           GROUP_CONCAT(fasilitas.namaFasilitas ORDER BY fasilitas.namaFasilitas ASC) AS fasilitasList,
                                           blok.namaBlok
                                    FROM kamar
                                    LEFT JOIN kamar_fasilitas ON kamar.idKamar = kamar_fasilitas.idKamar
                                    LEFT JOIN fasilitas ON kamar_fasilitas.idFasilitas = fasilitas.idFasilitas
                                    LEFT JOIN blok ON kamar.idBlok = blok.idBlok
                                    GROUP BY kamar.idKamar
                                ");

                                if ($sql === false) {
                                    echo "<tr><td colspan='9'>Error: " . $koneksi->error . "</td></tr>";
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
                                                <!-- Gambar kecil yang dapat diklik -->
                                                <img src="uploads/<?php echo $data['foto']; ?>" alt="Foto Kamar" width="100" data-toggle="modal" data-target="#fotoModal<?php echo $data['idKamar']; ?>">
                                            </td>
                                            <td><?php echo $data['fasilitasList']; ?></td> <!-- Menampilkan daftar fasilitas -->
                                            <td><?php echo $data['namaBlok']; ?></td>
                                            <td>
                                                <a href="?page=kamar&aksi=edit&idKamar=<?php echo $data['idKamar'];?>" class="btn btn-primary">Edit</a>
                                                <a onclick="return confirm('Apakah Anda Yakin Akan Menghapus Data ini..?')" 
                                                href="?page=kamar&aksi=hapus&idKamar=<?php echo $data['idKamar'];?>" class="btn btn-danger">Hapus</a>
                                            </td>
                                        </tr>

                                        <!-- Modal untuk menampilkan gambar besar -->
                                        <div class="modal fade" id="fotoModal<?php echo $data['idKamar']; ?>" tabindex="-1" role="dialog" aria-labelledby="fotoModalLabel<?php echo $data['idKamar']; ?>" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                        <h5 class="modal-title" id="fotoModalLabel<?php echo $data['idKamar']; ?>">Foto Kamar</h5>
                                                    </div>
                                                    <div class="modal-body">
                                                        <!-- Gambar besar dalam modal -->
                                                        <img src="uploads/<?php echo $data['foto']; ?>" alt="Foto Kamar" class="img-fluid">
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

                <!-- Buttons for Adding and Exporting -->
                <a href="?page=kamar&aksi=tambah" class="btn btn-primary" style="margin-bottom: 8px;">
                    <i class="fa fa-plus"></i> Tambah
                </a>
                
        </div>
        <!-- End Advanced Tables -->
    </div>
</div>
