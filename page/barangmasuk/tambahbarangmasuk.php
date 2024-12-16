<?php
// Cek apakah form disubmit
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $aset_id = $_POST['aset_id'];
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jumlah = $_POST['jumlah'];
    $harga = $_POST['harga'];

    // Query untuk menyimpan data barang masuk
    $sql = $koneksi->query("INSERT INTO barang_masuk (aset_id, tanggal_masuk, jumlah, harga) 
                            VALUES ('$aset_id', '$tanggal_masuk', '$jumlah', '$harga')");

    // Cek apakah query berhasil
    if ($sql) {
        echo "<script>alert('Data barang masuk berhasil ditambahkan!'); window.location='?page=barangmasuk';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, data gagal ditambahkan!');</script>";
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Barang Masuk</h6>
        </div>
        <div class="card-body">
            <!-- Form untuk tambah barang masuk -->
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="aset_id" class="form-label">Aset</label>
                            <select id="aset_id" name="aset_id" class="form-control" required>
                                <option value="">Pilih Aset</option>
                                <?php
                                // Query untuk mengambil data aset
                                $query_aset = $koneksi->query("SELECT id, nama_aset FROM aset");
                                while ($data_aset = $query_aset->fetch_assoc()) {
                                    echo "<option value='{$data_aset['id']}'>{$data_aset['nama_aset']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" id="tanggal_masuk" name="tanggal_masuk" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" id="jumlah" name="jumlah" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" id="harga" name="harga" class="form-control" step="0.01" required>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <button type="submit" name="submit" class="btn btn-primary custom-btn">
                    <i class="fas fa-save me-2"></i> Simpan
                </button>
                <a href="?page=barangmasuk" class="btn btn-secondary custom-btn">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </form>
        </div>
    </div>
</div>

<!-- CSS untuk tombol -->
<style>
    /* Custom button styling */
    .custom-btn {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }

    .custom-btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
    }
</style>
