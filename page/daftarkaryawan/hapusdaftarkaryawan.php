<?php
// Koneksi ke database
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
include('koneksibarang.php');

// Ambil ID atau kode karyawan dari URL
$id_karyawan = isset($_GET['id']) ? $_GET['id'] : '';

// Jika tidak ada ID yang dikirim, kembali ke halaman daftar karyawan
if (!$id_karyawan) {
    echo "<script>alert('ID karyawan tidak ditemukan!'); window.location.href='?page=daftarkaryawan';</script>";
    exit;
}

// Hapus data terkait di tabel aset terlebih dahulu
$sql_aset = $koneksi->query("DELETE FROM aset WHERE karyawan_id = '$id_karyawan'");

if ($sql_aset) {
    // Jika data terkait berhasil dihapus, hapus karyawan
    $sql_karyawan = $koneksi->query("DELETE FROM daftar_karyawan WHERE id = '$id_karyawan'");

    if ($sql_karyawan) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data karyawan berhasil dihapus',
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            window.location.href = '?page=daftarkaryawan';
        });
      </script>";    } else {
        echo "<script>alert('Gagal menghapus data karyawan!'); window.location.href='?page=daftarkaryawan';</script>";
    }
} else {
    echo "<script>alert('Gagal menghapus data terkait di tabel aset!'); window.location.href='?page=daftarkaryawan';</script>";
}
?>
