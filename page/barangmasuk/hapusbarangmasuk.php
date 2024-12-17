<?php
// Proses Hapus Data Barang Masuk
if (isset($_GET['id_transaksi'])) {
    $id_transaksi = $_GET['id_transaksi'];

    // Query Hapus Data
    $query_delete = $koneksi->query("DELETE FROM barang_masuk WHERE id_transaksi = '$id_transaksi'");

    // Notifikasi setelah proses
    if ($query_delete) {
        echo "<script>alert('Data berhasil dihapus'); window.location.href='?page=barangmasuk';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus'); window.location.href='?page=barangmasuk';</script>";
    }
} else {
    echo "<script>alert('ID Transaksi tidak ditemukan'); window.location.href='?page=barangmasuk';</script>";
}
?>
