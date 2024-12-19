<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");

if (isset($_GET['id_transaksi'])) {
    try {
        // Mulai transaksi
        $koneksi->begin_transaction();

        $id_transaksi = (int)$_GET['id_transaksi'];

        // Ambil data barang masuk sebelum dihapus
        $sql_select = "SELECT kode_barang, jumlah FROM barang_masuk WHERE id = ?";
        $stmt_select = $koneksi->prepare($sql_select);
        $stmt_select->bind_param("i", $id_transaksi);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        $data_barang = $result->fetch_assoc();

        if ($data_barang) {
            // Update stok di gudang (kurangi)
            $sql_update = "UPDATE gudang 
                          SET jumlah = CAST(jumlah AS SIGNED) - ? 
                          WHERE kode_barang = ?";
            
            $stmt_update = $koneksi->prepare($sql_update);
            $stmt_update->bind_param("is", 
                $data_barang['jumlah'], 
                $data_barang['kode_barang']
            );
            $stmt_update->execute();

            // Hapus data barang masuk
            $sql_delete = "DELETE FROM barang_masuk WHERE id = ?";
            $stmt_delete = $koneksi->prepare($sql_delete);
            $stmt_delete->bind_param("i", $id_transaksi);
            $stmt_delete->execute();

            // Commit transaksi
            $koneksi->commit();

            echo "<script>
                alert('Data Barang Masuk berhasil dihapus!');
                window.location.href='?page=barangmasuk';
            </script>";
        } else {
            throw new Exception("Data barang masuk tidak ditemukan!");
        }

    } catch (Exception $e) {
        // Rollback jika terjadi error
        $koneksi->rollback();
        echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.location.href='?page=barangmasuk';
        </script>";
    }
} else {
    echo "<script>
        alert('ID transaksi tidak valid!');
        window.location.href='?page=barangmasuk';
    </script>";
}
?>