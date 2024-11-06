<?php
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}
?>

<<<<<<< HEAD
=======

>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
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
<<<<<<< HEAD
                                <th>No</th>
=======
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
                                <th>idPembayaran</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Batas Pembayaran</th>
                                <th>Durasi Sewa</th>
                                <th>Status Pembayaran</th>
                                <th>idPenyewa</th>
<<<<<<< HEAD
                                <th>Jatuh Tempo</th>
=======
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $no = 1;
<<<<<<< HEAD
                                $sql = $koneksi->query("SELECT * FROM pembayaran");

                                if ($sql === false) {
                                    echo "<tr><td colspan='8'>Error: " . $koneksi->error . "</td></tr>";
                                } else {
                                    while ($data = $sql->fetch_assoc()) {
                            ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $data['idPembayaran']; ?></td>
                                            <td><?php echo $data['tanggalPembayaran']; ?></td>
                                            <td><?php echo $data['batasPembayaran']; ?></td>
                                            <td><?php echo $data['durasiSewa']; ?></td>
                                            <td><?php echo $data['StatusPembayaran']; ?></td>
                                            <td><?php echo $data['idPenyewa']; ?></td>
                                            <td><?php echo $data['jatuh_tempo']; ?></td>
=======
                                $sql = $koneksi->query("SELECT * FROM fasilitas");

                                if ($sql === false) {
                                    echo "<tr><td colspan='4'>Error: " . $koneksi->error . "</td></tr>";
                                } else {
                                  
                                    while ($data = $sql->fetch_assoc()) {
                                    ?>
                                        <tr class="odd gradeX">
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $data['namaFasilitas']; ?></td>
                                            <td><?php echo $data['Stok']; ?></td>
                                            <td><?php echo formatRupiah($data['biayaTambahan']); ?></td>
                                            <td>
                                                <a href="?page=fasilitas&aksi=ubah&idFasilitas=<?php echo $data['idFasilitas'];?>" class="btn btn-primary">Edit</a>
                                                <a onclick="return confirm('Apakah Anda Yakin Akan Menghapus Data ini..?')" 
                                                href="?page=fasilitas&aksi=hapus&idFasilitas=<?php echo $data['idFasilitas'];?>" class="btn btn-danger">Hapus</a>

                                            </td>
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
                                        </tr>
                            <?php 
                                    }
                                } 
                            ?>
                        </tbody>
                    </table>
                </div>

<<<<<<< HEAD
                <!-- Tombol untuk Menambah Data -->
                <a href="?page=pembayaran&aksi=tambah" class="btn btn-primary" style="margin-bottom: 8px;">
                    <i class="fa fa-plus"></i> Tambah
                </a>
                
            </div>
=======
                <!-- Buttons for Adding and Exporting -->
                <a href="?page=fasilitas&aksi=tambah" class="btn btn-primary" style="margin-bottom: 8px;">
                    <i class="fa fa-plus"></i> Tambah
                </a>
                
>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
        </div>
        <!-- End Advanced Tables -->
    </div>
</div>
<<<<<<< HEAD
=======


>>>>>>> 97ddaff488e5f69b89d0db42fb818e930cec56d7
