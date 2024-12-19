<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Hanya satu fungsi sum()
function sum() {
    var stok = document.getElementById('stok').value;
    var jumlahmasuk = document.getElementById('jumlahmasuk').value;
    var result = parseInt(stok) + parseInt(jumlahmasuk);
    if (!isNaN(result)) {
        document.getElementById('jumlah').value = result;
    }
}
</script>
  




  <?php 

$koneksi = new mysqli("localhost", "root", "", "webinventory");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query untuk mendapatkan id_transaksi terakhir
$no = mysqli_query($koneksi, "SELECT id_transaksi FROM barang_masuk ORDER BY id_transaksi DESC LIMIT 1");

if ($no && mysqli_num_rows($no) > 0) {
    $idtran = mysqli_fetch_array($no);
    $kode = $idtran['id_transaksi'];
    
    $urut = substr($kode, 8, 3);
    $tambah = (int) $urut + 1;
} else {
    // Jika belum ada data, mulai dari 1
    $tambah = 1;
}

$bulan = date("m");
$tahun = date("y");

// Format kode transaksi
if (strlen($tambah) == 1) {
    $format = "MIS-" . $bulan . $tahun . "00" . $tambah;
} else if (strlen($tambah) == 2) {
    $format = "MIS-" . $bulan . $tahun . "0" . $tambah;
} else {
    $format = "MIS-" . $bulan . $tahun . $tambah;
}
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
									 <input type="date" name="tanggal_masuk" class="form-control" id="tanggal_masuk" value="<?php echo $tanggal_masuk; ?>" />
								</div>
								</div>

								<div class="mb-3">
								<label for="nama_barang" class="form-label">Barang</label>
								<select class="form-control" id="nama_barang" name="nama_barang" required>
									<option value="">Pilih Nama Barang</option>
									<?php
									// Ambil data Nama Barang dari tabel gudang
									$sql = $koneksi->query("SELECT * FROM gudang ORDER BY nama_barang");
									while ($data = $sql->fetch_assoc()) {
										echo "<option value='" . htmlspecialchars($data['nama_barang']) . "'>" . htmlspecialchars($data['nama_barang']) . "</option>";
									}
									?>
								</select>
							</div>
								<label for="">Id Transaksi</label>
								<div class="form-group">
								<div class="form-line">
									<input type="text" name="id_transaksi" class="form-control" id="id_transaksi" value="<?php echo $format; ?>" readonly /> 
								</div>
								</div>
							
						
											<div class="mb-3">
									<label for="jumlahmasuk" class="form-label">Jumlah</label>
									<input type="number" class="form-control" id="jumlahmasuk" onkeyup="sum()"name="jumlahmasuk" placeholder="Masukan Jumlah Barang Masuk" min="1" required>
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
							
							
					



									 
							</div>
                            </div>
							
							<div class="tampung"></div>
					
				
				<input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
			</div>
                            </div>

                            </div>
							
							<div class="tampung1"></div>
					
						
						
							
							
							</form>

							<?php
							if (isset($_POST['simpan'])) {
								$nama_barang = $_POST['nama_barang']; // Nama barang yang dipilih
								$jumlah_masuk = (int) $_POST['jumlahmasuk'];
								$satuan = $_POST['satuan'];
							
								// Ambil data dari tabel gudang berdasarkan nama_barang
								$query_gudang = $koneksi->query("SELECT * FROM gudang WHERE nama_barang = '$nama_barang'");
								$data_gudang = $query_gudang->fetch_assoc();
							
								if ($data_gudang) {
									$kode_barang = $data_gudang['kode_barang']; // Diambil untuk referensi jika diperlukan
									$stok_sekarang = (int) $data_gudang['jumlah'];
							
									// Hitung stok baru
									$stok_baru = $stok_sekarang + $jumlah_masuk;
							
									// Simpan ke tabel barang_masuk
									$sql_insert = $koneksi->query("INSERT INTO barang_masuk (id_transaksi, tanggal, nama_barang, jumlah, satuan) 
																   VALUES ('$format', '$tanggal_masuk', '$nama_barang', '$jumlah_masuk', '$satuan')");
							
									if ($sql_insert) {
										// Update stok di tabel gudang
										$koneksi->query("UPDATE gudang SET jumlah = '$stok_baru' WHERE nama_barang = '$nama_barang'");
							
										echo "<script>
											alert('Data berhasil disimpan!');
											window.location.href='?page=barangmasuk';
										</script>";
									} else {
										echo "<script>alert('Gagal menyimpan data.');</script>";
									}
								} else {
									echo "<script>alert('Nama barang tidak ditemukan.');</script>";
								}
							}
							
							?>

										
								
										
										
								
										
								
								
								
							
									
							
								
								
								
								
								
