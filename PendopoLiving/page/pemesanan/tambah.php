<div class="panel panel-default">
    <div class="panel-heading">
        Tambah Data Pemesanan
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <form method="POST">
                    <div class="form-group">
                        <label>ID Pemesan</label>
                        <input class="form-control" name="id_pemesan" required/>
                    </div>

                    <div class="form-group">
                        <label>Pemesanan Kamar</label>
                        <input class="form-control" name="pemesanan_kamar" required/>
                    </div>

                    <div class="form-group">
                        <label>Uang Muka</label>
                        <input class="form-control" name="uang_muka" required/>
                    </div>

                    <div class="form-group">
                        <label>Status Uang Muka</label>
                        <select class="form-control" name="status_uang_muka">
                            <option value="Belum Dibayar">Belum Dibayar</option>
                            <option value="Sudah Dibayar">Sudah Dibayar</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tenggat Uang Muka</label>
                        <input type="date" class="form-control" name="tenggat_uang_muka" required/>
                    </div>

                    <div class="form-group">
                        <label>Mulai Menempati Kos</label>
                        <input type="date" class="form-control" name="mulai_menempati_kos" required/>
                    </div>

                    <div class="form-group">
                        <label>Batas Menempati Kos</label>
                        <input type="date" class="form-control" name="batas_menempati_kos" required/>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                        <input type="submit" name="simpan" value="Tambah" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
