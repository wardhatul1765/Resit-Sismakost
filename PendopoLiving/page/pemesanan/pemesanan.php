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
                Data Pemesanan
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered text-center" id="dataTables-example">
                        <thead class="thead-dark">
                            <tr>
                                <!-- <th>No</th> -->
                                <th>ID Pemesanan</th>
                                <th>Pemesanan Kamar</th>
                                <th>Uang Muka</th>
                                <th>Status Uang Muka</th>
                                <th>Tenggat Uang Muka</th>
                                <th>Mulai Menempati Kos</th>
                                <th>Batas Menempati Kos</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $sql = $koneksi->query("SELECT * FROM pemesanan WHERE status NOT IN ('Keluar', 'Dibatalkan')");

                                if ($sql === false) {
                                    echo "<tr><td colspan='10'>Error: " . $koneksi->error . "</td></tr>";
                                } else {
                                    while ($data = $sql->fetch_assoc()) {
                                        $statusUangMuka = htmlspecialchars($data['status_uang_muka']);
                                        $badgeClassUM = ($statusUangMuka === 'Lunas') ? 'badge-success' : 'badge-warning';

                                        $statusPemesanan = htmlspecialchars($data['status']);
                                        $badgeClassStatus = ($statusPemesanan === 'Aktif') ? 'badge-info' : 'badge-secondary';
                            ?>
                                        <tr class="odd gradeX">
                                            <!-- <td><?php echo $no++; ?></td> -->
                                            <td><?php echo $data['id_pemesanan']; ?></td>
                                            <td><?php echo $data['pemesanan_kamar']; ?></td>
                                            <td><?php echo formatRupiah($data['uang_muka']); ?></td>
                                            <td>
                                                <a href="page/pemesanan/edit.php?id_pemesanan=<?php echo $data['id_pemesanan'];?>" class="btn btn-primary">Edit</a>
                                                <a onclick="return confirm('Apakah Anda Yakin Akan Menghapus Data ini..?')" 
                                                href="?page=pemesanan&aksi=hapus&idPemesanan=<?php echo $data['id_pemesanan'];?>" class="btn btn-danger">Hapus</a>
                                            </td>
                                        </tr>
                            <?php 
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tombol Tambah Data -->
                <a href="?page=pemesanan&aksi=tambah" class="btn btn-primary" style="margin-bottom: 8px;">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            </div>
        </div>
        <!-- End Advanced Tables -->
    </div>
</div>
