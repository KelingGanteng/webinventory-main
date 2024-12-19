<script>
$(document).ready(function() {
	
	// Ketika barang dipilih
    $('#cmb_barang').on('change', function() {
		var selectedOption = $(this).find('option:selected');
        var kode_barang = selectedOption.data('kode');
        var stok = selectedOption.data('stok');
        
        $('#kode_barang').val(kode_barang);
        $('#stok').val(stok);
        updateTotalStok();

		$('#cmb_barang').select2({
			placeholder: "-- Pilih Barang --",
			allowClear: true,
			width: '100%',
			minimumInputLength: 2
		});
    });
});

function updateTotalStok() {
    var stok = parseInt($('#stok').val()) || 0;
    var jumlahmasuk = parseInt($('#jumlahmasuk').val()) || 0;
    var totalStok = stok + jumlahmasuk;
    $('#jumlah').val(totalStok);
}
</script>

<?php

$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Periksa apakah koneksi berhasil
if ($koneksi->connect_error) {
	die("Koneksi gagal: " . $koneksi->connect_error);
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
								<select name="barang" id="cmb_barang" class="form-control select2" onchange="updateTotalStok()">
									<option value="">-- Pilih Barang --</option>
									<?php
									// Query sesuai dengan struktur tabel
									$sql = $koneksi->query("SELECT id, kode_barang, nama_barang, jumlah 
														FROM gudang 
														WHERE nama_barang IS NOT NULL 
														ORDER BY nama_barang");
									
									if (!$sql) {
										echo "Error: " . $koneksi->error;
									} else {
										while ($data = $sql->fetch_assoc()) {
											// Pastikan data tidak NULL
											$id = $data['id'] ?? '';
											$kode = htmlspecialchars($data['kode_barang'] ?? '');
											$nama = htmlspecialchars($data['nama_barang'] ?? '');
											$jumlah = htmlspecialchars($data['jumlah'] ?? '0');
											
											if (!empty($nama)) {
												echo "<option value='$id' 
															data-kode='$kode'
															data-stok='$jumlah'>" . 
													"$nama - $kode</option>";
											}
										}
									}
									?>
								</select>
							</div>
						</div>

						<!-- Debug info -->
						<div style="display:none">
						<?php
							$debug_query = "SELECT COUNT(*) as total FROM gudang WHERE nama_barang IS NOT NULL";
							$result = $koneksi->query($debug_query);
							$row = $result->fetch_assoc();
							echo "Total records: " . $row['total'];
							
							// Tampilkan beberapa data pertama
							$sample = $koneksi->query("SELECT * FROM gudang LIMIT 3");
							while($row = $sample->fetch_assoc()) {
								echo "<pre>";
								print_r($row);
								echo "</pre>";
							}
						?>
						</div>

						<div class="form-group">
						<label for="stok">Stok Saat Ini</label>
						<input type="number" id="stok" name="stok" class="form-control" readonly />
					</div>

						<div class="form-group">
						<label for="kode_barang">Kode Barang</label>
						<input type="text" id="kode_barang" name="kode_barang" class="form-control" readonly />
					</div>





						<div class="tampung"></div>


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
						try {
							$koneksi->begin_transaction();

							$tanggal = $_POST['tanggal_masuk'];
							$barang_id = (int)$_POST['barang'];
							$jumlah_masuk = (int)$_POST['jumlahmasuk'];
							$satuan = $_POST['satuan'];

							// Ambil data barang berdasarkan ID
							$get_barang = $koneksi->query("SELECT kode_barang, nama_barang, jumlah 
														FROM gudang 
														WHERE id = $barang_id");
							
							if ($get_barang && $get_barang->num_rows > 0) {
								$barang_data = $get_barang->fetch_assoc();
								$kode_barang = $barang_data['kode_barang'];
								$nama_barang = $barang_data['nama_barang'];
								$stok_sekarang = (int)$barang_data['jumlah'];

								// Update stok di gudang
								$sql_update = "UPDATE gudang 
											SET jumlah = ? 
											WHERE id = ?";
								
								$stok_baru = $stok_sekarang + $jumlah_masuk;
								
								$stmt_update = $koneksi->prepare($sql_update);
								$stmt_update->bind_param("ii", $stok_baru, $barang_id);
								$stmt_update->execute();

								// Insert ke barang_masuk
								$sql_insert = "INSERT INTO barang_masuk 
											(tanggal, kode_barang, nama_barang, jumlah, satuan) 
											VALUES (?, ?, ?, ?, ?)";
								
								$stmt_insert = $koneksi->prepare($sql_insert);
								$stmt_insert->bind_param("sssis", 
									$tanggal, 
									$kode_barang, 
									$nama_barang, 
									$jumlah_masuk, 
									$satuan
								);
								$stmt_insert->execute();

								$koneksi->commit();
								echo "<script>
									alert('Data berhasil disimpan!');
									window.location.href='?page=barangmasuk';
								</script>";
							} else {
								throw new Exception("Barang tidak ditemukan!");
							}
						} catch (Exception $e) {
							$koneksi->rollback();
							echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
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