<script>
	function sum() {
		var stok = document.getElementById('stok').value;
		var jumlahmasuk = document.getElementById('jumlahmasuk').value;
		var result = parseInt(stok) + parseInt(jumlahmasuk);
		if (!isNaN(result)) {
			document.getElementById('jumlah').value = result;
		}
	}

	function updateTotalStok() {
		var stok = parseInt(document.getElementById('stok').value) || 0;
		var jumlahmasuk = parseInt(document.getElementById('jumlahmasuk').value) || 0;
		var totalStok = stok + jumlahmasuk;
		document.getElementById('jumlah').value = totalStok;
	}

</script>

<?php

$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Periksa apakah koneksi berhasil
if ($koneksi->connect_error) {
	die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query untuk mendapatkan id_transaksi terakhir
$no = mysqli_query($koneksi, "SELECT id_transaksi FROM barang_masuk ORDER BY id_transaksi DESC LIMIT 1");

// Periksa apakah query berhasil dan ada hasil
if ($no && mysqli_num_rows($no) > 0) {
	// Ambil data id_transaksi terakhir
	$idtran = mysqli_fetch_array($no);
	$kode = $idtran['id_transaksi'];

	// Ambil urutan nomor transaksi
	$urut = substr($kode, 8, 3);
	$tambah = (int) $urut + 1;
} else {
	// Jika tidak ada data, mulai dari urutan 001
	$tambah = 1;
}

// Format nomor transaksi
$bulan = date("m");
$tahun = date("y");

if (strlen($tambah) == 1) {
	$format = "MIS-" . $bulan . $tahun . "00" . $tambah;
} else if (strlen($tambah) == 2) {
	$format = "MIS-" . $bulan . $tahun . "0" . $tambah;
} else {
	$format = "MIS-" . $bulan . $tahun . $tambah;
}

// Tanggal masuk
$tanggal_masuk = date("Y-m-d");

?>
<div class="container-fluid">

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Tambah Barang Masuk</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">


				<div class="body">

					<form method="POST" enctype="multipart/form-data">

						<label for="">Id Transaksi</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="id_transaksi" class="form-control" id="id_transaksi"
									value="<?php echo $format; ?>" readonly />
							</div>
						</div>



						<label for="">Tanggal Masuk</label>
						<div class="form-group">
							<div class="form-line">
								<input type="date" name="tanggal_masuk" class="form-control" id="tanggal_masuk"
									value="<?php echo $tanggal_masuk; ?>" />
							</div>
						</div>


						<!-- Input Barang -->
						<label for="">Barang</label>
						<div class="form-group">
							<div class="form-line">
								<select name="barang" id="cmb_barang" class="form-control" onchange="updateTotalStok()">
									<option value="">-- Pilih Barang --</option>
									<?php
									$sql = $koneksi->query("SELECT * FROM gudang ORDER BY kode_barang");
									while ($data = $sql->fetch_assoc()) {
										echo "<option value='$data[kode_barang].$data[nama_barang]' " .
											($barang == $data['kode_barang'] ? "selected" : "") . ">$data[kode_barang] | $data[nama_barang]</option>";
									}
									?>
								</select>
							</div>
						</div>

						<!-- Inisialisasi Select2 -->
						<script>
							$(document).ready(function () {
								// Mengaktifkan Select2 pada elemen dengan id cmb_barang
								$('#cmb_barang').select2({
									placeholder: "-- Pilih Barang --",
									allowClear: true,
									width: '100%',           // Menyesuaikan lebar dropdown
									minimumInputLength: 2,   // Set minimum karakter untuk mulai pencarian
									maximumSelectionLength: 5 // Batasi jumlah pilihan yang dapat dipilih
								});

							});
						</script>


						<div class="tampung"></div>
						<label for="kondisi">Kondisi</label>
						<div class="form-group">
							<div class="form-line">
								<!-- Menampilkan checkbox dalam format sederhana -->
								<div class="checkbox-group">
									<label><input type="checkbox" name="kondisi[]" value="Baik" /> Baik</label>
									<label><input type="checkbox" name="kondisi[]" value="Rusak" /> Rusak</label>
									<label><input type="checkbox" name="kondisi[]" value="Bekas" /> Bekas</label>
								</div>
							</div>
						</div>

						<label for="jumlah">Jumlah</label>
						<div class="form-group">
							<div class="form-line">
								<input type="number" name="jumlahmasuk" class="form-control" style="max-width: 70px;"
									inputmode="numeric" min="0" step="1" id="jumlahmasuk"
									onchange="updateTotalStok()" />
							</div>
						</div>


						<label for="jumlah">Total Stok</label>
						<div class="form-group">
							<div class="form-line">
								<!-- Menampilkan stok yang ada di gudang + jumlah yang dimasukkan -->
								<input readonly="readonly" name="jumlah" id="jumlah" type="number" class="form-control"
									value="<?php echo $stok_gudang; ?>" />
							</div>
						</div>
						<div class="tampung1"></div>



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
						$jumlah = $_POST['jumlahmasuk'];
						$satuan = $_POST['satuan'];
						$satuan = $_POST['satuan'];
						$kondisi = isset($_POST['kondisi']) ? implode(", ", $_POST['kondisi']) : ''; // Gabungkan kondisi yang dipilih dengan koma
					
						// Simpan data barang masuk
						$sql = $koneksi->query("INSERT INTO barang_masuk (id_transaksi, tanggal, kode_barang, nama_barang, jumlah, satuan, kondisi) 
                                            VALUES ('$id_transaksi', '$tanggal', '$kode_barang', '$nama_barang', '$jumlah', '$satuan', '$kondisi')");
						if ($sql) {
							echo "<script>alert('Data berhasil disimpan!'); window.location.href='?page=barangmasuk';</script>";
						} else {
							echo "<script>alert('Gagal menyimpan data!');</script>";
						}
					}
					?>
				</div>
			</div>
		</div>

		<head>
			<!-- Link CSS Select2 -->
			<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

			<!-- Link JS Select2 -->
			<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

		</head>
	</div>

	<?php
	if (isset($_GET['barang'])) {
		$barang = $_GET['barang'];
		$kode_barang = explode('.', $barang)[0]; // Ambil kode barang dari parameter
	
		// Ambil stok barang yang ada di gudang
		$sql_stok = $koneksi->query("SELECT stok FROM gudang WHERE kode_barang = '$kode_barang'");
		$data_stok = $sql_stok->fetch_assoc();
		$stok_gudang = $data_stok['stok'] ?? 0; // Ambil stok, jika tidak ada, default ke 0
	} else {
		$stok_gudang = 0; // Default stok 0 jika barang belum dipilih
	}
	?>