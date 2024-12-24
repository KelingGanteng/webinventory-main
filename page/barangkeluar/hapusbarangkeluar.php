<?php
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Mulai transaksi
    $koneksi->begin_transaction();
    
    try {
        // Ambil informasi barang keluar sebelum dihapus
        $sql_select = "SELECT id_barang, jumlah_keluar FROM barang_keluar WHERE id_barang_keluar = ?";
        $stmt_select = $koneksi->prepare($sql_select);
        $stmt_select->bind_param("i", $id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        $data = $result->fetch_assoc();
        
        if (!$data) {
            throw new Exception("Data barang keluar tidak ditemukan");
        }
        
        // Kembalikan stok ke gudang
        $sql_update = "UPDATE gudang SET jumlah = jumlah + ? WHERE id = ?";
        $stmt_update = $koneksi->prepare($sql_update);
        $stmt_update->bind_param("ii", $data['jumlah_keluar'], $data['id_barang']);
        
        if (!$stmt_update->execute()) {
            throw new Exception("Error updating stock: " . $stmt_update->error);
        }
        
        // Hapus data barang keluar
        $sql_delete = "DELETE FROM barang_keluar WHERE id_barang_keluar = ?";
        $stmt_delete = $koneksi->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);
        
        if (!$stmt_delete->execute()) {
            throw new Exception("Error deleting record: " . $stmt_delete->error);
        }
        
        // Commit transaksi
        $koneksi->commit();
        
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data barang keluar berhasil dihapus',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = '?page=barangkeluar';
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
                    window.location.href = '?page=barangkeluar';
                });
              </script>";
    }
}
?>