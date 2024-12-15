<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil id_retur dari URL
if (isset($_GET['id_retur'])) {
    $id_retur = $_GET['id_retur'];

    // Ambil data barang retur berdasarkan id_retur
    $sql = $koneksi->query("SELECT * FROM barang_retur WHERE id_retur = '$id_retur'");
    $data_retur = $sql->fetch_assoc();
}

// Jika tidak ada data, alihkan ke halaman barang retur
if (!$data_retur) {
    header("Location: ?page=barangretur");
    exit;
}

// Ambil ID Transaksi terakhir
$no = mysqli_query($koneksi, "SELECT id_retur FROM barang_retur ORDER BY id_retur DESC LIMIT 1");
$idtran = mysqli_fetch_array($no);

// Jika tidak ada transaksi retur sebelumnya, inisialisasi kode retur pertama
if ($idtran) {
    $kode = $idtran['id_retur'];
} else {
    // Jika tidak ada data retur sebelumnya, buat kode retur pertama
    $kode = "RTR-" . date("m") . date("y") . "000"; // Format awal retur
}

// Mengambil angka urut dari id_retur terakhir
$urut = substr($kode, 8, 3);

// Tambah 1 pada angka urut
$tambah = (int) $urut + 1;

// Format bulan dan tahun
$bulan = date("m");
$tahun = date("y");

// Membuat format kode retur berdasarkan angka urut
if (strlen($tambah) == 1) {
    $format = "RTR-" . $bulan . $tahun . "00" . $tambah;
} else if (strlen($tambah) == 2) {
    $format = "RTR-" . $bulan . $tahun . "0" . $tambah;
} else {
    $format = "RTR-" . $bulan . $tahun . $tambah;
}

// Tanggal retur saat ini
$tanggal_retur = date("Y-m-d");

