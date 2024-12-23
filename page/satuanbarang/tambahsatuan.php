<div class="container-fluid">

	<!-- DataTales Example -->
	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">Tambah Satuan Barang</h6>
		</div>
		<div class="card-body">
			<div class="table-responsive">


				<div class="body">

					<form method="POST" enctype="multipart/form-data">


						<label for="">Satuan Barang</label>
						<div class="form-group">
							<div class="form-line">
								<input type="text" name="satuan" class="form-control" />
							</div>
						</div>



						<input type="submit" name="simpan" value="Simpan" class="btn btn-primary">

					</form>




					<?php
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

					if (isset($_POST['simpan'])) {
						$satuan = $_POST['satuan'];

						if (empty($satuan)) {
							?>
							<script type="text/javascript">
								alert("Satuan barang tidak boleh kosong!");
							</script>
							<?php
						} else {
							$sql = $koneksi->query("INSERT INTO satuan (satuan) VALUES('$satuan')");

							if ($sql) {
								echo "<script>
								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: 'Data satuan barang berhasil disimpan',
									showConfirmButton: false,
									timer: 1500
								}).then(function() {
									window.location.href = '?page=satuanbarang';
								});
							  </script>"; 
							}
						}
					}



					?>