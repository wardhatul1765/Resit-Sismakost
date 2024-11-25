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
                Data Transaksi
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                            <tr>
                                <!-- <th>idPembayaran</th> -->
                                <th>Tanggal Pembayaran</th>
                                <th>Batas Pembayaran</th>
                                <th>Durasi Sewa</th>
                                <th>Status Pembayaran</th>
                                <th>idPenyewa</th>
                                <th>Jatuh Tempo</th>
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
                                        <tr class="odd gradeX">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $data['tanggalPembayaran']; ?></td>
                                            <td><?php echo $data['batasPembayaran']; ?></td>
                                            <td><?php echo $data['durasiSewa']; ?></td>
                                            <td><?php echo $data['StatusPembayaran']; ?></td>
                                            <td><?php echo $data['idPenyewa']; ?></td>
                                            <td><?php echo $data['jatuh_tempo']; ?></td>
                                        </tr>
                            <?php 
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Tombol untuk Menambah Data -->
                <a href="?page=pembayaran&aksi=tambah" class="btn btn-primary" style="margin-bottom: 8px;">
                    <i class="fa fa-plus"></i> Tambah
                </a>
                
            </div>
        </div>
        <!-- End Advanced Tables -->
    </div>
</div>
