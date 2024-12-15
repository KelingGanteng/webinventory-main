<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Fungsi untuk generate kode supplier baru
function generateKodeSupplier($koneksi)
{
	try {
		// Query untuk mendapatkan kode supplier terakhir
		$query = "SELECT kode_supplier FROM tb_supplier ORDER BY kode_supplier DESC LIMIT 1";
		$result = mysqli_query($koneksi, $query);

		if (!$result) {
			throw new Exception("Error query: " . mysqli_error($koneksi));
		}

		$bulan = date("m");
		$tahun = date("y");

		if ($row = mysqli_fetch_assoc($result)) {
			// Jika ada data sebelumnya
			$lastKode = $row['kode_supplier'];
			// Ambil 3 digit terakhir
			$lastNumber = (int) substr($lastKode, -3);
			$nextNumber = $lastNumber + 1;
		} else {
			// Jika belum ada data
			$nextNumber = 1;
		}

		// Format kode supplier: SUP-MMYY-XXX
		return sprintf("SUP%s%s%03d", $bulan, $tahun, $nextNumber);

	} catch (Exception $e) {
		error_log("Error generating kode supplier: " . $e->getMessage());
		return "SUP" . $bulan . $tahun . "001"; // Default fallback
	}
}

// Generate kode supplier baru
$kode_supplier = generateKodeSupplier($koneksi);
?>

<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Tambah Supplier</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<div class="body">
					<form method="POST" id="tb_supplier">
						<label for="kode_supplier">Kode Supplier</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="kode_supplier" id="tb_supplier" class="form-control" required
									placeholder="Masukkan kode supplier" />
							</div>
						</div>

						<label for="nama_supplier">Nama Supplier</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="nama_supplier" id="nama_supplier" class="form-control" required
									placeholder="Masukkan nama supplier" />
							</div>
						</div>

						<label for="alamat">Alamat</label>
						<div class="form-group">
							<div class="form-line">
								<textarea name="alamat" id="alamat" class="form-control" required
									placeholder="Masukkan alamat lengkap" rows="3"></textarea>
							</div>
						</div>

						<label for="telepon">Telepon</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="telepon" id="telepon" class="form-control" required
									placeholder="Contoh: 081234567890" pattern="[0-9]{10,13}" maxlength="13" />
								<small class="form-text text-muted">
									Masukkan nomor telepon 10-13 digit
								</small>
							</div>
						</div>

						<div class="form-group">
							<button type="submit" name="simpan" class="btn btn-primary">
								<i class="fas fa-save"></i> Simpan
							</button>
							<a href="?page=supplier" class="btn btn-secondary">
								<i class="fas fa-arrow-left"></i> Kembali
							</a>
						</div>
					</form>

					<?php
					if (isset($_POST['simpan'])) {
						try {
							$kode_supplier = mysqli_real_escape_string($koneksi, $_POST['kode_supplier']);
							$nama_supplier = mysqli_real_escape_string($koneksi, $_POST['nama_supplier']);
							$alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
							$telepon = mysqli_real_escape_string($koneksi, $_POST['telepon']);

							// Validasi input
							if (empty($nama_supplier) || empty($alamat) || empty($telepon)) {
								throw new Exception("Semua field harus diisi!");
							}

							if (!preg_match("/^[0-9]{10,13}$/", $telepon)) {
								throw new Exception("Format nomor telepon tidak valid!");
							}

							// Cek duplikasi
							$check = $koneksi->prepare("SELECT kode_supplier FROM tb_supplier WHERE nama_supplier = ?");
							$check->bind_param("s", $nama_supplier);
							$check->execute();
							$result = $check->get_result();

							if ($result->num_rows > 0) {
								throw new Exception("Supplier dengan nama tersebut sudah ada!");
							}

							// Insert data
							$stmt = $koneksi->prepare("INSERT INTO tb_supplier 
                                (kode_supplier, nama_supplier, alamat, telepon) 
                                VALUES (?, ?, ?, ?)");

							$stmt->bind_param("ssss", $kode_supplier, $nama_supplier, $alamat, $telepon);

							if ($stmt->execute()) {
								echo "<script>
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: 'Data supplier berhasil disimpan',
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(function() {
                                        window.location.href='?page=supplier';
                                    });
                                </script>";
							} else {
								throw new Exception("Gagal menyimpan data: " . $stmt->error);
							}

						} catch (Exception $e) {
							echo "<script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: '" . addslashes($e->getMessage()) . "'
                                });
                            </script>";
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	// Validasi form
	document.getElementById('formSupplier').addEventListener('submit', function (e) {
		e.preventDefault();

		Swal.fire({
			title: 'Konfirmasi',
			text: "Apakah Anda yakin ingin menyimpan data supplier ini?",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Simpan!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				this.submit();
			}
		});
	});
</script>