<?php
if (isset($_GET['id'])) {
    try {
        $koneksi->begin_transaction();
        
        $id = (int)$_GET['id'];
        
        // Ambil data barang keluar sebelum dihapus
        $sql_get = "SELECT kode_barang, jumlah FROM barang_keluar WHERE id = ?";
        $stmt_get = $koneksi->prepare($sql_get);
        $stmt_get->bind_param("i", $id);
        $stmt_get->execute();
        $result = $stmt_get->get_result();
        
        if ($data = $result->fetch_assoc()) {
            $kode_barang = $data['kode_barang'];
            $jumlah_kembali = (int)$data['jumlah'];
            
            // Kembalikan stok ke gudang
            $sql_update = "UPDATE gudang 
                          SET jumlah = jumlah + ? 
                          WHERE kode_barang = ?";
            $stmt_update = $koneksi->prepare($sql_update);
            $stmt_update->bind_param("is", $jumlah_kembali, $kode_barang);
            $stmt_update->execute();
            
            // Hapus data barang keluar
            $sql_delete = "DELETE FROM barang_keluar WHERE id = ?";
            $stmt_delete = $koneksi->prepare($sql_delete);
            $stmt_delete->bind_param("i", $id);
            $stmt_delete->execute();
            
            $koneksi->commit();
            echo "<script>
                alert('Data Berhasil Dihapus');
                window.location.href='?page=barangkeluar';
            </script>";
        } else {
            throw new Exception("Data tidak ditemukan");
        }
    } catch (Exception $e) {
        $koneksi->rollback();
        echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.location.href='?page=barangkeluar';
        </script>";
    }
} else {
    echo "<script>
        alert('ID tidak valid');
        window.location.href='?page=barangkeluar';
    </script>";
}
?>