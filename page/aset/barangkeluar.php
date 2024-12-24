<?php
// Ambil data aset yang akan dikeluarkan
$id = $_GET['id'];
$sql = $koneksi->query("
    SELECT aset.*, 
           daftar_karyawan.nama AS nama_karyawan,
           gudang.nama_barang,
           gudang.jumlah as stok_tersedia,
           gudang.satuan,
           departemen.nama AS nama_departemen
    FROM aset
    LEFT JOIN daftar_karyawan ON aset.karyawan_id = daftar_karyawan.id
    LEFT JOIN gudang ON aset.gudang_id = gudang.id
    LEFT JOIN departemen ON aset.departemen_id = departemen.id
    WHERE aset.id = $id
");
$data = $sql->fetch_assoc();
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Pengeluaran Aset</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kode Aset</label>
                        <input type="text" class="form-control" value="<?php echo $data['kode_lengkap']; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" value="<?php echo $data['nama_barang']; ?>" readonly>
                    </div>
                </div>


 <!-- Hidden input untuk jumlah keluar -->
 <input type="hidden" name="jumlah_keluar" value="1">



                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Departemen</label>
                        <input type="text" class="form-control" value="<?php echo $data['nama_departemen']; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Karyawan</label>
                        <input type="text" class="form-control" value="<?php echo $data['nama_karyawan']; ?>" readonly>
                    </div>
                </div>
                    <div class="col-md-4">
                    
                    <div class="invalid-feedback" id="stok-warning" style="display: none;">
                    </div>
                 </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                        <input type="date" class="form-control" name="tanggal_keluar" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="keterangan_keluar" class="form-label">Keterangan Detail</label>
                        <textarea class="form-control" name="keterangan_keluar" rows="3" required></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary custom-btn" id="submitBtn">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="?page=aset" class="btn btn-secondary custom-btn">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal_keluar = $_POST['tanggal_keluar'];
    $keterangan_keluar = $_POST['keterangan_keluar'];
    $jumlah_keluar = 1; // Set jumlah keluar ke 1

    // Mulai transaksi
    $koneksi->begin_transaction();

    try {
        // Update status aset menjadi Aktif
        $sql_update_aset = "UPDATE aset SET 
                           tanggal_keluar = ?,
                           keterangan_keluar = ?,
                           jumlah_keluar = ?,
                           status = 'Aktif'
                           WHERE id = ?";
                           
        $stmt_aset = $koneksi->prepare($sql_update_aset);
        
        if ($stmt_aset === false) {
            throw new Exception("Error preparing statement: " . $koneksi->error);
        }
        
        if (!$stmt_aset->bind_param("ssis", 
            $tanggal_keluar, 
            $keterangan_keluar,
            $jumlah_keluar,
            $id
        )) {
            throw new Exception("Error binding parameters: " . $stmt_aset->error);
        }
        
        if (!$stmt_aset->execute()) {
            throw new Exception("Error executing update: " . $stmt_aset->error);
        }

        // Update stok di gudang
        $sql_update_gudang = "UPDATE gudang SET jumlah = jumlah - 1 WHERE id = ?";
        $stmt_gudang = $koneksi->prepare($sql_update_gudang);
        
        if ($stmt_gudang === false) {
            throw new Exception("Error preparing gudang statement: " . $koneksi->error);
        }
        
        if (!$stmt_gudang->bind_param("i", $data['gudang_id'])) {
            throw new Exception("Error binding gudang parameters: " . $stmt_gudang->error);
        }
        
        if (!$stmt_gudang->execute()) {
            throw new Exception("Error updating gudang: " . $stmt_gudang->error);
        }

        // Commit transaksi
        $koneksi->commit();
        
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Aset berhasil diserahkan ke karyawan',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = '?page=aset';
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