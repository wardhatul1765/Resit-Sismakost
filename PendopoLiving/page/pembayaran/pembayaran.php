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
                Data Pembayaran
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered text-center">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Batas Pembayaran</th>
                                <th>Durasi Sewa</th>
                                <th>Status Pembayaran</th>
                                <th>ID Penyewa</th>
                                <th>Jatuh Tempo</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
                                $sql = $koneksi->query("SELECT * FROM pembayaran");

                                if ($sql === false) {
                                    echo "<tr><td colspan='8'>Error: " . $koneksi->error . "</td></tr>";
                                } else {
                                    while ($data = $sql->fetch_assoc()) {
                            ?>
                                        <tr>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo htmlspecialchars($data['tanggalPembayaran']); ?></td>
                                            <td><?php echo htmlspecialchars($data['batasPembayaran']); ?></td>
                                            <td><?php echo htmlspecialchars($data['durasiSewa']); ?> bulan</td>
                                            <td>
                                                <?php
                                                    $status = htmlspecialchars($data['StatusPembayaran']);
                                                    $badgeClass = ($status === 'Lunas') ? 'badge-success' : 'badge-danger';
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>">
                                                    <?php echo $status; ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($data['idPenyewa']); ?></td>
                                            <td><?php echo htmlspecialchars($data['jatuh_tempo']); ?></td>
                                            <td>
                                                <!-- <a href="?page=pembayaran&aksi=detail&id=<?php echo $data['idPembayaran']; ?>" 
                                                   class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i> Detail
                                                </a>
                                                <a onclick="return confirm('Apakah Anda yakin akan menghapus data ini?')" 
                                                   href="?page=pembayaran&aksi=hapus&id=<?php echo $data['idPembayaran']; ?>" 
                                                   class="btn btn-danger btn-sm">
                                                    <i class="fa fa-trash"></i> Hapus -->
                                                </a>
                                            </td>
                                        </tr>
                            <?php 
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- End Advanced Tables -->
    </div>
</div>
