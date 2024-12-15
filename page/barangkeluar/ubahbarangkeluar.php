<script>
    function sum() {
        var stok = document.getElementById('stok').value;
        var jumlahkeluar = document.getElementById('jumlahkeluar').value;
        var result = parseInt(stok) - parseInt(jumlahkeluar);
        if (!isNaN(result)) {
            document.getElementById('total').value = result;
        }
    }

    $(document).ready(function () {
        // Ketika barang dipilih
        $('#cmb_barang').change(function () {
            var tamp = $(this).val(); // Ambil nilai barang
            $.ajax({
                type: 'POST',
                url: 'get_satuan.php',  // Pastikan ini adalah file yang mengembalikan satuan
                data: { tamp: tamp },    // Kirimkan kode barang untuk mengambil satuan
                success: function (response) {
                    // Masukkan response (HTML satuan) ke dalam div tampung
                    $('.tampung').html(response);  // Tampilkan satuan di div
                }
            });
        });
    });
</script>

<?php

$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Cek koneksi ke database
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil id_transaksi dari URL
$id_transaksi = $_GET['id_transaksi'];  // Dapatkan id_transaksi dari URL

// Query untuk mengambil data barang keluar yang ingin diubah
$query = "SELECT * FROM barang_keluar WHERE id_transaksi = '$id_transaksi'";
$result = $koneksi->query($query);
$data = $result->fetch_assoc();

// Cek apakah data ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href = '?page=barangkeluar';</script>";
    exit;
}

// Tanggal barang keluar
$tanggal_keluar = date("Y-m-d");

?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Barang Keluar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="body">
                    <form method="POST" enctype="multipart/form-data">

                        <label for="">Id Transaksi</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="id_transaksi" class="form-control" id="id_transaksi"
                                    value="<?php echo $data['id_transaksi']; ?>" readonly />
                            </div>
                        </div>

                        <label for="">Tanggal Keluar</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="date" name="tanggal_keluar" class="form-control" id="tanggal_keluar"
                                    value="<?php echo $data['tanggal']; ?>" />
                            </div>
                        </div>

                        <label for="">Barang</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select name="barang" id="cmb_barang" class="form-control">
                                    <option value="">-- Pilih Barang --</option>
                                    <?php
                                    // Ambil barang yang dipilih
                                    $barang_terpilih = $data['kode_barang'] . '.' . $data['nama_barang'];

                                    $sql = $koneksi->query("select * from gudang order by kode_barang");
                                    while ($data_barang = $sql->fetch_assoc()) {
                                        $selected = ($barang_terpilih == $data_barang['kode_barang'] . '.' . $data_barang['nama_barang']) ? "selected" : "";
                                        echo "<option value='$data_barang[kode_barang].$data_barang[nama_barang]' $selected>$data_barang[kode_barang] | $data_barang[nama_barang]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="tampung"></div>

                        <label for="karyawan">Pilih Karyawan</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select name="karyawan" id="karyawan" class="form-control">
                                    <option value="">-- Pilih Karyawan --</option>
                                    <?php
                                    $sql_karyawan = $koneksi->query("SELECT dk.id, dk.nama AS nama_karyawan, d.nama AS nama_departemen
                                              FROM daftar_karyawan dk
                                              LEFT JOIN departemen d ON dk.departemen_id = d.id
                                              ORDER BY dk.nama ASC");
                                    while ($data_karyawan = $sql_karyawan->fetch_assoc()) {
                                        $selected_karyawan = ($data_karyawan['id'] == $data['karyawan_id']) ? "selected" : "";
                                        echo "<option value='" . $data_karyawan['id'] . "' $selected_karyawan>";
                                        echo htmlspecialchars($data_karyawan['nama_karyawan']) . " (" . htmlspecialchars($data_karyawan['nama_departemen']) . ")";
                                        echo "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <label for="">Jumlah</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="number" name="jumlahkeluar" class="form-control" style="max-width: 70px;"
                                    inputmode="numeric" min="0" step="1" value="<?php echo $data['jumlah']; ?>" />
                            </div>
                        </div>

                        <label for="kondisi">Kondisi</label>
                        <div class="form-group">
                            <div class="form-line">
                                <!-- Menampilkan checkbox dalam format sederhana -->
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="kondisi[]" value="Baik"
                                            <?php echo (strpos($data['kondisi'], 'Baik') !== false) ? 'checked' : ''; ?> /> Baik</label>
                                    <label><input type="checkbox" name="kondisi[]" value="Rusak"
                                            <?php echo (strpos($data['kondisi'], 'Rusak') !== false) ? 'checked' : ''; ?> /> Rusak</label>
                                    <label><input type="checkbox" name="kondisi[]" value="Bekas"
                                            <?php echo (strpos($data['kondisi'], 'Bekas') !== false) ? 'checked' : ''; ?> /> Bekas</label>
                                </div>
                            </div>
                        </div>

                        <label for="total">Total Stok</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input readonly="readonly" name="jumlah" id="jumlah" type="number" class="form-control">
                            </div>
                        </div>

                        <div class="tampung1"></div>

                        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">

                    </form>

                    <?php
                    // Proses simpan data setelah klik "Simpan"
                    if (isset($_POST['simpan'])) {
                        $id_transaksi = $_POST['id_transaksi'];
                        $tanggal = $_POST['tanggal_keluar'];
                        $karyawan_id = $_POST['karyawan'];
                        $barang = $_POST['barang'];
                        $pecah_barang = explode(".", $barang);
                        $kode_barang = $pecah_barang[0];
                        $nama_barang = $pecah_barang[1];

                        // Ambil kondisi yang dipilih
                        $kondisi = isset($_POST['kondisi']) ? implode(", ", $_POST['kondisi']) : '';
                        $jumlah = $_POST['jumlahkeluar'];
                        $satuan = $_POST['satuan'];

                        // Proses update data barang keluar
                        $sql_update = $koneksi->query("UPDATE barang_keluar 
                                                       SET tanggal = '$tanggal', kode_barang = '$kode_barang', 
                                                           nama_barang = '$nama_barang', kondisi = '$kondisi', 
                                                           jumlah = '$jumlah', satuan = '$satuan', karyawan_id = '$karyawan_id'
                                                       WHERE id_transaksi = '$id_transaksi'");

                        // Periksa jika berhasil
                        if ($sql_update) {
                            echo "<script>alert('Data berhasil diubah'); window.location.href = '?page=barangkeluar';</script>";
                        } else {
                            echo "<script>alert('Gagal mengubah data!');</script>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
