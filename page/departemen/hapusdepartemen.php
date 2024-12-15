<?php
// Menghubungkan ke database
include('koneksibarang.php');

// Mengambil ID departemen dari URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengecek apakah departemen dengan ID ini ada
    $sql = $koneksi->query("SELECT * FROM departemen WHERE id = '$id'");

    if ($sql->num_rows > 0) {
        // Query untuk menghapus data departemen
        $delete = $koneksi->query("DELETE FROM departemen WHERE id = '$id'");

        // Jika berhasil menghapus
        if ($delete) {
            echo "<script>alert('Departemen berhasil dihapus!'); window.location='?page=departemen';</script>";
        } else {
            echo "<script>alert('Gagal menghapus departemen.'); window.location='?page=departemen';</script>";
        }
    } else {
        echo "<script>alert('Departemen tidak ditemukan!'); window.location='?page=departemen';</script>";
    }
} else {
    echo "<script>alert('ID Departemen tidak ditemukan!'); window.location='?page=departemen';</script>";
}
?>