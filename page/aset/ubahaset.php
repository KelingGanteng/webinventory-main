<?php
// Koneksi ke database
include('koneksibarang.php');

// Mengecek apakah ID aset diberikan di URL
if (isset($_GET['id'])) {
    $id_aset = $_GET['id'];

    // Mengambil data aset yang ingin diubah
    $sql = $koneksi->query("SELECT * FROM aset WHERE id = '$id_aset'");
    if ($sql->num_rows > 0) {
        $aset = $sql->fetch_assoc();
    } else {
        echo "<script>alert('Aset tidak ditemukan!'); window.location.href = '?page=aset';</script>";
        exit;
    }
}

// Mengecek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Memeriksa apakah 'kode_aset' dan 'nomor_urut' ada dalam form
    $kode_aset = isset($_POST['kode_aset']) ? $_POST['kode_aset'] : '';
    $nomor_urut = isset($_POST['nomor_urut']) ? $_POST['nomor_urut'] : '';

    // Pastikan nomor urut diberikan jika kode aset dipilih
    if ($kode_aset && $nomor_urut) {
        // Format nomor urut dengan padding 4 digit
        $nomor_urut = str_pad($nomor_urut, 4, '0', STR_PAD_LEFT);

        // Ambil nama jenis barang untuk digunakan dalam kode lengkap
        $jenis_barang = $_POST['jenis_barang']; // Ambil jenis barang dari form

        // Gabungkan kode aset, jenis barang, dan nomor urut untuk membentuk kode lengkap
        $kode_lengkap = $kode_aset . '/' . $jenis_barang . '/' . $nomor_urut; // Format: CBA001/Laptop/0001
    } else {
        $kode_lengkap = ''; // Tidak ada kode lengkap jika tidak ada kode aset atau nomor urut
    }

    // Ambil data lainnya dari form
    $departemen_id = $_POST['departemen_id'];
    $gudang_id = $_POST['gudang_id'];
    $status = $_POST['status'];
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $karyawan_id = $_POST['karyawan_id'];

    // Validasi karyawan_id
    $karyawan_check = $koneksi->query("SELECT id FROM daftar_karyawan WHERE id = '$karyawan_id'");
    if ($karyawan_check->num_rows == 0) {
        echo "<script>alert('Karyawan dengan ID tersebut tidak ditemukan!'); window.location.href = '?page=ubah-aset&id=$id_aset';</script>";
        exit; // Menghentikan proses jika karyawan tidak ditemukan
    }

    // Query untuk mengupdate data aset
    $sql = "UPDATE aset SET 
                kode_aset = '$kode_aset',
                kode_lengkap = '$kode_lengkap',
                departemen_id = '$departemen_id',
                status = '$status',
                tanggal_pembelian = '$tanggal_pembelian',
                karyawan_id = '$karyawan_id',
                gudang_id = '$gudang_id'
            WHERE id = '$id_aset'";

    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Data aset berhasil diperbarui!'); window.location.href='?page=aset';</script>";
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
                        // Ambil data Kode Aset dari tabel jenis_barang
                        $sql = $koneksi->query("SELECT * FROM jenis_barang");
                        while ($data = $sql->fetch_assoc()) {
                            $selected = ($data['code_barang'] == $aset['kode_aset']) ? 'selected' : '';
                            echo "<option value='" . $data['code_barang'] . "' $selected>" . $data['code_barang'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jenis_barang" class="form-label">Jenis Barang</label>
                    <select class="form-control" id="jenis_barang" name="jenis_barang" required>
                        <option value="">Pilih Jenis Barang</option>
                        <?php
                        $sql = $koneksi->query("SELECT * FROM jenis_barang ORDER BY jenis_barang");
                        while ($data = $sql->fetch_assoc()) {
                            $selected = ($data['jenis_barang'] == $aset['jenis_barang']) ? 'selected' : '';
                            echo "<option value='" . $data['jenis_barang'] . "' $selected>" . $data['jenis_barang'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nomor_urut" class="form-label">Nomor Urut</label>
                    <input type="number" class="form-control" id="nomor_urut" name="nomor_urut" value="<?php echo substr($aset['kode_lengkap'], -4); ?>" placeholder="Masukkan Nomor Urut" min="1" required>
                </div>

                <div class="mb-3">
                    <label for="kode_lengkap" class="form-label">Kode Aset Lengkap</label>
                    <input type="text" class="form-control" id="kode_lengkap" name="kode_lengkap" value="<?php echo $aset['kode_lengkap']; ?>" readonly>
                </div>

                <script>
                    $(document).ready(function() {
                        // Ketika kode_aset dipilih
                        $('#kode_aset').change(function() {
                            updateKodeLengkap();
                        });

                        // Ketika jenis_barang dipilih
                        $('#jenis_barang').change(function() {
                            updateKodeLengkap();
                        });

                        // Ketika nomor_urut diinput
                        $('#nomor_urut').on('input', function() {
                            updateKodeLengkap();
                        });

                        function updateKodeLengkap() {
                            var kodeAset = $('#kode_aset').val();
                            var jenisBarang = $('#jenis_barang').val();
                            var nomorUrut = $('#nomor_urut').val().padStart(4, '0'); // Format nomor urut dengan padding 4 digit

                            // Jika ketiga input terisi
                            if (kodeAset && jenisBarang && nomorUrut) {
                                var kodeLengkap = kodeAset + '/' + jenisBarang + '/' + nomorUrut;
                                $('#kode_lengkap').val(kodeLengkap); // Tampilkan kode lengkap
                            } else {
                                $('#kode_lengkap').val(''); // Kosongkan kode lengkap jika input tidak lengkap
                            }
                        }
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
                            $selected = ($data['id'] == $aset['departemen_id']) ? 'selected' : '';
                            echo "<option value='" . $data['id'] . "' $selected>" . htmlspecialchars($data['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Input untuk Gudang -->
                <div class="mb-3">
                    <label for="gudang_id" class="form-label">Nama Barang</label>
                    <select class="form-control select2" id="gudang_id" name="gudang_id" required>
                        <option value="">Pilih Nama Barang</option>
                        <?php
                        $sql = $koneksi->query("SELECT * FROM gudang ORDER BY nama_barang");
                        while ($data = $sql->fetch_assoc()) {
                            $selected = ($data['id'] == $aset['gudang_id']) ? 'selected' : '';
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
                            $selected = ($data['id'] == $aset['karyawan_id']) ? 'selected' : '';
                            echo "<option value='" . $data['id'] . "' $selected>" . htmlspecialchars($data['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Input untuk Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="Aktif" <?php echo ($aset['status'] == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                        <option value="Tidak Aktif" <?php echo ($aset['status'] == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Input untuk Tanggal Pembelian -->
                <div class="mb-3">
                    <label for="tanggal_pembelian" class="form-label">Tanggal Penyerahan</label>
                    <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" value="<?php echo $aset['tanggal_pembelian']; ?>" required>
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

<style>
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
</style>
