<?php
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

// Ambil data aset yang akan dikembalikan
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
    WHERE aset.id = '$id'
");

if (!$sql) {
    die("Error in query: " . $koneksi->error);
}

$data = $sql->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal_return = $_POST['tanggal_return'];
    $keterangan = $_POST['keterangan'];
    $jumlah_return = 1;

    // Mulai transaksi
    $koneksi->begin_transaction();

    try {
        // Update status aset menjadi Tidak Aktif
        $sql_update = "UPDATE aset SET 
                      status = 'Tidak Aktif',
                      tanggal_keluar = ?,
                      keterangan_keluar = ?
                      WHERE id = ?";
        
        $stmt = $koneksi->prepare($sql_update);
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $koneksi->error);
        }
        
        $stmt->bind_param("ssi", $tanggal_return, $keterangan, $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error executing update: " . $stmt->error);
        }

        // Update stok di gudang (menambah 1)
        $sql_update_gudang = "UPDATE gudang SET jumlah = jumlah + 1 WHERE id = ?";
        $stmt_gudang = $koneksi->prepare($sql_update_gudang);
        
        if ($stmt_gudang === false) {
            throw new Exception("Error preparing gudang statement: " . $koneksi->error);
        }
        
        $stmt_gudang->bind_param("i", $data['gudang_id']);
        
        if (!$stmt_gudang->execute()) {
            throw new Exception("Error updating gudang: " . $stmt_gudang->error);
        }

        // Commit transaksi
        $koneksi->commit();

        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Aset berhasil dikembalikan dan stok gudang diperbarui',
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
            <h6 class="m-0 font-weight-bold text-primary">Form Pengembalian Aset</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <!-- Form fields untuk data aset yang readonly -->
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
                        <label class="form-label">Departemen</label>
                        <input type="text" class="form-control" value="<?php echo $data['nama_departemen']; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Karyawan</label>
                        <input type="text" class="form-control" value="<?php echo $data['nama_karyawan']; ?>" readonly>
                    </div>
                </div>


                 <!-- Hidden input untuk jumlah return -->
                 <input type="hidden" name="jumlah_return" value="1">




                <!-- Form fields untuk pengembalian -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="tanggal_return" class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" name="tanggal_return" required>
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3"></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-warning custom-btn">
                        <i class="fas fa-undo me-1"></i> Kembalikan
                    </button>
                    <a href="?page=aset" class="btn btn-secondary custom-btn">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>