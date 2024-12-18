<?php
// Cek apakah kode_barang ada di URL
if (isset($_GET['kode_barang'])) {
    $kode_barang = $_GET['kode_barang'];

    // Ambil data barang berdasarkan kode_barang
    $sql = $koneksi->query("SELECT * FROM gudang WHERE kode_barang='$kode_barang'");
    if ($sql->num_rows > 0) {
        $data = $sql->fetch_assoc();
    } else {
        // Jika data tidak ditemukan
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location.href='?page=gudang';
              </script>";
        exit();
    }
}

// Proses update data
if (isset($_POST['update'])) {
    $nama_barang = $_POST['nama_barang'];
    $jenis_barang = $_POST['jenis_barang'];
    $jumlah = $_POST['jumlah'];
    $satuan = $_POST['satuan'];

    // Update data barang
    $update = $koneksi->query("UPDATE gudang 
        SET nama_barang='$nama_barang', jenis_barang='$jenis_barang', jumlah='$jumlah', satuan='$satuan' 
        WHERE kode_barang='$kode_barang'");

    if ($update) {
        echo "<script>
                alert('Data berhasil diubah!');
                window.location.href='?page=gudang';
              </script>";
    } else {
        echo "<script>
                alert('Gagal mengubah data: " . $koneksi->error . "');
              </script>";
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Ubah Stok Gudang</h6>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="form-group">
          <label for="kode_barang">Kode Barang</label>
          <input type="text" name="kode_barang" id="kode_barang" class="form-control" value="<?php echo htmlspecialchars($data['kode_barang']); ?>" readonly />
        </div>

        <div class="form-group">
          <label for="nama_barang">Nama Barang</label>
          <input type="text" name="nama_barang" class="form-control" value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required />
        </div>

        <div class="form-group">
          <label for="jenis_barang">Jenis Barang</label>
          <select name="jenis_barang" class="form-control" required>
            <option value="">-- Pilih Jenis Barang --</option>
            <?php
            $jenis_sql = $koneksi->query("SELECT * FROM jenis_barang ORDER BY jenis_barang");
            while ($jenis = $jenis_sql->fetch_assoc()) {
                echo "<option value='" . $jenis['jenis_barang'] . "' " . ($data['jenis_barang'] == $jenis['jenis_barang'] ? 'selected' : '') . ">" . $jenis['jenis_barang'] . "</option>";
            }
            ?>
          </select>
        </div>

        <div class="form-group">
          <label for="jumlah">Jumlah</label>
          <input type="number" name="jumlah" class="form-control" value="<?php echo htmlspecialchars($data['jumlah']); ?>" min="0" step="1" required />
        </div>

        <div class="form-group">
          <label for="satuan">Satuan Barang</label>
          <select name="satuan" class="form-control" required>
            <option value="">-- Pilih Satuan Barang --</option>
            <?php
            $satuan_sql = $koneksi->query("SELECT * FROM satuan ORDER BY id");
            while ($satuan = $satuan_sql->fetch_assoc()) {
                echo "<option value='" . $satuan['satuan'] . "' " . ($data['satuan'] == $satuan['satuan'] ? 'selected' : '') . ">" . $satuan['satuan'] . "</option>";
            }
            ?>
          </select>
        </div>

        <button type="submit" name="update" class="btn btn-primary">Update</button>
        <a href="?page=gudang" class="btn btn-secondary">Batal</a>
      </form>
    </div>
  </div>
</div>
