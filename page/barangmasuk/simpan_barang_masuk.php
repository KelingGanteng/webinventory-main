<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_barang = $_POST['kode_barang']; // kode_barang sebenarnya berisi id dari form select
    $tanggal_masuk = $_POST['tanggal_masuk'];
    $jumlah_masuk = $_POST['jumlah_masuk'];
    $keterangan = $_POST['keterangan'];

    // Mulai transaksi
    $koneksi->begin_transaction();

    try {
        // 1. Insert ke tabel barang_masuk
        $sql_insert = "INSERT INTO barang_masuk (tanggal_masuk, id_barang, jumlah_masuk, keterangan) 
                      VALUES (?, ?, ?, ?)";
        $stmt = $koneksi->prepare($sql_insert);
        
        if ($stmt === false) {
            throw new Exception("Error preparing insert statement: " . $koneksi->error);
        }
        
        $stmt->bind_param("siis", $tanggal_masuk, $id_barang, $jumlah_masuk, $keterangan);
        
        if (!$stmt->execute()) {
            throw new Exception("Error executing insert statement: " . $stmt->error);
        }

        // 2. Update stok di tabel gudang
        $sql_update = "UPDATE gudang SET jumlah = jumlah + ? WHERE id = ?";
        $stmt_update = $koneksi->prepare($sql_update);
        
        if ($stmt_update === false) {
            throw new Exception("Error preparing update statement: " . $koneksi->error);
        }
        
        $stmt_update->bind_param("ii", $jumlah_masuk, $id_barang);
        
        if (!$stmt_update->execute()) {
            throw new Exception("Error executing update statement: " . $stmt_update->error);
        }

        // Commit transaksi
        $koneksi->commit();
        
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data barang masuk berhasil disimpan',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = '?page=barang_masuk';
                });
              </script>";
    } catch (Exception $e) {
        // Rollback jika terjadi error
        $koneksi->rollback();
        
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan: " . addslashes($e->getMessage()) . "',
                    showConfirmButton: true
                });
              </script>";
    }
}
?>