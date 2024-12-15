<?php
// Debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<!-- Debug GET: ";
print_r($_GET);
echo " -->";
echo "<!-- Debug POST: ";
print_r($_POST);
echo " -->";

// Cek id
if (!isset($_GET['id'])) {
	echo "<script>
        alert('ID tidak ditemukan!');
        window.location.href='?page=satuanbarang';
    </script>";
	exit;
}

$id = $_GET['id'];

// Query data
$sql = $koneksi->query("SELECT * FROM satuan WHERE id='$id'");
$data = $sql->fetch_assoc();

// Cek data
if (!$data) {
	echo "<script>
        alert('Data tidak ditemukan!');
        window.location.href='?page=satuanbarang';
    </script>";
	exit;
}
?>

<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Ubah Satuan Barang</h6>
		</div>
		<div class="card-body">
			<form method="POST">
				<div class="form-group">
					<label>Satuan Barang</label>
					<input type="text" name="satuan" value="<?php echo $data['satuan']; ?>" class="form-control"
						required>
				</div>

				<div>
					<input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
					<a href="?page=satuanbarang" class="btn btn-secondary">Batal</a>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
if (isset($_POST['simpan'])) {
	$satuan = $_POST['satuan'];

	$sql = $koneksi->query("UPDATE satuan SET satuan='$satuan' WHERE id='$id'");

	if ($sql) {
		echo "<script>
            alert('Data Berhasil Diubah');
            window.location.href='?page=satuanbarang';
        </script>";
	} else {
		echo "<script>
            alert('Gagal Mengubah Data!');
        </script>";
	}
}
?>