<?php
// Menghubungkan ke database
include('koneksibarang.php');

// Ambil ID karyawan yang akan diubah dari URL
$id = $_GET['id'];

// Query untuk mengambil data karyawan berdasarkan ID
$sql = $koneksi->query("SELECT * FROM daftar_karyawan WHERE id = '$id'");
$data = $sql->fetch_assoc();

// Cek apakah data karyawan ditemukan
if (!$data) {
    echo "<script>alert('Data karyawan tidak ditemukan!'); window.location='?page=daftarkaryawan';</script>";
}

// Menyimpan perubahan jika tombol submit ditekan
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nama_karyawan = $_POST['nama_karyawan'];
    $departemen_id = $_POST['departemen_id'];

    // Query untuk mengupdate data karyawan
    $update_sql = $koneksi->query("UPDATE daftar_karyawan SET nama = '$nama_karyawan', departemen_id = '$departemen_id' WHERE id = '$id'");

    // Cek jika berhasil
    if ($update_sql) {
        echo "<script>alert('Data berhasil diubah!'); window.location='?page=daftarkaryawan';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data!');</script>";
    }
}

// Query untuk mengambil data departemen
$departemen_sql = $koneksi->query("SELECT * FROM departemen");

?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Data Karyawan</h6>
        </div>
        <div class="card-body">
            <!-- Form untuk mengubah data karyawan -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama_karyawan">Nama Karyawan</label>
                    <input type="text" id="nama_karyawan" name="nama_karyawan" class="form-control"
                        value="<?php echo htmlspecialchars($data['nama']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="departemen_id">Departemen</label>
                    <select name="departemen_id" id="departemen_id" class="form-control" required>
                        <option value="">Pilih Departemen</option>
                        <?php
                        while ($departemen = $departemen_sql->fetch_assoc()) {
                            // Tandai departemen yang sudah dipilih
                            $selected = ($data['departemen_id'] == $departemen['id']) ? "selected" : "";
                            echo "<option value='" . $departemen['id'] . "' $selected>" . htmlspecialchars($departemen['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="?page=daftarkaryawan" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Page Content -->