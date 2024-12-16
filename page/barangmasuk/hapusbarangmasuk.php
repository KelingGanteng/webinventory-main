<?php
// Cek apakah ada parameter 'id' yang diterima melalui URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data barang masuk berdasarkan id
    $sql = $koneksi->query("DELETE FROM barang_masuk WHERE id = '$id'");

    // Cek apakah query berhasil
    if ($sql) {
        echo "<script>alert('Data barang masuk berhasil dihapus!'); window.location='?page=barangmasuk';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, data gagal dihapus!'); window.location='?page=barangmasuk';</script>";
    }
}
?>
