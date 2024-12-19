<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Periksa apakah koneksi berhasil
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Periksa apakah ada ID yang dikirim
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Ambil data transaksi
    $sql = "SELECT * FROM barang_masuk WHERE id = ?";
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data_transaksi = $result->fetch_assoc();
        
        // Ambil data barang
        $kode_barang = $data_transaksi['kode_barang'];
        $nama_barang = $data_transaksi['nama_barang'];
        $jumlah = $data_transaksi['jumlah'];
        $satuan = $data_transaksi['satuan'];
    } else {
        echo "<script>
            alert('Data transaksi tidak ditemukan!');
            window.location.href='?page=barangmasuk';
        </script>";
        exit;
    }
} else {
    echo "<script>
        alert('ID tidak valid!');
        window.location.href='?page=barangmasuk';
    </script>";
    exit;
}

// Proses simpan perubahan
if (isset($_POST['simpan'])) {
    try {
        $koneksi->begin_transaction();

        $id = (int)$_GET['id'];
        $jumlah_baru = (int)$_POST['jumlah'];
        
        // Ambil data barang masuk yang lama
        $sql_old = "SELECT jumlah FROM barang_masuk WHERE id = ?";
        $stmt_old = $koneksi->prepare($sql_old);
        $stmt_old->bind_param("i", $id);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        $data_old = $result_old->fetch_assoc();
        $jumlah_lama = (int)$data_old['jumlah'];

        // Update stok di gudang
        $selisih = $jumlah_baru - $jumlah_lama;
        $sql_update_gudang = "UPDATE gudang 
                             SET jumlah = CAST(jumlah AS SIGNED) + ? 
                             WHERE kode_barang = ?";
        $stmt_gudang = $koneksi->prepare($sql_update_gudang);
        $stmt_gudang->bind_param("is", $selisih, $kode_barang);
        $stmt_gudang->execute();

        // Update data barang masuk
        $sql_update = "UPDATE barang_masuk 
                       SET jumlah = ? 
                       WHERE id = ?";
        $stmt_update = $koneksi->prepare($sql_update);
        $stmt_update->bind_param("ii", $jumlah_baru, $id);
        $stmt_update->execute();

        $koneksi->commit();
        echo "<script>
            alert('Data berhasil diubah!');
            window.location.href='?page=barangmasuk';
        </script>";
    } catch (Exception $e) {
        $koneksi->rollback();
        echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
        </script>";
    }
}
?>

<script>
    function sum() {
        var stok = document.getElementById('stok').value;
        var jumlahmasuk = document.getElementById('jumlahmasuk').value;
        var result = parseInt(stok) - parseInt(jumlahmasuk);
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
                url: 'get_satuan1.php',
                data: { tamp: tamp },
                success: function (response) {
                    $('.tampung1').html(response);
                }
            });
        });
    });
</script>
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
<!-- Form Edit -->
<div class="form-group">
    <label>Kode Barang</label>
    <input type="text" class="form-control" name="kode_barang" value="<?php echo $kode_barang; ?>" readonly>
</div>

<div class="form-group">
    <label>Nama Barang</label>
    <input type="text" class="form-control" name="nama_barang" value="<?php echo $nama_barang; ?>" readonly>
</div>

<div class="form-group">
    <label>Jumlah</label>
    <input type="number" class="form-control" name="jumlah" id="jumlahmasuk" 
           value="<?php echo $jumlah; ?>" onkeyup="sum();">
</div>

<div class="form-group">
    <label>Satuan</label>
    <input type="text" class="form-control" name="satuan" value="<?php echo $satuan; ?>" readonly>
</div>

<button type="submit" name="simpan" class="btn btn-primary">
        <i class="fa fa-save"></i> Simpan
    </button>
</form>