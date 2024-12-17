<?php
// Koneksi ke database
include('koneksibarang.php');

// Mengecek apakah ID aset sudah disertakan dalam URL
if (isset($_GET['id'])) {
    $id_aset = $_GET['id'];

    // Ambil data aset berdasarkan ID
    $sql = $koneksi->query("SELECT * FROM aset WHERE id = '$id_aset'");
    if ($sql->num_rows > 0) {
        $data_aset = $sql->fetch_assoc();
    } else {
        echo "<script>alert('Aset tidak ditemukan!'); window.location.href = '?page=aset';</script>";
        exit;
    }
}

// Mengecek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $kode_aset = $_POST['kode_aset'];
    $nomor_urut = $_POST['nomor_urut'];
    $departemen_id = $_POST['departemen_id'];
    $gudang_id = $_POST['gudang_id'];
    $status = $_POST['status'];
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $karyawan_id = $_POST['karyawan_id'];

    // Format nomor urut dengan padding 4 digit
    $nomor_urut = str_pad($nomor_urut, 4, '0', STR_PAD_LEFT);

    // Ambil nama jenis barang untuk digunakan dalam kode lengkap
    $sql_jenis_barang = $koneksi->query("SELECT jenis_barang FROM jenis_barang WHERE code_barang = '$kode_aset'");
    $data_jenis_barang = $sql_jenis_barang->fetch_assoc();
    $jenis_barang = $data_jenis_barang['jenis_barang'];

    // Gabungkan kode aset, jenis barang, dan nomor urut untuk membentuk kode lengkap
    $kode_lengkap = $kode_aset . '/' . $jenis_barang . '/' . $nomor_urut;

    // Query untuk memperbarui data aset
    $sql_update = "UPDATE aset SET
        kode_aset = '$kode_aset',
        kode_lengkap = '$kode_lengkap',
        departemen_id = '$departemen_id',
        status = '$status',
        tanggal_pembelian = '$tanggal_pembelian',
        karyawan_id = '$karyawan_id',
        gudang_id = '$gudang_id'
        WHERE id = '$id_aset'";

    if ($koneksi->query($sql_update) === TRUE) {
        echo "<script>alert('Data aset berhasil diubah!'); window.location.href='?page=aset';</script>";
    } else {
        echo "Error: " . $sql_update . "<br>" . $koneksi->error;
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
                <!-- Input untuk Kode Aset -->
                <div class="mb-3">
                    <label for="kode_aset" class="form-label">Kode Aset</label>
                    <select class="form-control" id="kode_aset" name="kode_aset" required>
                        <option value="">Pilih Kode Aset</option>
                        <?php
                        // Ambil data Kode Aset dari tabel jenis_barang
                        $sql = $koneksi->query("SELECT * FROM jenis_barang");
                        while ($data = $sql->fetch_assoc()) {
                            $selected = ($data['code_barang'] == $data_aset['kode_aset']) ? 'selected' : '';
                            echo "<option value='" . $data['code_barang'] . "' $selected>" . $data['code_barang'] . " - " . $data['jenis_barang'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Tempat untuk menampilkan input Nomor Urut -->
                <div id="nomor_urut_container" class="mb-3" style="display:block;">
                    <label for="nomor_urut" class="form-label">Nomor Urut Kode Aset</label>
                    <input type="number" class="form-control" id="nomor_urut" name="nomor_urut" 
                           placeholder="Masukkan Nomor Urut" min="1" required value="<?php echo substr($data_aset['kode_lengkap'], -4); ?>">
                </div>

                <!-- Tempat untuk menampilkan Kode Aset Lengkap -->
                <div class="mb-3">
                    <label for="kode_lengkap" class="form-label">Kode Aset Lengkap</label>
                    <input type="text" class="form-control" id="kode_lengkap" name="kode_lengkap" readonly
                           value="<?php echo $data_aset['kode_lengkap']; ?>">
                </div>

                <script>
                    $(document).ready(function() {
                        // Menangani perubahan pada input nomor urut
                        document.getElementById('nomor_urut').addEventListener('input', function() {
                            var kodeAset = document.getElementById('kode_aset').value;
                            var nomorUrut = this.value;
                            var kodeLengkap = document.getElementById('kode_lengkap');

                            if (kodeAset && nomorUrut) {
                                // Format nomor urut menjadi 4 digit (misalnya 0001, 0002, ...)
                                var nomorUrutFormatted = nomorUrut.padStart(4, '0');
                                kodeLengkap.value = kodeAset + "/" + nomorUrutFormatted;
                            }
                        });
                    });
                </script>

                <!-- Input untuk Departemen -->
                <div class="mb-3">
                    <label for="departemen_id" class="form-label">Departemen</label>
                    <select class="form-control select2" id="departemen_id" name="departemen_id" required>
                        <option value="">Pilih Departemen</option>
                        <?php
                        $sql = $koneksi->query("SELECT * FROM departemen ORDER BY nama");
                        while ($data = $sql->fetch_assoc()) {
                            $selected = ($data['id'] == $data_aset['departemen_id']) ? 'selected' : '';
                            echo "<option value='" . $data['id'] . "' $selected>" . htmlspecialchars($data['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Input untuk Nama Barang -->
                <div class="mb-3">
                    <label for="gudang_id" class="form-label">Nama Barang</label>
                    <select class="form-control select2" id="gudang_id" name="gudang_id" required>
                        <option value="">Pilih Nama Barang</option>
                        <?php
                        $sql = $koneksi->query("SELECT * FROM gudang ORDER BY nama_barang");
                        while ($data = $sql->fetch_assoc()) {
                            $selected = ($data['id'] == $data_aset['gudang_id']) ? 'selected' : '';
                            echo "<option value='" . $data['id'] . "' $selected>" . htmlspecialchars($data['nama_barang']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Input untuk Karyawan -->
                <div class="mb-3">
                    <label for="karyawan_id" class="form-label">Karyawan</label>
                    <select class="form-control select2" id="karyawan_id" name="karyawan_id" required>
                        <option value="">Pilih Karyawan</option>
                        <?php
                        // Ambil data Karyawan dari tabel daftar_karyawan
                        $sql = $koneksi->query("SELECT * FROM daftar_karyawan");
                        while ($data = $sql->fetch_assoc()) {
                            $selected = ($data['id'] == $data_aset['karyawan_id']) ? 'selected' : '';
                            echo "<option value='" . $data['id'] . "' $selected>" . htmlspecialchars($data['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Input untuk Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="Aktif" <?php echo ($data_aset['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Tidak Aktif" <?php echo ($data_aset['status'] == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Input untuk Tanggal Pembelian -->
                <div class="mb-3">
                    <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                    <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" required
                           value="<?php echo $data_aset['tanggal_pembelian']; ?>">
                </div>

                <!-- Tombol Submit -->
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary custom-btn">
                        <i class="fas fa-save me-2"></i> Simpan Perubahan
                    </button>
                    <a href="?page=aset" class="btn btn-secondary custom-btn">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
