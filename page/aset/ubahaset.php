<?php
// Koneksi ke database
include('koneksibarang.php');

// Ambil ID atau kode aset dari URL
$id_aset = isset($_GET['id']) ? $_GET['id'] : '';

// Jika tidak ada ID yang dikirim, kembali ke halaman aset
if (!$id_aset) {
    echo "<script>alert('ID aset tidak ditemukan!'); window.location.href='?page=aset';</script>";
    exit;
}

// Ambil data aset berdasarkan ID
$sql = $koneksi->query("SELECT * FROM aset WHERE id = '$id_aset'");
$aset = $sql->fetch_assoc();

if (!$aset) {
    echo "<script>alert('Data aset tidak ditemukan!'); window.location.href='?page=aset';</script>";
    exit;
}

// Mengecek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_aset = isset($_POST['kode_aset']) ? $_POST['kode_aset'] : '';
    $nomor_urut = isset($_POST['nomor_urut']) ? $_POST['nomor_urut'] : '';

    if ($kode_aset && $nomor_urut) {
        $nomor_urut = str_pad($nomor_urut, 4, '0', STR_PAD_LEFT);
        $sql_jenis_barang = $koneksi->query("SELECT jenis_barang FROM jenis_barang WHERE code_barang = '$kode_aset'");
        $data_jenis_barang = $sql_jenis_barang->fetch_assoc();
        $jenis_barang = $data_jenis_barang['jenis_barang'];
        $kode_lengkap = $kode_aset . '/' . $jenis_barang . '/' . $nomor_urut;
    } else {
        $kode_lengkap = '';
    }

    $nama_aset = $_POST['nama_aset'];
    $departemen_id = $_POST['departemen_id'];
    $lokasi = $_POST['lokasi'];
    $status = $_POST['status'];
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $karyawan_id = $_POST['karyawan_id'];
    $kondisi = $_POST['kondisi'];

    // Validasi karyawan_id
    $karyawan_check = $koneksi->query("SELECT id FROM daftar_karyawan WHERE id = '$karyawan_id'");
    if ($karyawan_check->num_rows == 0) {
        echo "<script>alert('Karyawan dengan ID tersebut tidak ditemukan!'); window.location.href = '?page=ubah-aset&id=$id_aset';</script>";
        exit;
    }

    // Mengecek apakah kode aset sudah ada (kecuali untuk aset ini sendiri)
    $cek_kode_aset = $koneksi->query("SELECT * FROM aset WHERE kode_lengkap = '$kode_lengkap' AND id != '$id_aset'");
    if ($cek_kode_aset->num_rows > 0) {
        echo "<script>alert('Kode aset sudah ada, harap pilih nomor urut yang berbeda.'); window.location.href = '?page=ubah-aset&id=$id_aset';</script>";
        exit;
    }

    // Query untuk memperbarui data ke database
    $sql = "UPDATE aset SET kode_aset='$kode_aset', kode_lengkap='$kode_lengkap', nama_aset='$nama_aset', departemen_id='$departemen_id', lokasi='$lokasi', status='$status', tanggal_pembelian='$tanggal_pembelian', karyawan_id='$karyawan_id', kondisi='$kondisi' WHERE id='$id_aset'";

    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Data aset berhasil diubah!'); window.location.href='?page=aset';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}
?>

<!-- Halaman Form Ubah Aset -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Aset</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="kode_aset" class="form-label">Kode Aset</label>
                    <select class="form-control" id="kode_aset" name="kode_aset" required>
                        <option value="">Pilih Kode Aset</option>
                        <?php
                        $sql = $koneksi->query("SELECT * FROM jenis_barang");
                        while ($data = $sql->fetch_assoc()) {
                            $selected = ($aset['kode_aset'] == $data['code_barang']) ? 'selected' : '';
                            echo "<option value='" . $data['code_barang'] . "' $selected>" . $data['code_barang'] . " - " . $data['jenis_barang'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div id="nomor_urut_container" class="mb-3">
                    <label for="nomor_urut" class="form-label">Nomor Urut Kode Aset</label>
                    <input type="number" class="form-control" id="nomor_urut" name="nomor_urut" value="<?php echo substr($aset['kode_lengkap'], -4); ?>" min="1" required>
                </div>

                <div class="mb-3">
                    <label for="kode_lengkap" class="form-label">Kode Aset Lengkap</label>
                    <input type="text" class="form-control" id="kode_lengkap" name="kode_lengkap" value="<?php echo htmlspecialchars($aset['kode_lengkap']); ?>" readonly>
                </div>

                <div class="mb-3">
                    <label for="nama_aset" class="form-label">Nama Aset</label>
                    <input type="text" class="form-control" id="nama_aset" name="nama_aset" value="<?php echo htmlspecialchars($aset['nama_aset']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="departemen_id" class="form-label">Departemen</label>
                    <select class="form-control" id="departemen_id" name="departemen_id" required>
                        <option value="">Pilih Departemen</option>
                        <?php
                        $sql = $koneksi->query("SELECT * FROM departemen ORDER BY nama");
                        while ($data = $sql->fetch_assoc()) {
                            $selected = ($aset['departemen_id'] == $data['id']) ? 'selected' : '';
                            echo "<option value='" . $data['id'] . "' $selected>" . htmlspecialchars($data['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="karyawan_id" class="form-label">Karyawan</label>
                    <select class="form-control" id="karyawan_id" name="karyawan_id" required>
                        <option value="">Pilih Karyawan</option>
                        <?php
                        $sql = $koneksi->query("SELECT * FROM daftar_karyawan");
                        while ($data = $sql->fetch_assoc()) {
                            $selected = ($aset['karyawan_id'] == $data['id']) ? 'selected' : '';
                            echo "<option value='" . $data['id'] . "' $selected>" . htmlspecialchars($data['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?php echo htmlspecialchars($aset['lokasi']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="Aktif" <?php echo ($aset['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Tidak Aktif" <?php echo ($aset['status'] == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                    <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" value="<?php echo $aset['tanggal_pembelian']; ?>" required>
                </div>

                <div class="mb-3">
                    <label for="kondisi" class="form-label">Kondisi</label>
                    <select id="kondisi" name="kondisi" class="form-control" required>
                        <option value="Baik" <?php echo ($aset['kondisi'] == 'Baik') ? 'selected' : ''; ?>>Baik</option>
                        <option value="Rusak" <?php echo ($aset['kondisi'] == 'Rusak') ? 'selected' : ''; ?>>Rusak</option>
                    </select>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="?page=aset" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
