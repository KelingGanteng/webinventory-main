<script>
	function sum() {
		var stok = document.getElementById('stok').value;
		var jumlahkeluar = document.getElementById('jumlahkeluar').value;
		var result = parseInt(stok) - parseInt(jumlahkeluar);
		if (!isNaN(result)) {
			document.getElementById('total').value = result;
		}
	}
	$(document).ready(function () {
		// Ketika barang dipilih
		$('#cmb_barang').change(function () {
			var tamp = $(this).val(); // Ambil nilai barang
			$.ajax({
				type: 'POST',
				url: 'get_satuan.php',  // Pastikan ini adalah file yang mengembalikan satuan
				data: { tamp: tamp },    // Kirimkan kode barang untuk mengambil satuan
				success: function (response) {
					// Masukkan response (HTML satuan) ke dalam div tampung
					$('.tampung').html(response);  // Tampilkan satuan di div
				}
			});
		});
	});

</script>

</script>

<?php

$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Cek koneksi ke database
if ($koneksi->connect_error) {
	die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query untuk mengambil id_transaksi terakhir dari tabel barang_keluar
$no = mysqli_query($koneksi, "SELECT id_transaksi FROM barang_keluar ORDER BY id_transaksi DESC LIMIT 1");
$idtran = mysqli_fetch_array($no);

// Jika tidak ada transaksi sebelumnya, inisialisasi kode transaksi pertama
if ($idtran) {
	$kode = $idtran['id_transaksi'];
} else {
	// Jika tidak ada data transaksi sebelumnya, buat kode transaksi pertama
	$kode = "TRK-" . date("m") . date("y") . "000"; // Format awal transaksi
}

// Mengambil angka urut dari id_transaksi terakhir
$urut = substr($kode, 8, 3);

// Tambah 1 pada angka urut
$tambah = (int) $urut + 1;

// Format bulan dan tahun
$bulan = date("m");
$tahun = date("y");

// Membuat format kode transaksi berdasarkan angka urut
if (strlen($tambah) == 1) {
	$format = "MIS-" . $bulan . $tahun . "00" . $tambah;
} else if (strlen($tambah) == 2) {
	$format = "MIS-" . $bulan . $tahun . "0" . $tambah;
} else {
	$format = "MIS-" . $bulan . $tahun . $tambah;
}

// Tanggal barang keluar
$tanggal_keluar = date("Y-m-d");

?>



<div class="container-fluid">

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Tambah Barang Keluar</h6>
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



						<label for="">Tanggal Keluar</label>
						<div class="form-group">
							<div class="form-line">
								<input type="date" name="tanggal_keluar" class="form-control" id="tanggal_kelauar"
									value="<?php echo $tanggal_keluar; ?>" />
							</div>
						</div>


						<label for="">Barang</label>
						<div class="form-group">
							<div class="form-line">
								<select name="barang" id="cmb_barang" class="form-control" />
								<option value="">-- Pilih Barang --</option>
								<?php

								$sql = $koneksi->query("select * from gudang order by kode_barang");
								while ($data = $sql->fetch_assoc()) {
									echo "<option value='$data[kode_barang].$data[nama_barang]'>$data[kode_barang] | $data[nama_barang]</option>";
								}
								?>

								</select>


							</div>
						</div>
						<div class="tampung"></div>


						<label for="karyawan">Pilih Karyawan</label>
						<div class="form-group">
							<div class="form-line">
								<select name="karyawan" id="karyawan" class="form-control">
									<option value="">-- Pilih Karyawan --</option>
									<?php
									// Query untuk mengambil data karyawan beserta departemennya
									$sql_karyawan = $koneksi->query("SELECT dk.id, dk.nama AS nama_karyawan, d.nama AS nama_departemen
                                              FROM daftar_karyawan dk
                                              LEFT JOIN departemen d ON dk.departemen_id = d.id
                                              ORDER BY dk.nama ASC");
									while ($data_karyawan = $sql_karyawan->fetch_assoc()) {
										echo "<option value='" . $data_karyawan['id'] . "'>";
										echo htmlspecialchars($data_karyawan['nama_karyawan']) . " (" . htmlspecialchars($data_karyawan['nama_departemen']) . ")";
										echo "</option>";
									}
									?>
								</select>
							</div>
						</div>

						<label for="departemen">Pilih Departemen</label>
						<div class="form-group">
							<div class="form-line">
								<select name="departemen" id="departemen" class="form-control">
									<option value="">-- Pilih Departemen --</option>
									<?php
									// Query untuk mengambil data departemen
									$sql_departemen = $koneksi->query("SELECT * FROM departemen ORDER BY nama ASC");
									while ($data_departemen = $sql_departemen->fetch_assoc()) {
										echo "<option value='" . $data_departemen['id'] . "'>";
										echo htmlspecialchars($data_departemen['nama']);
										echo "</option>";
									}
									?>
								</select>
							</div>
						</div>





						<label for="">Jumlah</label>
						<div class="form-group">
							<div class="form-line">
								<input type="number" name="jumlahkeluar" class="form-control" style="max-width: 70px;"
									inputmode="numeric" min="0" step="1" />
							</div>
						</div>



				</div>
			</div>

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

			<style>
				.checkbox-group {
					display: flex;
					flex-wrap: wrap;
					/* Membuat checkbox bisa berada dalam baris yang sama atau dibungkus jika panjang */
				}

				.checkbox-group label {
					display: inline-block;
					margin-right: 20px;
					/* Memberi jarak antar checkbox */
					font-size: 14px;
					/* Menjaga ukuran font agar tetap konsisten */
				}

				.checkbox-group input {
					margin-right: 5px;
					/* Memberikan sedikit jarak antara checkbox dan teks label */
				}
			</style>



			<label for="total">Total Stok</label>
			<div class="form-group">
				<div class="form-line">
					<input readonly="readonly" name="jumlah" id="jumlah" type="number" class="form-control">


				</div>
			</div>

			<div class="tampung1"></div>





			<input type="submit" name="simpan" value="Simpan" class="btn btn-primary">

			</form>



			<?php

			if (isset($_POST['simpan'])) {
				$id_transaksi = $_POST['id_transaksi'];
				$tanggal = $_POST['tanggal_keluar'];
				$karyawan_id = $_POST['karyawan'];  // Ambil id karyawan yang dipilih
			
				// Pisahkan kode barang dan nama barang
				$barang = $_POST['barang'];
				$pecah_barang = explode(".", $barang);
				$kode_barang = $pecah_barang[0];
				$nama_barang = $pecah_barang[1];

				// Ambil kondisi yang dipilih
				$kondisi = isset($_POST['kondisi']) ? implode(", ", $_POST['kondisi']) : ''; // Gabungkan kondisi yang dipilih dengan koma
			
				$jumlah = $_POST['jumlahkeluar'];
				$satuan = $_POST['satuan'];  // Ambil satuan yang dipilih
				$departemen_id = $_POST['departemen'];  // Ambil id departemen yang dipilih
			

				// Ambil stok dari gudang untuk barang yang dipilih
				$sql_stok = $koneksi->query("SELECT jumlah FROM gudang WHERE kode_barang = '$kode_barang'");
				$stok_data = $sql_stok->fetch_assoc();
				$stok_tersedia = $stok_data['jumlah'];

				// Cek apakah jumlah keluar lebih besar dari stok yang tersedia
				if ($jumlah > $stok_tersedia) {
					echo "<script>alert('Jumlah keluar melebihi stok yang tersedia!'); window.location.href = '?page=barangkeluar&aksi=tambahbarangkeluar';</script>";
				} else {
					// Proses simpan data barang keluar
					$total = $stok_tersedia - $jumlah;  // Hitung stok sisa setelah barang keluar
			
					// Simpan transaksi barang keluar
					$sql = $koneksi->query("INSERT INTO barang_keluar (id_transaksi, tanggal, kode_barang, nama_barang, kondisi, jumlah, satuan, karyawan_id, departemen_id) 
					VALUES('$id_transaksi', '$tanggal', '$kode_barang', '$nama_barang', '$kondisi', '$jumlah', '$satuan', '$karyawan_id', '$departemen_id')");


					// Update stok di gudang setelah transaksi
					$sql2 = $koneksi->query("UPDATE gudang SET jumlah = $total WHERE kode_barang = '$kode_barang'");

					// Tampilkan pesan berhasil dan redirect
					echo "<script>alert('Simpan Data Berhasil'); window.location.href = '?page=barangkeluar';</script>";
				}
			}
			?>