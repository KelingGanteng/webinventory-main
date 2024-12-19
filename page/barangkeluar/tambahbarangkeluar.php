<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Barang Keluar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="body">
                    <form method="POST" enctype="multipart/form-data">
                        <label for="">Tanggal Keluar</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="date" name="tanggal_keluar" class="form-control" id="tanggal_keluar"
                                    value="<?php echo date('Y-m-d'); ?>" />
                            </div>
                        </div>

                        <!-- Input Barang -->
                        <label for="">Barang</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select name="barang" id="cmb_barang" class="form-control select2" onchange="updateTotalStok()">
                                    <option value="">-- Pilih Barang --</option>
                                    <?php
                                    $sql = $koneksi->query("SELECT id, kode_barang, nama_barang, jumlah, satuan 
                                                        FROM gudang 
                                                        WHERE nama_barang IS NOT NULL 
                                                        ORDER BY nama_barang");
                                    
                                    while ($data = $sql->fetch_assoc()) {
                                        $id = $data['id'] ?? '';
                                        $kode = htmlspecialchars($data['kode_barang'] ?? '');
                                        $nama = htmlspecialchars($data['nama_barang'] ?? '');
                                        $jumlah = htmlspecialchars($data['jumlah'] ?? '0');
                                        $satuan = htmlspecialchars($data['satuan'] ?? '');
                                        
                                        if (!empty($nama)) {
                                            echo "<option value='$id' 
                                                    data-kode='$kode'
                                                    data-stok='$jumlah'
                                                    data-satuan='$satuan'>" . 
                                                "$nama - $kode (Stok: $jumlah)</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="stok">Stok Saat Ini</label>
                            <input type="number" id="stok" name="stok" class="form-control" readonly />
                        </div>

                        <div class="form-group">
                            <label for="kode_barang">Kode Barang</label>
                            <input type="text" id="kode_barang" name="kode_barang" class="form-control" readonly />
                        </div>

                        <div class="form-group">
                            <label for="satuan">Satuan</label>
                            <input type="text" id="satuan" name="satuan" class="form-control" readonly />
                        </div>

                        <label for="jumlah">Jumlah Keluar</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="number" name="jumlahkeluar" class="form-control" style="max-width: 70px;"
                                    inputmode="numeric" min="1" step="1" id="jumlahkeluar"
                                    onchange="updateTotalStok()" required />
                            </div>
                        </div>

                        <label for="jumlah">Sisa Stok</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input readonly="readonly" name="jumlah" id="jumlah" type="number" class="form-control" />
                            </div>
                        </div>

                        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
                    </form>

                    <?php
                    if (isset($_POST['simpan'])) {
                        try {
                            $koneksi->begin_transaction();

                            $tanggal = $_POST['tanggal_keluar'];
                            $barang_id = (int)$_POST['barang'];
                            $jumlah_keluar = (int)$_POST['jumlahkeluar'];
                            $satuan = $_POST['satuan'];

                            // Ambil data barang
                            $get_barang = $koneksi->query("SELECT kode_barang, nama_barang, jumlah 
                                                        FROM gudang 
                                                        WHERE id = $barang_id");
                            
                            if ($get_barang && $get_barang->num_rows > 0) {
                                $barang_data = $get_barang->fetch_assoc();
                                $stok_sekarang = (int)$barang_data['jumlah'];

                                // Cek stok mencukupi
                                if ($stok_sekarang >= $jumlah_keluar) {
                                    // Update stok di gudang (kurangi)
                                    $stok_baru = $stok_sekarang - $jumlah_keluar;
                                    $sql_update = "UPDATE gudang SET jumlah = ? WHERE id = ?";
                                    $stmt_update = $koneksi->prepare($sql_update);
                                    $stmt_update->bind_param("ii", $stok_baru, $barang_id);
                                    $stmt_update->execute();

                                    // Insert ke barang_keluar
                                    $sql_insert = "INSERT INTO barang_keluar 
                                                (tanggal, kode_barang, nama_barang, jumlah, satuan) 
                                                VALUES (?, ?, ?, ?, ?)";
                                    
                                    $stmt_insert = $koneksi->prepare($sql_insert);
                                    $stmt_insert->bind_param("sssis", 
                                        $tanggal, 
                                        $barang_data['kode_barang'],
                                        $barang_data['nama_barang'],
                                        $jumlah_keluar,
                                        $satuan
                                    );
                                    $stmt_insert->execute();

                                    $koneksi->commit();
                                    echo "<script>
                                        alert('Data berhasil disimpan!');
                                        window.location.href='?page=barangkeluar';
                                    </script>";
                                } else {
                                    throw new Exception("Stok tidak mencukupi!");
                                }
                            } else {
                                throw new Exception("Barang tidak ditemukan!");
                            }
                        } catch (Exception $e) {
                            $koneksi->rollback();
                            echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#cmb_barang').select2({
        placeholder: "-- Pilih Barang --",
        allowClear: true,
        width: '100%',
        minimumInputLength: 2
    });
    
    $('#cmb_barang').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var kode_barang = selectedOption.data('kode');
        var stok = selectedOption.data('stok');
        var satuan = selectedOption.data('satuan');
        
        $('#kode_barang').val(kode_barang);
        $('#stok').val(stok);
        $('#satuan').val(satuan);
        updateTotalStok();
    });
});

function updateTotalStok() {
    var stok = parseInt($('#stok').val()) || 0;
    var jumlahkeluar = parseInt($('#jumlahkeluar').val()) || 0;
    var sisaStok = stok - jumlahkeluar;
    
    // Cek jika stok minus
    if (sisaStok < 0) {
        alert('Jumlah keluar tidak boleh melebihi stok yang ada!');
        $('#jumlahkeluar').val('');
        sisaStok = stok;
    }
    
    $('#jumlah').val(sisaStok);
}
</script>