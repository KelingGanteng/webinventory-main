<?php
// Pastikan halaman ini dilindungi dari akses langsung
if (!isset($_GET['kode_barang'])) {
    die('Kode barang tidak ditemukan.');
}

// Ambil kode barang dari parameter URL
$kode_barang = $_GET['kode_barang'];

// Koneksi ke database
include 'koneksibarang.php'; // Pastikan koneksi.php sesuai dengan pengaturan database Anda

// Query untuk menghapus data barang berdasarkan kode_barang
$sql = "DELETE FROM gudang WHERE kode_barang = ?";
$stmt = $koneksi->prepare($sql);
$stmt->bind_param("s", $kode_barang);

if ($stmt->execute()) {
    // Jika berhasil, redirect ke halaman utama dengan pesan sukses
    echo "<script>
            alert('Data berhasil dihapus!');
            window.location.href = '?page=gudang';
          </script>";
} else {
    // Jika gagal, tampilkan pesan error
    echo "<script>
            alert('Terjadi kesalahan dalam menghapus data!');
            window.location.href = '?page=gudang';
          </script>";
}

// Tutup koneksi dan statement
$stmt->close();
$koneksi->close();
?>
