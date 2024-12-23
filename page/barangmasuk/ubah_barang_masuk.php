<?php
// Ambil data yang akan diubah
$id_barang_masuk = $_GET['id'];
$query = $koneksi->query("
    SELECT bm.*, g.kode_barang, g.nama_barang, g.jenis_barang, g.satuan 
    FROM barang_masuk bm
    LEFT JOIN gudang g ON bm.id_barang = g.id
    WHERE bm.id_barang_masuk = $id_barang_masuk
");
$data = $query->fetch_assoc();

// Proses ubah data
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_barang = $_POST['kode_barang'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jumlah_masuk_baru = $_POST['jumlah_masuk'];
    $jumlah_masuk_lama = $data['jumlah_masuk'];
    $keterangan = $_POST['keterangan'];

    // Mulai transaksi
    $koneksi->begin_transaction();

    try {
        // 1. Update tabel barang_masuk
        $sql_update_bm = "UPDATE barang_masuk SET 
                         tanggal_masuk = ?,
                         id_barang = ?,
                         jumlah_masuk = ?,
                         keterangan = ?
                         WHERE id_barang_masuk = ?";
        $stmt = $koneksi->prepare($sql_update_bm);
        
        if ($stmt === false) {
            throw new Exception("Error preparing update statement: " . $koneksi->error);
        }
        
        $stmt->bind_param("siisi", $tanggal_masuk, $id_barang, $jumlah_masuk_baru, $keterangan, $id_barang_masuk);
        
        if (!$stmt->execute()) {
            throw new Exception("Error executing update statement: " . $stmt->error);
        }

        // 2. Update stok di gudang
        // Kurangi stok lama
        $sql_update_stok1 = "UPDATE gudang SET jumlah = jumlah - ? WHERE id = ?";
        $stmt_stok1 = $koneksi->prepare($sql_update_stok1);
        $stmt_stok1->bind_param("ii", $jumlah_masuk_lama, $data['id_barang']);
        $stmt_stok1->execute();

        // Tambah stok baru
        $sql_update_stok2 = "UPDATE gudang SET jumlah = jumlah + ? WHERE id = ?";
        $stmt_stok2 = $koneksi->prepare($sql_update_stok2);
        $stmt_stok2->bind_param("ii", $jumlah_masuk_baru, $id_barang);
        $stmt_stok2->execute();

        // Commit transaksi
        $koneksi->commit();
        
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data barang masuk berhasil diubah',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = '?page=barangmasuk';
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
            <h6 class="m-0 font-weight-bold text-primary">Ubah Barang Masuk</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kode_barang" class="form-label">Pilih Barang</label>
                        <select class="form-control select2" name="kode_barang" id="kode_barang" required>
                            <?php
                            $sql = $koneksi->query("SELECT id, kode_barang, nama_barang, jenis_barang, satuan FROM gudang ORDER BY kode_barang");
                            while ($row = $sql->fetch_assoc()) {
                                $selected = ($row['id'] == $data['id_barang']) ? 'selected' : '';
                                echo "<option value='" . $row['id'] . "' 
                                    data-jenis='" . $row['jenis_barang'] . "'
                                    data-nama='" . $row['nama_barang'] . "'
                                    data-satuan='" . $row['satuan'] . "' 
                                    $selected>" . 
                                    $row['kode_barang'] . " - " . $row['nama_barang'] . 
                                    "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" name="tanggal_masuk" value="<?php echo $data['tanggal_masuk']; ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Barang</label>
                        <input type="text" class="form-control" id="jenis_barang" value="<?php echo $data['jenis_barang']; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" value="<?php echo $data['nama_barang']; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="satuan" value="<?php echo $data['satuan']; ?>" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jumlah_masuk" class="form-label">Jumlah Masuk</label>
                        <input type="number" class="form-control" name="jumlah_masuk" value="<?php echo $data['jumlah_masuk']; ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3"><?php echo $data['keterangan']; ?></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary custom-btn">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                    <a href="?page=barangmasuk" class="btn btn-secondary custom-btn">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>