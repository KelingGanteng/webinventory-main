<?php
// Pastikan SweetAlert2 sudah di-include
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
if (isset($_GET['id'])) {
    $id_barang_masuk = $_GET['id'];
    
    // Mulai transaksi
    $koneksi->begin_transaction();
    
    try {
        // 1. Ambil data barang masuk yang akan dihapus
        $query = $koneksi->query("SELECT id_barang, jumlah_masuk FROM barang_masuk WHERE id_barang_masuk = $id_barang_masuk");
        $data = $query->fetch_assoc();
        
        if (!$data) {
            throw new Exception("Data barang masuk tidak ditemukan");
        }
        
        // 2. Update stok di gudang (kurangi)
        $sql_update = "UPDATE gudang SET jumlah = jumlah - ? WHERE id = ?";
        $stmt_update = $koneksi->prepare($sql_update);
        
        if ($stmt_update === false) {
            throw new Exception("Error preparing update statement: " . $koneksi->error);
        }
        
        $stmt_update->bind_param("ii", $data['jumlah_masuk'], $data['id_barang']);
        
        if (!$stmt_update->execute()) {
            throw new Exception("Error executing update statement: " . $stmt_update->error);
        }
        
        // 3. Hapus data barang masuk
        $sql_delete = "DELETE FROM barang_masuk WHERE id_barang_masuk = ?";
        $stmt_delete = $koneksi->prepare($sql_delete);
        
        if ($stmt_delete === false) {
            throw new Exception("Error preparing delete statement: " . $koneksi->error);
        }
        
        $stmt_delete->bind_param("i", $id_barang_masuk);
        
        if (!$stmt_delete->execute()) {
            throw new Exception("Error executing delete statement: " . $stmt_delete->error);
        }
        
        // Commit transaksi
        $koneksi->commit();
        
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data barang masuk berhasil dihapus',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = '?page=barangmasuk';
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
                }).then(function() {
                    window.location.href = '?page=barangmasuk';
                });
              </script>";
    }
} else {
    echo "<script>window.location.href = '?page=barangmasuk';</script>";
}
?>