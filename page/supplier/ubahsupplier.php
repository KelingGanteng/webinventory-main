<?php
// Debugging


// Cek koneksi
if (!isset($koneksi)) {
	die("Koneksi database tidak tersedia!");
}

// Cek parameter kode_supplier
if (!isset($_GET['kode_supplier'])) {
	echo "<script>
        alert('Kode supplier tidak ditemukan!');
        window.location.href='?page=supplier';
    </script>";
	exit;
}

$kode_supplier = mysqli_real_escape_string($koneksi, $_GET['kode_supplier']);

// Query dengan prepared statement
$stmt = $koneksi->prepare("SELECT * FROM tb_supplier WHERE kode_supplier = ?");
$stmt->bind_param("s", $kode_supplier);
$stmt->execute();
$result = $stmt->get_result();
$tampil = $result->fetch_assoc();

// Cek apakah data ditemukan
if (!$tampil) {
	echo "<script>
        alert('Data supplier tidak ditemukan!');
        window.location.href='?page=supplier';
    </script>";
	exit;
}
?>

<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Ubah Supplier</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<div class="body">
					<form method="POST">
						<label for="kode_supplier">Kode Supplier</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="kode_supplier" id="kode_supplier"
									value="<?php echo htmlspecialchars($tampil['kode_supplier']); ?>" readonly
									class="form-control" />
							</div>
						</div>

						<label for="nama_supplier">Nama Supplier</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="nama_supplier" id="nama_supplier"
									value="<?php echo htmlspecialchars($tampil['nama_supplier']); ?>"
									class="form-control" required />
							</div>
						</div>

						<label for="alamat">Alamat</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="alamat" id="alamat"
									value="<?php echo htmlspecialchars($tampil['alamat']); ?>" class="form-control"
									required />
							</div>
						</div>

						<label for="telepon">Telepon</label>
						<div class="form-group">
							<div class="form-line">
								<input type="number" name="telepon" id="telepon"
									value="<?php echo htmlspecialchars($tampil['telepon']); ?>" class="form-control"
									required />
							</div>
						</div>

						<input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
					</form>

					<?php
					if (isset($_POST['simpan'])) {
						$nama_supplier = mysqli_real_escape_string($koneksi, $_POST['nama_supplier']);
						$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
						$telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);

						// Update dengan prepared statement
						$update = $koneksi->prepare("UPDATE tb_supplier SET 
                            nama_supplier = ?, 
                            alamat = ?, 
                            telepon = ? 
                            WHERE kode_supplier = ?");

						$update->bind_param("ssss", $nama_supplier, $alamat, $telepon, $kode_supplier);

						if ($update->execute()) {
							echo "<script>
                                alert('Data Berhasil Diubah');
                                window.location.href='?page=supplier';
                            </script>";
						} else {
							echo "<script>
                                alert('Gagal mengubah data: " . $koneksi->error . "');
                            </script>";
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>