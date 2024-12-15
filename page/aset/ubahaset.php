<?php
// Koneksi ke database
include('koneksibarang.php');

// Cek apakah ada parameter 'id' di URL
if (isset($_GET['id'])) {
    $id_aset = $_GET['id'];

    // Query untuk mengambil data aset berdasarkan ID
    $sql = $koneksi->query("SELECT aset.*, daftar_karyawan.nama AS nama_karyawan, departemen.nama AS nama_departemen 
                            FROM aset
                            LEFT JOIN daftar_karyawan ON aset.karyawan_id = daftar_karyawan.id
                            LEFT JOIN departemen ON aset.departemen_id = departemen.id
                            WHERE aset.id = '$id_aset'");
    if ($sql->num_rows > 0) {
        $data = $sql->fetch_assoc();
    } else {
        // Jika aset tidak ditemukan
        echo "<script>alert('Aset tidak ditemukan!'); window.location.href='?page=aset';</script>";
        exit;
    }
} else {
    // Jika ID tidak ditemukan
    echo "<script>alert('ID aset tidak ditemukan!'); window.location.href='?page=aset';</script>";
    exit;
}

// Proses pembaruan data aset
if (isset($_POST['submit'])) {
    $kode_aset = $_POST['kode_aset'];
    $nama_aset = $_POST['nama_aset'];
    $departemen_id = $_POST['departemen_id'];
    $lokasi = $_POST['lokasi'];
    $status = $_POST['status'];
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $karyawan_id = $_POST['karyawan_id'];
    $kondisi = $_POST['kondisi'];

    // Query untuk memperbarui data aset
    $sql_update = $koneksi->query("UPDATE aset SET 
        kode_aset = '$kode_aset', 
        nama_aset = '$nama_aset', 
        departemen_id = '$departemen_id', 
        lokasi = '$lokasi', 
        status = '$status', 
        tanggal_pembelian = '$tanggal_pembelian', 
        karyawan_id = '$karyawan_id', 
        kondisi = '$kondisi' 
        WHERE id = '$id_aset'");

    if ($sql_update) {
        echo "<script>alert('Data aset berhasil diperbarui!'); window.location.href='?page=aset';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat memperbarui data!'); window.history.back();</script>";
    }
}
?>

<!-- Formulir Ubah Aset -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Data Aset</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <!-- Kode Aset -->
                <div class="mb-3">
                    <label for="kode_aset" class="form-label">Kode Aset</label>
                    <input type="text" class="form-control" id="kode_aset" name="kode_aset"
                        value="<?php echo $data['kode_aset']; ?>" required>
                </div>

                <!-- Nama Aset -->
                <div class="mb-3">
                    <label for="nama_aset" class="form-label">Nama Aset</label>
                    <input type="text" class="form-control" id="nama_aset" name="nama_aset"
                        value="<?php echo $data['nama_aset']; ?>" required>
                </div>

                <!-- Departemen -->
                <div class="mb-3">
                    <label for="departemen_id" class="form-label">Departemen</label>
                    <select class="form-control" id="departemen_id" name="departemen_id" required>
                        <?php
                        // Mengambil daftar departemen
                        $sql_departemen = $koneksi->query("SELECT * FROM departemen");
                        while ($departemen = $sql_departemen->fetch_assoc()) {
                            $selected = ($departemen['id'] == $data['departemen_id']) ? 'selected' : '';
                            echo "<option value='{$departemen['id']}' {$selected}>{$departemen['nama']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Karyawan -->
                <div class="mb-3">
                    <label for="karyawan_id" class="form-label">Karyawan</label>
                    <select class="form-control" id="karyawan_id" name="karyawan_id" required>
                        <?php
                        // Mengambil daftar karyawan
                        $sql_karyawan = $koneksi->query("SELECT * FROM daftar_karyawan");
                        while ($karyawan = $sql_karyawan->fetch_assoc()) {
                            $selected = ($karyawan['id'] == $data['karyawan_id']) ? 'selected' : '';
                            echo "<option value='{$karyawan['id']}' {$selected}>{$karyawan['nama']}</option>";
                        }
                        ?>
                    </select>
                </div>


                <!-- Lokasi -->
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi"
                        value="<?php echo $data['lokasi']; ?>" required>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Aktif" <?php echo ($data['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Tidak Aktif" <?php echo ($data['status'] == 'Tidak Aktif') ? 'selected' : ''; ?>>
                            Tidak Aktif</option>
                    </select>
                </div>

                <!-- Tanggal Pembelian -->
                <div class="mb-3">
                    <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                    <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian"
                        value="<?php echo $data['tanggal_pembelian']; ?>" required>
                </div>


                <!-- Kondisi -->
                <div class="mb-3">
                    <label for="kondisi" class="form-label">Kondisi</label>
                    <input type="text" class="form-control" id="kondisi" name="kondisi"
                        value="<?php echo $data['kondisi']; ?>" required>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" name="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="?page=aset" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<!-- CSS Custom untuk tombol dan layout -->
<style>
    .form-control {
        border-radius: 0.375rem;
        border: 1px solid #ced4da;
        box-shadow: none;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
    }
</style>