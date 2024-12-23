<div class="container-fluid">
	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Ubah Jenis Barang</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<div class="body">
					<?php
					// Ensure the 'id' is provided in the URL for editing
					if (isset($_GET['id'])) {
						$id = $_GET['id'];

						// Fetch the existing data for the selected 'id'
						$sql = $koneksi->query("SELECT * FROM jenis_barang WHERE id = '$id'");
						$data = $sql->fetch_assoc();

						if (!$data) {
							echo "<script>alert('Data tidak ditemukan!'); window.location.href = '?page=jenisbarang';</script>";
							exit;
						}
					?>

					<form method="POST" enctype="multipart/form-data">
						<!-- Jenis Barang -->
						<label for="">Jenis Barang</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="jenis_barang" class="form-control" value="<?php echo htmlspecialchars($data['jenis_barang']); ?>" required />
							</div>
						</div>

						<!-- Dropdown Departemen -->
						<label for="departemen">Departemen</label>
						<div class="form-group">
							<select name="departemen" id="departemen" class="form-control" required>
								<?php
								// Fetch departments to populate the dropdown
								$query_departemen = $koneksi->query("SELECT * FROM departemen");

								if ($query_departemen) {
									while ($row = $query_departemen->fetch_assoc()) {
										$selected = ($row['id'] == $data['departemen']) ? 'selected' : '';
										echo "<option value='{$row['id']}' $selected>{$row['nama']}</option>";
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
                                <?php
                                // Define an array with the Roman numerals
                                $romawi_options = ['I', 'II', 'III', 'IV'];

                                // Loop through the array and generate option tags
                                foreach ($romawi_options as $romawi) {
                                    $selected = ($data['angka_romawi'] == $romawi) ? 'selected' : '';
                                    echo "<option value='$romawi' $selected>$romawi</option>";
                                }
                                ?>
                            </select>
                        </div>

						<!-- Kode Barang (automatically generated) -->
						<label for="">Kode Barang</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" id="generated_code" class="form-control" value="<?php echo htmlspecialchars($data['code_barang']); ?>" disabled />
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

							// Fetch department name to generate the code
							$query_departemen = $koneksi->query("SELECT nama FROM departemen WHERE id = '$departemen_id'");

							if ($query_departemen && $query_departemen->num_rows > 0) {
								$departemen = $query_departemen->fetch_assoc();
								$departemen_kode = strtoupper(substr($departemen['nama'], 0, 2)); // First two letters of department
								$new_code_barang = "SF/$departemen_kode/$angka_romawi"; // Generate new code

								// Check if the updated 'jenis_barang' is unique
								$query_check = $koneksi->query("SELECT * FROM jenis_barang WHERE jenis_barang = '$jenis_barang' AND id != '$id'");

								if ($query_check->num_rows == 0) {
									// Update the data in the jenis_barang table
									$sql_update = $koneksi->query("UPDATE jenis_barang SET jenis_barang = '$jenis_barang', code_barang = '$new_code_barang', departemen = '$departemen_id', angka_romawi = '$angka_romawi' WHERE id = '$id'");

									if ($sql_update) {
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
					} else {
						// Redirect if no ID is found in the URL
						echo "<script>window.location.href = '?page=jenisbarang';</script>";
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
