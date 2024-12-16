<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Tambah Stok</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<div class="body">
					<form method="POST" enctype="multipart/form-data">
						<label for="">Jenis Barang</label>
						<div class="form-group">
							<div class="form-line">
								<select name="jenis_barang" id="jenis_barang" class="form-control" required>
									<option value="">-- Pilih Jenis Barang --</option>
									<?php
									$sql = $koneksi->query("SELECT * FROM jenis_barang ORDER BY jenis_barang");
									while ($data = $sql->fetch_assoc()) {
										echo "<option value='" . $data['jenis_barang'] . "' 
                                            data-kode='" . $data['code_barang'] . "'>" .
											$data['jenis_barang'] .
											"</option>";
									}
									?>
								</select>
							</div>
						</div>

						<label for="">Kode Barang</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="kode_barang" id="kode_barang" class="form-control" readonly
									required />
							</div>
						</div>

						<label for="">Nama Barang</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="nama_barang" class="form-control" required />
							</div>
						</div>


						<label for="jumlah">Jumlah</label>
						<div class="form-group">
							<div class="form-line">
								<input type="number" name="jumlah" class="form-control" style="max-width: 70px;"
									inputmode="numeric" min="0" step="1" value="0" readonly />
							</div>
						</div>



						<label for="">Satuan Barang</label>
						<div class="form-group">
							<div class="form-line">
								<select name="satuan" class="form-control" required>
									<option value="">-- Pilih Satuan Barang --</option>
									<?php
									$sql = $koneksi->query("SELECT * FROM satuan ORDER BY id");
									while ($data = $sql->fetch_assoc()) {
										echo "<option value='" . $data['satuan'] . "'>" .
											$data['satuan'] . "</option>";
									}
									?>
								</select>
							</div>
						</div>

						<input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
					</form>

					<?php
					if (isset($_POST['simpan'])) {
						$kode_barang = $_POST['kode_barang'];
						$nama_barang = $_POST['nama_barang'];
						$jenis_barang = $_POST['jenis_barang'];
						$jumlah = $_POST['jumlah'];
						$satuan = $_POST['satuan'];




						$cek = $koneksi->query("SELECT kode_barang FROM gudang WHERE kode_barang='$kode_barang'");
						if ($cek->num_rows > 0) {
							echo "<script>
								alert('Kode barang sudah ada di gudang!');
							</script>";
						} else {
							$sql = $koneksi->query("INSERT INTO gudang 
								(kode_barang, nama_barang, jenis_barang, jumlah, satuan) 
								VALUES 
								('$kode_barang', '$nama_barang', '$jenis_barang', '$jumlah', '$satuan')");

							if ($sql) {
								echo "<script>
									alert('Data Berhasil Disimpan');
									window.location.href='?page=gudang';
								</script>";
							} else {
								echo "<script>
									alert('Gagal menyimpan data: " . $koneksi->error . "');
								</script>";
							}
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.getElementById('jenis_barang').addEventListener('change', function () {
		var selectedOption = this.options[this.selectedIndex];
		var kodeBarang = selectedOption.getAttribute('data-kode');
		document.getElementById('kode_barang').value = kodeBarang;
	});
</script>