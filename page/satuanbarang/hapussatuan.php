<?php
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
if (!isset($_GET['id'])) {
	echo "<script>
        alert('ID tidak ditemukan!');
        window.location.href='?page=satuanbarang';
    </script>";
	exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil nama satuan untuk konfirmasi
$check = $koneksi->query("SELECT satuan FROM satuan WHERE id='$id'");
$data = $check->fetch_assoc();

if ($data) {
	$sql = $koneksi->query("DELETE FROM satuan WHERE id='$id'");

	if ($sql) {
		echo "<script>
		Swal.fire({
			icon: 'success',
			title: 'Berhasil',
			text: 'Data satuan barang berhasil dihapus',
			showConfirmButton: false,
			timer: 1500
		}).then(function() {
			window.location.href = '?page=satuanbarang';
		});
	  </script>";
	} else {
		echo "<script>
            alert('Gagal menghapus data: " . $koneksi->error . "');
            window.location.href='?page=satuanbarang';
        </script>";
	}
} else {
	echo "<script>
        alert('Data tidak ditemukan!');
        window.location.href='?page=satuanbarang';
    </script>";
}
?>