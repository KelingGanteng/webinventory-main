<?php
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

// Ambil data aset yang akan diserahkan
$id = $_GET['id'];
$sql = $koneksi->query("
    SELECT aset.*, 
           daftar_karyawan.nama AS nama_karyawan,
           gudang.nama_barang,
           gudang.id as gudang_id,
           departemen.nama AS nama_departemen
    FROM aset
    LEFT JOIN daftar_karyawan ON aset.karyawan_id = daftar_karyawan.id
    LEFT JOIN gudang ON aset.gudang_id = gudang.id
    LEFT JOIN departemen ON aset.departemen_id = departemen.id
    WHERE aset.id = $id
");
$data = $sql->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal_penyerahan = $_POST['tanggal_penyerahan'];
    $keterangan = $_POST['keterangan'];
    $jumlah = 1; // Set jumlah tetap 1

    // Mulai transaksi
    $koneksi->begin_transaction();

    try {
        // Update status aset menjadi Aktif
        $sql_update = "UPDATE aset SET 
                      status = 'Aktif',
                      tanggal_pembelian = ?,
                      keterangan_keluar = ?,
                      jumlah_keluar = ?
                      WHERE id = ?";
        
        $stmt = $koneksi->prepare($sql_update);
        if ($stmt === false) {
            throw new Exception("Error preparing aset statement: " . $koneksi->error);
        }
        
        $stmt->bind_param("ssii", $tanggal_penyerahan, $keterangan, $jumlah, $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error executing aset update: " . $stmt->error);
        }

        // Update stok di gudang
        $sql_gudang = "UPDATE gudang SET jumlah = jumlah - ? WHERE id = ?";
        $stmt_gudang = $koneksi->prepare($sql_gudang);
        if ($stmt_gudang === false) {
            throw new Exception("Error preparing gudang statement: " . $koneksi->error);
        }
        
        $stmt_gudang->bind_param("ii", $jumlah, $data['gudang_id']);
        
        if (!$stmt_gudang->execute()) {
            throw new Exception("Error updating gudang: " . $stmt_gudang->error);
        }

        // Commit transaksi jika semua berhasil
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

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Penyerahan Aset</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <!-- Hidden input untuk jumlah -->
                <input type="hidden" name="jumlah" value="1">
                
                <!-- Form fields lainnya tetap sama -->
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

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal_penyerahan" class="form-label">Tanggal Penyerahan</label>
                        <input type="date" class="form-control" name="tanggal_penyerahan" required>
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3"></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary custom-btn">
                        <i class="fas fa-share me-1"></i> Serahkan
                    </button>
                    <a href="?page=aset" class="btn btn-secondary custom-btn">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>