// Proses Update Data
if (isset($_POST['update_retur'])) {
    $id_retur = $_POST['id_retur'];
    $tanggal_retur = $_POST['tanggal_retur'];
    $karyawan_id = $_POST['karyawan_id'];
    $departemen_id = $_POST['departemen_id'];

    // Ambil barang yang dipilih
    $barang = $_POST['barang'];
    $pecah_barang = explode(".", $barang);
    $kode_barang = $pecah_barang[0];
    $nama_barang = $pecah_barang[1];

    // Ambil kondisi yang dipilih
    $kondisi = isset($_POST['kondisi']) ? implode(", ", $_POST['kondisi']) : '';

    // Ambil kerusakan dan kerusakan detail jika ada
    $kerusakan = $_POST['kerusakan'];
    $kerusakan_detail = isset($_POST['kerusakan_detail']) ? $_POST['kerusakan_detail'] : '';
    if ($kerusakan_detail) {
        $kerusakan .= ', ' . $kerusakan_detail;
    }

    $jumlah_retur = $_POST['jumlah_retur'];

    // Update data retur di database
    $sql_update = $koneksi->query("UPDATE barang_retur SET 
        tanggal_retur = '$tanggal_retur', 
        kode_barang = '$kode_barang', 
        nama_barang = '$nama_barang', 
        kondisi = '$kondisi', 
        kerusakan = '$kerusakan', 
        jumlah = '$jumlah_retur', 
        karyawan_id = '$karyawan_id', 
        departemen_id = '$departemen_id' 
        WHERE id_retur = '$id_retur'");

    // Update stok barang di gudang
    $sql_update_stok = $koneksi->query("UPDATE gudang SET jumlah = jumlah + $jumlah_retur WHERE kode_barang = '$kode_barang'");

    if ($sql_update) {
        echo "<script>alert('Data barang retur berhasil diperbarui!'); window.location.href = '?page=barangretur';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data barang retur!');</script>";
    }
}
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Barang Retur</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <form method="POST" enctype="multipart/form-data">
                    <!-- Form ID Retur -->
                    <label for="id_retur">ID Retur</label>
                    <div class="form-group">
                        <input type="text" name="id_retur" class="form-control" id="id_retur"
                            value="<?php echo $data_retur['id_retur']; ?>" readonly />
                    </div>

                    <!-- Tanggal Retur -->
                    <label for="tanggal_retur">Tanggal Retur</label>
                    <div class="form-group">
                        <input type="date" name="tanggal_retur" class="form-control" id="tanggal_retur"
                            value="<?php echo $data_retur['tanggal_retur']; ?>" />
                    </div>

                    <!-- Barang -->
                    <label for="barang">Barang</label>
                    <div class="form-group">
                        <select name="barang" id="cmb_barang" class="form-control">
                            <option value="<?php echo $data_retur['kode_barang'] . '.' . $data_retur['nama_barang']; ?>">
                                <?php echo $data_retur['kode_barang'] . " | " . $data_retur['nama_barang']; ?>
                            </option>
                            <?php
                            $sql = $koneksi->query("SELECT * FROM gudang ORDER BY nama_barang");
                            while ($data = $sql->fetch_assoc()) {
                                echo "<option value='$data[kode_barang].$data[nama_barang]'>$data[kode_barang] | $data[nama_barang]</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Kolom Karyawan -->
                    <div class="mb-3">
                        <label for="karyawan_id" class="form-label">Karyawan</label>
                        <select name="karyawan_id" id="karyawan_id" class="form-control">
                            <option value="<?php echo $data_retur['karyawan_id']; ?>">
                                <?php
                                $karyawan_query = $koneksi->query("SELECT * FROM daftar_karyawan WHERE id = " . $data_retur['karyawan_id']);
                                $karyawan = $karyawan_query->fetch_assoc();
                                echo $karyawan['nama'];
                                ?>
                            </option>
                            <?php
                            $karyawan_query = $koneksi->query("SELECT * FROM daftar_karyawan");
                            while ($karyawan = $karyawan_query->fetch_assoc()) {
                                echo "<option value='" . $karyawan['id'] . "'>" . $karyawan['nama'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Kolom Departemen -->
                    <div class="mb-3">
                        <label for="departemen_id" class="form-label">Departemen</label>
                        <select name="departemen_id" id="departemen_id" class="form-control">
                            <option value="<?php echo $data_retur['departemen_id']; ?>">
                                <?php
                                $departemen_query = $koneksi->query("SELECT * FROM departemen WHERE id = " . $data_retur['departemen_id']);
                                $departemen = $departemen_query->fetch_assoc();
                                echo $departemen['nama'];
                                ?>
                            </option>
                            <?php
                            $departemen_query = $koneksi->query("SELECT * FROM departemen");
                            while ($departemen = $departemen_query->fetch_assoc()) {
                                echo "<option value='" . $departemen['id'] . "'>" . $departemen['nama'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Kondisi Barang -->
                    <label for="kondisi">Kondisi</label>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="kondisi[]" value="Baik"
                                    <?php echo (strpos($data_retur['kondisi'], 'Baik') !== false) ? 'checked' : ''; ?> /> Baik</label>
                            <label><input type="checkbox" name="kondisi[]" value="Rusak"
                                    <?php echo (strpos($data_retur['kondisi'], 'Rusak') !== false) ? 'checked' : ''; ?> /> Rusak</label>
                            <label><input type="checkbox" name="kondisi[]" value="Bekas"
                                    <?php echo (strpos($data_retur['kondisi'], 'Bekas') !== false) ? 'checked' : ''; ?> /> Bekas</label>
                        </div>
                    </div>

                    <div id="kerusakan_dropdown" class="form-group" style="display:<?php echo (strpos($data_retur['kondisi'], 'Rusak') !== false) ? 'block' : 'none'; ?>;">
                        <label for="kerusakan">Kerusakan Deskripsi</label>
                        <div class="form-group">
                            <textarea name="kerusakan" id="kerusakan" class="form-control" rows="4"><?php echo $data_retur['kerusakan']; ?></textarea>
                        </div>
                    </div>

                    <label for="">Jumlah</label>
                    <div class="form-group">
                        <div class="form-line">
                            <input type="number" name="jumlah_retur" class="form-control" style="max-width: 70px;"
                                inputmode="numeric" min="0" step="1" />
                        </div>
                    </div>

                    <input type="submit" name="update_retur" value="Update Retur" class="btn btn-primary" />
                </form>
            </div>
        </div>
    </div>
</div>
