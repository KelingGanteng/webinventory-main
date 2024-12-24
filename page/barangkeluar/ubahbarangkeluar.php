<?php
// Ambil data barang keluar yang akan diubah
$id = $_GET['id'];
$sql = $koneksi->query("
    SELECT bk.*, g.kode_barang, g.nama_barang, g.jenis_barang, g.satuan, g.jumlah as stok_sekarang 
    FROM barang_keluar bk
    LEFT JOIN gudang g ON bk.id_barang = g.id
    WHERE bk.id_barang_keluar = $id
");
$data = $sql->fetch_assoc();
$stok_awal = $data['stok_sekarang'] + $data['jumlah_keluar']; // Menghitung stok awal
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Barang Keluar</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <input type="hidden" name="id_barang_keluar" value="<?php echo $id; ?>">
                <input type="hidden" name="jumlah_keluar_lama" value="<?php echo $data['jumlah_keluar']; ?>">
                <input type="hidden" name="id_barang_lama" value="<?php echo $data['id_barang']; ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kode_barang" class="form-label">Pilih Barang</label>
                        <select class="form-control select2" name="kode_barang" id="kode_barang" required>
                            <?php
                            $sql_barang = $koneksi->query("SELECT id, kode_barang, nama_barang, jenis_barang, satuan, jumlah FROM gudang ORDER BY kode_barang");
                            while ($barang = $sql_barang->fetch_assoc()) {
                                $selected = ($barang['id'] == $data['id_barang']) ? 'selected' : '';
                                echo "<option value='" . $barang['id'] . "' 
                                    data-stok='" . $barang['jumlah'] . "'
                                    data-jenis='" . $barang['jenis_barang'] . "'
                                    data-nama='" . $barang['nama_barang'] . "'
                                    data-satuan='" . $barang['satuan'] . "' 
                                    $selected>" . 
                                    $barang['kode_barang'] . " - " . $barang['nama_barang'] . 
                                    "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                        <input type="date" class="form-control" name="tanggal_keluar" 
                               value="<?php echo $data['tanggal_keluar']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Barang</label>
                        <input type="text" class="form-control" id="jenis_barang" 
                               value="<?php echo $data['jenis_barang']; ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" 
                               value="<?php echo $data['nama_barang']; ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stok Tersedia</label>
                        <input type="text" class="form-control" id="stok_tersedia" 
                               value="<?php echo $stok_awal; ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="satuan" 
                               value="<?php echo $data['satuan']; ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                        <input type="number" class="form-control" name="jumlah_keluar" id="jumlah_keluar" 
                               value="<?php echo $data['jumlah_keluar']; ?>" required>
                        <div class="invalid-feedback" id="stok-warning" style="display: none;">
                            Jumlah keluar tidak boleh melebihi stok tersedia!
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3"><?php echo $data['keterangan']; ?></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary custom-btn" id="submitBtn">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="?page=barangkeluar" class="btn btn-secondary custom-btn">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

// Proses update data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_barang_keluar = $_POST['id_barang_keluar'];
    $id_barang = $_POST['kode_barang'];
    $tanggal_keluar = $_POST['tanggal_keluar'];
    $jumlah_keluar = $_POST['jumlah_keluar'];
    $jumlah_keluar_lama = $_POST['jumlah_keluar_lama'];
    $id_barang_lama = $_POST['id_barang_lama'];
    $keterangan = $_POST['keterangan'];

    // Mulai transaksi
    $koneksi->begin_transaction();

    try {
        // Update barang_keluar
        $sql_update = "UPDATE barang_keluar SET 
                      tanggal_keluar = ?, 
                      id_barang = ?, 
                      jumlah_keluar = ?, 
                      keterangan = ? 
                      WHERE id_barang_keluar = ?";
        $stmt = $koneksi->prepare($sql_update);
        $stmt->bind_param("siisi", $tanggal_keluar, $id_barang, $jumlah_keluar, $keterangan, $id_barang_keluar);
        
        if (!$stmt->execute()) {
            throw new Exception("Error updating barang_keluar: " . $stmt->error);
        }

        // Kembalikan stok lama
        $sql_restore = "UPDATE gudang SET jumlah = jumlah + ? WHERE id = ?";
        $stmt_restore = $koneksi->prepare($sql_restore);
        $stmt_restore->bind_param("ii", $jumlah_keluar_lama, $id_barang_lama);
        
        if (!$stmt_restore->execute()) {
            throw new Exception("Error restoring stock: " . $stmt_restore->error);
        }

        // Update stok baru
        $sql_update_stok = "UPDATE gudang SET jumlah = jumlah - ? WHERE id = ?";
        $stmt_update_stok = $koneksi->prepare($sql_update_stok);
        $stmt_update_stok->bind_param("ii", $jumlah_keluar, $id_barang);
        
        if (!$stmt_update_stok->execute()) {
            throw new Exception("Error updating stock: " . $stmt_update_stok->error);
        }

        // Commit transaksi
        $koneksi->commit();
        
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data barang keluar berhasil diubah',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = '?page=barangkeluar';
                });
              </script>";
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $koneksi->rollback();
        
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan: " . addslashes($e->getMessage()) . "',
                    showConfirmButton: true
                });
              </script>";
    }
}
?>

<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    $('#kode_barang').change(function() {
        var selectedOption = $(this).find('option:selected');
        $('#jenis_barang').val(selectedOption.data('jenis'));
        $('#nama_barang').val(selectedOption.data('nama'));
        $('#satuan').val(selectedOption.data('satuan'));
        $('#stok_tersedia').val(selectedOption.data('stok'));
    });

    $('#jumlah_keluar').on('input', function() {
        var stokTersedia = parseInt($('#stok_tersedia').val()) || 0;
        var jumlahKeluar = parseInt($(this).val()) || 0;
        
        if (jumlahKeluar > stokTersedia) {
            $('#stok-warning').show();
            $('#submitBtn').prop('disabled', true);
        } else {
            $('#stok-warning').hide();
            $('#submitBtn').prop('disabled', false);
        }
    });
});
</script>