<div class="container-fluid">
	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Tambah Jenis Barang</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<div class="body">
					<form method="POST" enctype="multipart/form-data">
						<label for="">Jenis Barang</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="jenis_barang" class="form-control" required />
							</div>
						</div>

						<!-- Dropdown Departemen -->
						<label for="departemen">Departemen</label>
						<div class="form-group">
							<select name="departemen" id="departemen" class="form-control" required>
								<?php
								// Ambil data departemen dari database
								$query_departemen = $koneksi->query("SELECT * FROM departemen");

								// Cek apakah query berhasil
								if ($query_departemen) {
									while ($row = $query_departemen->fetch_assoc()) {
										echo "<option value='{$row['id']}'>{$row['nama']}</option>";
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
								<option value="I">I</option>
								<option value="II">II</option>
								<option value="III">III</option>
								<option value="IV">IV</option>
							</select>
						</div>

						<!-- Kode Barang (automatically generated) -->
						<label for="">Kode Barang</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" id="generated_code" class="form-control" disabled />
								<small>(Kode barang akan dihasilkan otomatis)</small>
							</div>
						</div>

						<input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
					</form>

					<?php
					echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
						if (isset($_POST['simpan'])) {
							$jenis_barang = $_POST['jenis_barang'];
							$departemen_id = $_POST['departemen'];
							$angka_romawi = $_POST['romawi'];

							// Ambil nama departemen berdasarkan id
							$query_departemen = $koneksi->query("SELECT nama FROM departemen WHERE id = '$departemen_id'");

							if ($query_departemen && $query_departemen->num_rows > 0) {
								$departemen = $query_departemen->fetch_assoc();
								$departemen_kode = strtoupper(substr($departemen['nama'], 0, 2)); // Ambil 2 huruf pertama dari nama departemen
								$new_code_barang = "SF/$departemen_kode/$angka_romawi";

								// Periksa apakah jenis barang yang sama sudah ada
								$query_check = $koneksi->query("SELECT * FROM jenis_barang WHERE jenis_barang = '$jenis_barang'");

								if ($query_check->num_rows == 0) {
									// Insert data ke tabel jenis_barang
									$sql = $koneksi->query("INSERT INTO jenis_barang (jenis_barang, code_barang) VALUES ('$jenis_barang', '$new_code_barang')");

									if ($sql) {
										echo "<script>
										Swal.fire({
											icon: 'success',
											title: 'Berhasil',
											text: 'Data jenis barang berhasil disimpan',
											showConfirmButton: false,
											timer: 1500
										}).then(function() {
											window.location.href = '?page=jenisbarang';
										});
									  </script>"; 
									} else {
										echo "Error: " . $koneksi->error;
									}
								} else {
									echo "Error: Jenis barang sudah ada. Tidak dapat duplikasi.";
								}
							} else {
								echo "Error: Data departemen tidak ditemukan.";
							}
						}
					?>


				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.getElementById('departemen').addEventListener('change', generateCode);
	document.getElementById('romawi').addEventListener('change', generateCode);

	function generateCode() {
		const departemen = document.getElementById('departemen');
		const romawi = document.getElementById('romawi');
		if (departemen && romawi) {
			const departemenKode = departemen.options[departemen.selectedIndex].text.substring(0, 2).toUpperCase();
			const romawiValue = romawi.value;
			document.getElementById('generated_code').value = `SF/${departemenKode}/${romawiValue}`;
		}
	}
</script>
