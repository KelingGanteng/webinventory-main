<!-- Begin Page Content -->
<div class="container-fluid">
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Barang Masuk</h6>
		</div>
		<div class="card-body">
			<!-- Tombol Tambah Barang -->
			<div class="mb-3">
				<a href="?page=barangmasuk&aksi=tambahbarangmasuk" class="btn btn-primary custom-btn">
					<i class="fas fa-plus me-2"></i> Tambah Barang
				</a>
			</div>

			<!-- Filter Tanggal -->
			<div class="row mb-3">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-text"><i class="fas fa-calendar-alt"></i> Dari</span>
						<input type="date" id="min" name="min" class="form-control">
						<span class="input-group-text"><i class="fas fa-calendar-alt"></i> Sampai</span>
						<input type="date" id="max" name="max" class="form-control">
					</div>
				</div>
			</div>

			<!-- Tabel Barang Masuk -->
			<div class="table-responsive">
				<table class="table table-bordered" id="barangmasuk" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th style="width: 50px; text-align: center; vertical-align: middle;">No</th>
							<th style="width: 150px; text-align: center; vertical-align: middle;">Id Transaksi</th>
							<th style="width: 150px; text-align: center; vertical-align: middle;">Tanggal Masuk</th>
							<th style="width: 150px; text-align: center; vertical-align: middle;">Kode Barang</th>
							<th style="width: 200px; text-align: center; vertical-align: middle;">Nama Barang</th>
							<th style="width: 100px; text-align: center; vertical-align: middle;">Kondisi</th>
							<th style="width: 150px; text-align: center; vertical-align: middle;">Jumlah Masuk</th>
							<th style="width: 100px; text-align: center; vertical-align: middle;">Satuan</th>
							<th style="width: 150px; text-align: center; vertical-align: middle;">Pengaturan</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						$sql = $koneksi->query("SELECT barang_masuk.*, gudang.satuan, gudang.nama_barang 
							FROM barang_masuk 
							INNER JOIN gudang ON barang_masuk.kode_barang = gudang.kode_barang");
						while ($data = $sql->fetch_assoc()) {
							?>
							<tr>
								<td style="text-align: center;"><?php echo $no++; ?></td>
								<td style="text-align: center;"><?php echo $data['id_transaksi']; ?></td>
								<td style="text-align: center;"><?php echo $data['tanggal']; ?></td>
								<td style="text-align: center;"><?php echo $data['kode_barang']; ?></td>
								<td style="text-align: center;"><?php echo $data['nama_barang']; ?></td>
								<td style="text-align: center;"><?php echo $data['kondisi']; ?></td>
								<td style="text-align: center;"><?php echo $data['jumlah']; ?></td>
								<td style="text-align: center;"><?php echo $data['satuan']; ?></td>
								<td style="text-align: center;">
									<a href="?page=barangmasuk&aksi=ubahbarangmasuk&id_transaksi=<?php echo $data['id_transaksi']; ?>"
										class="btn btn-info btn-sm custom-btn">
										<i class="fas fa-edit"></i> Edit
									</a>
									<a href="?page=barangmasuk&aksi=hapusbarangmasuk&id_transaksi=<?php echo $data['id_transaksi']; ?>"
										class="btn btn-danger btn-sm custom-btn"
										onclick="return confirm('Apakah anda yakin akan menghapus data ini?')">
										<i class="fas fa-trash"></i> Hapus
									</a>

								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- DataTable Initialization Script -->
<!-- JavaScript untuk DataTable dan Filter Tanggal -->
<script>
	$(document).ready(function () {
		var table = $('#barangmasuk').DataTable({
			dom: 'Bfrtip',
			buttons: [
				{
					extend: 'copy',
					text: '<i class="fas fa-copy"></i> Copy',
					className: 'btn btn-secondary btn-sm'
				},
				{
					extend: 'csv',
					text: '<i class="fas fa-file-csv"></i> CSV',
					className: 'btn btn-secondary btn-sm'
				},
				{
					extend: 'excel',
					text: '<i class="fas fa-file-excel"></i> Excel',
					className: 'btn btn-secondary btn-sm'
				},
				{
					extend: 'pdf',
					text: '<i class="fas fa-file-pdf"></i> PDF',
					className: 'btn btn-secondary btn-sm'
				},
				{
					extend: 'print',
					text: '<i class="fas fa-print"></i> Print',
					className: 'btn btn-secondary btn-sm'
				}
			],
			language: {
				search: "Cari:",
				lengthMenu: "Tampilkan _MENU_ data per halaman",
				zeroRecords: "Data tidak ditemukan",
				info: "Menampilkan halaman _PAGE_ dari _PAGES_",
				infoEmpty: "Tidak ada data yang tersedia",
				infoFiltered: "(difilter dari _MAX_ total data)",
				paginate: {
					first: "Pertama",
					last: "Terakhir",
					next: "Selanjutnya",
					previous: "Sebelumnya"
				}
			},
			order: [[1, 'asc']], // Urutkan berdasarkan ID Transaksi
			pageLength: 10 // Jumlah data per halaman
		});

		$.fn.dataTable.ext.search.push(
			function (settings, data, dataIndex) {
				var min = $('#min').val();
				var max = $('#max').val();
				var date = data[2]; // Kolom Tanggal Keluar

				if (min === "" && max === "") return true;
				if (min === "") return date <= max;
				if (max === "") return date >= min;
				return date >= min && date <= max;
			}
		);

		$('#min, #max').on('change', function () {
			table.draw();
		});
	});
</script>

<!-- Tooltip Initialization (Bootstrap 5) -->
<script>
	var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
		return new bootstrap.Tooltip(tooltipTriggerEl)
	})
</script>

<!-- Additional Styles -->
<style>
	/* Custom button styling */
	.custom-btn {
		background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
		color: white;
		border: none;
		box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
		transition: transform 0.2s ease, box-shadow 0.2s ease;
	}

	/* Button hover effect */
	.custom-btn:hover {
		transform: scale(1.05);
		box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
	}
</style>
