<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Barang Keluar</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="kode_barang" class="form-label">Pilih Barang</label>
                        <select class="form-control select2" name="kode_barang" id="kode_barang" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php
                            $sql = $koneksi->query("SELECT id, kode_barang, nama_barang, jenis_barang, satuan, jumlah FROM gudang ORDER BY kode_barang");
                            while ($data = $sql->fetch_assoc()) {
                                echo "<option value='" . $data['id'] . "' 
                                    data-stok='" . $data['jumlah'] . "'
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
                        <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
                        <input type="date" class="form-control" name="tanggal_keluar" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Jenis Barang</label>
                        <input type="text" class="form-control" id="jenis_barang" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" class="form-control" id="nama_barang" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stok Tersedia</label>
                        <input type="text" class="form-control" id="stok_tersedia" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Satuan</label>
                        <input type="text" class="form-control" id="satuan" readonly>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jumlah_keluar" class="form-label">Jumlah Keluar</label>
                        <input type="number" class="form-control" name="jumlah_keluar" id="jumlah_keluar" required>
                        <div class="invalid-feedback" id="stok-warning" style="display: none;">
                            Jumlah keluar tidak boleh melebihi stok tersedia!
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="keterangan" rows="3"></textarea>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary custom-btn" id="submitBtn">
                        <i class="fas fa-save me-1"></i> Simpan
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

// Proses simpan data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_barang = $_POST['kode_barang'];
    $tanggal_keluar = $_POST['tanggal_keluar'];
    $jumlah_keluar = $_POST['jumlah_keluar'];
    $keterangan = $_POST['keterangan'];

    // Mulai transaksi
    $koneksi->begin_transaction();

    try {
        // Cek stok tersedia
        $sql_cek = "SELECT jumlah FROM gudang WHERE id = ?";
        $stmt_cek = $koneksi->prepare($sql_cek);
        $stmt_cek->bind_param("i", $id_barang);
        $stmt_cek->execute();
        $result = $stmt_cek->get_result();
        $row = $result->fetch_assoc();
        
        if ($jumlah_keluar > $row['jumlah']) {
            throw new Exception("Jumlah keluar melebihi stok tersedia!");
        }

        // 1. Insert ke tabel barang_keluar
        $sql_insert = "INSERT INTO barang_keluar (tanggal_keluar, id_barang, jumlah_keluar, keterangan) 
                      VALUES (?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql_insert);
        $stmt->bind_param("siis", $tanggal_keluar, $id_barang, $jumlah_keluar, $keterangan);
        
        if (!$stmt->execute()) {
            throw new Exception("Error executing insert statement: " . $stmt->error);
        }

        // 2. Update stok di tabel gudang
        $sql_update = "UPDATE gudang SET jumlah = jumlah - ? WHERE id = ?";
        $stmt_update = $koneksi->prepare($sql_update);
        $stmt_update->bind_param("ii", $jumlah_keluar, $id_barang);
        
        if (!$stmt_update->execute()) {
            throw new Exception("Error executing update statement: " . $stmt_update->error);
        }

        // Commit transaksi
        $koneksi->commit();
        
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data barang keluar berhasil disimpan',
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
        $('#stok_tersedia').val(selectedOption.data('stok'));
    });

    // Validasi jumlah keluar tidak melebihi stok
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

<style>
.select2-container--bootstrap4 .select2-selection--single {
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
}

.invalid-feedback {
    color: #dc3545;
    font-size: 80%;
}
</style>