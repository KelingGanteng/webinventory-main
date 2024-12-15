<script>
    function sum() {
        var stok = document.getElementById('stok').value;
        var jumlahmasuk = document.getElementById('jumlahmasuk').value;
        var result = parseInt(stok) - parseInt(jumlahmasuk);
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
                url: 'get_satuan1.php',
                data: { tamp: tamp },  // Kirimkan kode barang untuk mengambil satuan
                success: function (response) {
                    // Masukkan response (HTML satuan) ke dalam div tampung
                    $('.tampung1').html(response);
                }
            });
        });
    });
</script>

<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Periksa apakah koneksi berhasil
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil ID transaksi yang diteruskan melalui URL
$id_transaksi = isset($_GET['id_transaksi']) ? $_GET['id_transaksi'] : '';

// Ambil data transaksi berdasarkan ID
$sql = $koneksi->query("SELECT * FROM barang_masuk WHERE id_transaksi = '$id_transaksi'");
$data_transaksi = $sql->fetch_assoc();

if (!$data_transaksi) {
    echo "Data transaksi tidak ditemukan!";
    exit;
}

// Ambil data barang
$kode_barang = $data_transaksi['kode_barang'];
$nama_barang = $data_transaksi['nama_barang'];
$jumlah = $data_transaksi['jumlah'];
$satuan = $data_transaksi['satuan'];
$kondisi = explode(", ", $data_transaksi['kondisi']);  // Split kondisi jika lebih dari satu
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Barang Masuk</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="body">
                    <form method="POST" enctype="multipart/form-data">
                        <label for="">Id Transaksi</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="id_transaksi" class="form-control" id="id_transaksi"
                                    value="<?php echo $data_transaksi['id_transaksi']; ?>" readonly />
                            </div>
                        </div>

                        <label for="">Tanggal Masuk</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="date" name="tanggal_masuk" class="form-control"
                                    value="<?php echo $data_transaksi['tanggal']; ?>" />
                            </div>
                        </div>

                        <label for="">Barang</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select name="barang" id="cmb_barang" class="form-control">
                                    <option value="">-- Pilih Barang --</option>
                                    <?php
                                    $sql = $koneksi->query("SELECT * FROM gudang ORDER BY kode_barang");
                                    while ($row = $sql->fetch_assoc()) {
                                        // Tandai barang yang sudah dipilih
                                        $selected = ($row['kode_barang'] == $kode_barang) ? 'selected' : '';
                                        echo "<option value='{$row['kode_barang']}.{$row['nama_barang']}' $selected>{$row['kode_barang']} | {$row['nama_barang']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="tampung1"></div>
                        </div>

                        <div class="tampung"></div>

                        <label for="">Jumlah</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="number" name="jumlah" class="form-control" style="max-width: 70px;"
                                    inputmode="numeric" min="0" step="1" value="<?php echo $jumlah; ?>" readonly />
                            </div>
                        </div>

                        <label for="">Kondisi</label>
                        <div class="form-group">
                            <div class="form-line">
                                <div class="checkbox-group">
                                    <label><input type="checkbox" name="kondisi[]" value="Baik" <?php echo (in_array('Baik', $kondisi) ? 'checked' : ''); ?> /> Baik</label>
                                    <label><input type="checkbox" name="kondisi[]" value="Rusak" <?php echo (in_array('Rusak', $kondisi) ? 'checked' : ''); ?> /> Rusak</label>
                                    <label><input type="checkbox" name="kondisi[]" value="Bekas" <?php echo (in_array('Bekas', $kondisi) ? 'checked' : ''); ?> /> Bekas</label>
                                </div>
                            </div>
                        </div>

                        <label for="jumlah">Total Stok</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input readonly="readonly" name="jumlah" id="jumlah" type="number" class="form-control"
                                    value="<?php echo $jumlah; ?>" />
                            </div>
                        </div>

                        <label for="satuan">Satuan Barang</label>
                        <div class="form-group">
                            <div class="form-line">
                                <select name="satuan" id="satuan" class="form-control">
                                    <option value="">-- Pilih Satuan --</option>
                                    <?php
                                    $sql_satuan = $koneksi->query("SELECT * FROM satuan ORDER BY satuan");
                                    while ($data_satuan = $sql_satuan->fetch_assoc()) {
                                        $selected = ($data_satuan['satuan'] == $satuan) ? 'selected' : '';
                                        echo "<option value='" . $data_satuan['satuan'] . "' $selected>" . $data_satuan['satuan'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
                    </form>

                    <?php
                    if (isset($_POST['simpan'])) {
                        $id_transaksi = $_POST['id_transaksi'];
                        $tanggal = $_POST['tanggal_masuk'];

                        $barang = $_POST['barang'];
                        $pecah_barang = explode(".", $barang);
                        $kode_barang = $pecah_barang[0];
                        $nama_barang = $pecah_barang[1];

                        $jumlah = $_POST['jumlah'];
                        $satuan = $_POST['satuan'];

                        // Menangani kondisi yang dikirimkan sebagai array
                        $kondisi = isset($_POST['kondisi']) ? implode(", ", $_POST['kondisi']) : '';

                        // Update data barang masuk
                        $sql = $koneksi->query("UPDATE barang_masuk SET 
                            tanggal='$tanggal', kode_barang='$kode_barang', nama_barang='$nama_barang', jumlah='$jumlah', satuan='$satuan', kondisi='$kondisi' 
                            WHERE id_transaksi='$id_transaksi'");

                        if ($sql) {
                            echo "<script type='text/javascript'>
                                alert('Data Berhasil Diubah');
                                window.location.href = '?page=barangmasuk';
                            </script>";
                        } else {
                            echo "<script type='text/javascript'>
                                alert('Data Gagal Diubah');
                            </script>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>