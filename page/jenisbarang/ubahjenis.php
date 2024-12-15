<?php
// Pastikan sudah terhubung ke database
// include("koneksi.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data jenis barang berdasarkan ID
    $query = $koneksi->query("SELECT * FROM jenis_barang WHERE id = '$id'");
    $data = $query->fetch_assoc();

    if (!$data) {
        die("Data tidak ditemukan.");
    }

    $jenis_barang = $data['jenis_barang'];
    $code_barang = $data['code_barang'];

    // Ambil kode departemen dan angka romawi dari code_barang
    $parts = explode('/', $code_barang);
    $departemen_kode = $parts[1];
    $angka_romawi = $parts[2];

    // Ambil data departemen untuk dropdown
    $query_departemen = $koneksi->query("SELECT * FROM departemen");
} else {
    die("ID tidak diberikan.");
}

if (isset($_POST['simpan'])) {
    $jenis_barang = $_POST['jenis_barang'];
    $departemen_id = $_POST['departemen'];
    $angka_romawi = $_POST['romawi'];

    // Ambil nama departemen berdasarkan id
    $query_departemen = $koneksi->query("SELECT nama FROM departemen WHERE id = '$departemen_id'");

    if ($query_departemen) {
        $departemen = $query_departemen->fetch_assoc();
        $departemen_kode = substr($departemen['nama'], 0, 2); // Ambil 2 huruf pertama dari nama departemen

        // Update data jenis barang
        $sql = $koneksi->query("UPDATE jenis_barang SET jenis_barang = '$jenis_barang', code_barang = 'SF/$departemen_kode/$angka_romawi' WHERE id = '$id'");

        if ($sql) {
            ?>
            <script type="text/javascript">
                alert("Data Berhasil Diubah");
                window.location.href = "?page=jenisbarang";
            </script>
            <?php
        } else {
            echo "Error: " . $koneksi->error;
        }
    } else {
        echo "Error: " . $koneksi->error;
    }
}
?>

<div class="container-fluid">
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Jenis Barang</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="body">
                    <form method="POST" enctype="multipart/form-data">
                        <label for="">Jenis Barang</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="jenis_barang" class="form-control" value="<?php echo $jenis_barang; ?>" required />
                            </div>
                        </div>

                        <!-- Dropdown Departemen -->
                        <label for="departemen">Departemen</label>
                        <div class="form-group">
                            <select name="departemen" id="departemen" class="form-control" required>
                                <?php
                                // Ambil data departemen untuk dropdown
                                $query_departemen = $koneksi->query("SELECT * FROM departemen");

                                // Cek apakah query berhasil
                                if ($query_departemen) {
                                    while ($row = $query_departemen->fetch_assoc()) {
                                        $selected = ($row['id'] == $departemen_id) ? 'selected' : '';
                                        echo "<option value='{$row['id']}' {$selected}>{$row['nama']}</option>";
                                    }
                                } else {
                                    echo "<option disabled>Data departemen tidak ditemukan</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Dropdown Angka Romawi -->
                        <label for="romawi">Angka Romawi</label>
                        <div class="form-group">
                            <select name="romawi" id="romawi" class="form-control" required>
                                <option value="I" <?php echo ($angka_romawi == "I") ? 'selected' : ''; ?>>I</option>
                                <option value="II" <?php echo ($angka_romawi == "II") ? 'selected' : ''; ?>>II</option>
                                <option value="III" <?php echo ($angka_romawi == "III") ? 'selected' : ''; ?>>III</option>
                                <option value="IV" <?php echo ($angka_romawi == "IV") ? 'selected' : ''; ?>>IV</option>
                            </select>
                        </div>

                        <!-- Kode Barang (automatically generated) -->
                        <label for="">Kode Barang</label>
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" name="code_barang" class="form-control" value="<?php echo $code_barang; ?>" disabled />
                                <small>(Kode barang akan dihasilkan otomatis)</small>
                            </div>
                        </div>

                        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
