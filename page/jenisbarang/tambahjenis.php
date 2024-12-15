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
								<input type="text" name="code_barang" class="form-control" disabled />
								<small>(Kode barang akan dihasilkan otomatis)</small>
							</div>
						</div>

						<input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
					</form>

					<?php
					if (isset($_POST['simpan'])) {
						$jenis_barang = $_POST['jenis_barang'];
						$departemen_id = $_POST['departemen'];
						$angka_romawi = $_POST['romawi'];

						// Ambil nama departemen berdasarkan id
						$query_departemen = $koneksi->query("SELECT nama FROM departemen WHERE id = '$departemen_id'");

						if ($query_departemen) {
							$departemen = $query_departemen->fetch_assoc();
							$departemen_kode = substr($departemen['nama'], 0, 2); // Ambil 2 huruf pertama dari nama departemen
					
							// Ambil nomor urut terakhir berdasarkan format yang sama
							$query_last_code = $koneksi->query("SELECT MAX(SUBSTRING(code_barang, 10, 4)) AS last_code 
                                            FROM jenis_barang 
                                            WHERE code_barang LIKE 'SF/$departemen_kode/$angka_romawi/%'");

							if ($query_last_code) {
								$last_code_data = $query_last_code->fetch_assoc();
								// Pastikan 'last_code' adalah integer
								$last_code = $last_code_data['last_code'] ? (int) $last_code_data['last_code'] : 0;
								// Format nomor urut baru 0001, 0002, dst
					

								$new_code_barang = "SF/$departemen_kode/$angka_romawi";

								// Insert data ke tabel jenis_barang
								$sql = $koneksi->query("INSERT INTO jenis_barang (jenis_barang, code_barang) VALUES ('$jenis_barang', '$new_code_barang')");

								if ($sql) {
									?>
									<script type="text/javascript">
										alert("Data Berhasil Disimpan");
										window.location.href = "?page=jenisbarang";
									</script>
									<?php
								} else {
									echo "Error: " . $koneksi->error;
								}
							} else {
								echo "Error: " . $koneksi->error;
							}
						} else {
							echo "Error: " . $koneksi->error;
						}
					}
					?>

				</div>
			</div>
		</div>
	</div>
</div>