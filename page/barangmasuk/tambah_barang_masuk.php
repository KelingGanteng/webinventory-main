<?php
// Proses simpan data
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_barang = $_POST['kode_barang']; // karena di form name-nya masih kode_barang
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jumlah_masuk = $_POST['jumlah_masuk'];
    $keterangan = $_POST['keterangan'];

    // Mulai transaksi
    $koneksi->begin_transaction();

    try {
        // 1. Insert ke tabel barang_masuk
        $sql_insert = "INSERT INTO barang_masuk (tanggal_masuk, id_barang, jumlah_masuk, keterangan) 
                      VALUES (?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql_insert);
        
        if ($stmt === false) {
            throw new Exception("Error preparing insert statement: " . $koneksi->error);
        }
        
        $stmt->bind_param("siis", $tanggal_masuk, $id_barang, $jumlah_masuk, $keterangan);
        
        if (!$stmt->execute()) {
            throw new Exception("Error executing insert statement: " . $stmt->error);
        }

        // 2. Update stok di tabel gudang
        $sql_update = "UPDATE gudang SET jumlah = jumlah + ? WHERE id = ?";
        $stmt_update = $koneksi->prepare($sql_update);
        
        if ($stmt_update === false) {
            throw new Exception("Error preparing update statement: " . $koneksi->error);
        }
        
        $stmt_update->bind_param("ii", $jumlah_masuk, $id_barang);
        
        if (!$stmt_update->execute()) {
            throw new Exception("Error executing update statement: " . $stmt_update->error);
        }

        // Commit transaksi
        $koneksi->commit();
        
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data barang masuk berhasil disimpan',
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

<!-- Form HTML -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Barang Masuk</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kode_barang" class="form-label">Pilih Barang</label>
                        <select class="form-control select2" name="kode_barang" id="kode_barang" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php
                            $sql = $koneksi->query("SELECT id, kode_barang, nama_barang, jenis_barang, satuan FROM gudang ORDER BY kode_barang");
                            while ($data = $sql->fetch_assoc()) {
                                echo "<option value='" . $data['id'] . "' 
                                    data-jenis='" . $data['jenis_barang'] . "'
                                    data-nama='" . $data['nama_barang'] . "'
                                    data-satuan='" . $data['satuan'] . "'>" . 
                                    $data['kode_barang'] . " - " . $data['nama_barang'] . 
                                    "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                        <input type="date" class="form-control" name="tanggal_masuk" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Jenis Barang</label>
                        <input type="text" class="form-control" id="jenis_barang" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="satuan" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jumlah_masuk" class="form-label">Jumlah Masuk</label>
                        <input type="number" class="form-control" name="jumlah_masuk" required>
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3"></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary custom-btn">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                    <a href="?page=barangmasuk" class="btn btn-secondary custom-btn">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include Select2 CSS dan JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });

    // Event handler saat barang dipilih
    $('#kode_barang').change(function() {
        var selectedOption = $(this).find('option:selected');
        $('#jenis_barang').val(selectedOption.data('jenis'));
        $('#nama_barang').val(selectedOption.data('nama'));
        $('#satuan').val(selectedOption.data('satuan'));
    });
});
</script>

<style>
.select2-container--bootstrap4 .select2-selection--single {
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
}
</style>