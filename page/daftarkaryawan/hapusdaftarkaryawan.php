<?php
// Menghubungkan ke database
include('koneksi.php');

// Ambil ID karyawan yang akan dihapus dari URL
$id = $_GET['id'];

// Query untuk menghapus data karyawan berdasarkan ID
if (isset($_GET['id'])) {
    // Pastikan ID ada dan valid
    $id = $_GET['id'];

    // Query untuk menghapus data karyawan
    $delete_sql = $koneksi->query("DELETE FROM daftar_karyawan WHERE id = '$id'");

    // Cek apakah penghapusan berhasil
    if ($delete_sql) {
        // Jika berhasil, tampilkan pesan dan redirect ke halaman daftar karyawan
        echo "<script>alert('Data karyawan berhasil dihapus!'); window.location='?page=daftarkaryawan';</script>";
    } else {
        // Jika gagal, tampilkan pesan error
        echo "<script>alert('Gagal menghapus data karyawan!'); window.location='?page=daftarkaryawan';</script>";
    }
} else {
    // Jika ID tidak ada di URL, tampilkan pesan error
    echo "<script>alert('ID karyawan tidak ditemukan!'); window.location='?page=daftarkaryawan';</script>";
}
?>