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
    $sql = "SELECT * FROM barang_keluar WHERE id = ?";
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
            window.location.href='?page=barangkeluar';
        </script>";
        exit;
    }
} else {
    echo "<script>
        alert('ID tidak valid!');
        window.location.href='?page=barangkeluar';
    </script>";
    exit;
}

// Proses simpan perubahan
if (isset($_POST['simpan'])) {
    try {
        $koneksi->begin_transaction();

        $id = (int)$_GET['id'];
        $jumlah_baru = (int)$_POST['jumlah'];
        
        // Ambil data barang keluar yang lama
        $sql_old = "SELECT kode_barang, jumlah FROM barang_keluar WHERE id = ?";
        $stmt_old = $koneksi->prepare($sql_old);
        $stmt_old->bind_param("i", $id);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        
        if ($result_old && $data_old = $result_old->fetch_assoc()) {
            $jumlah_lama = (int)$data_old['jumlah'];
            $kode_barang = $data_old['kode_barang'];

            // Debug: Tampilkan kode barang yang dicari
            error_log("Mencari barang dengan kode: " . $kode_barang);

            // Cek stok di gudang dengan LIKE untuk mencocokkan kode barang
            $sql_stok = "SELECT jumlah FROM gudang WHERE kode_barang LIKE CONCAT(?, '%')";
            $stmt_stok = $koneksi->prepare($sql_stok);
            $kode_base = explode('-', $kode_barang)[0]; // Ambil bagian dasar kode (SF/IT/III)
            $stmt_stok->bind_param("s", $kode_base);
            $stmt_stok->execute();
            $result_stok = $stmt_stok->get_result();
            
            if ($result_stok && $data_stok = $result_stok->fetch_assoc()) {
                $stok_gudang = (int)$data_stok['jumlah'];
                
                // Debug: Tampilkan stok yang ditemukan
                error_log("Stok ditemukan: " . $stok_gudang);
                
                // Hitung stok yang tersedia
                $stok_sekarang = $stok_gudang + $jumlah_lama;

                // Validasi stok mencukupi
                if ($stok_sekarang >= $jumlah_baru) {
                    // Update stok di gudang
                    $stok_akhir = $stok_sekarang - $jumlah_baru;
                    $sql_update_gudang = "UPDATE gudang 
                                        SET jumlah = ? 
                                        WHERE kode_barang LIKE CONCAT(?, '%')";
                    $stmt_gudang = $koneksi->prepare($sql_update_gudang);
                    $stmt_gudang->bind_param("is", $stok_akhir, $kode_base);
                    $stmt_gudang->execute();

                    // Update data barang keluar
                    $sql_update = "UPDATE barang_keluar 
                                SET jumlah = ? 
                                WHERE id = ?";
                    $stmt_update = $koneksi->prepare($sql_update);
                    $stmt_update->bind_param("ii", $jumlah_baru, $id);
                    $stmt_update->execute();

                    $koneksi->commit();
                    echo "<script>
                        alert('Data berhasil diubah!');
                        window.location.href='?page=barangkeluar';
                    </script>";
                } else {
                    throw new Exception("Stok tidak mencukupi! Stok tersedia: " . $stok_sekarang);
                }
            } else {
                throw new Exception("Data barang dengan kode " . $kode_barang . " tidak ditemukan di gudang!");
            }
        } else {
            throw new Exception("Data barang keluar tidak ditemukan!");
        }
    } catch (Exception $e) {
        $koneksi->rollback();
        echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
        </script>";
    }
}
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Barang Keluar</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div class="body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Kode Barang</label>
                            <input type="text" class="form-control" name="kode_barang" 
                                   value="<?php echo $kode_barang; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" class="form-control" name="nama_barang" 
                                   value="<?php echo $nama_barang; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Jumlah</label>
                            <input type="number" class="form-control" name="jumlah" id="jumlahkeluar" 
                                   value="<?php echo $jumlah; ?>" min="1">
                        </div>

                        <div class="form-group">
                            <label>Satuan</label>
                            <input type="text" class="form-control" name="satuan" 
                                   value="<?php echo $satuan; ?>" readonly>
                        </div>

                        <button type="submit" name="simpan" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>