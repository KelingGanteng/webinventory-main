<?php
// Koneksi ke database
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
include('koneksibarang.php');

// Cek apakah ada parameter 'id' di URL
if (isset($_GET['id'])) {
    // Ambil ID aset dari URL
    $id_aset = $_GET['id'];

    // Query untuk mengecek apakah aset dengan ID tersebut ada di database
    $sql_check = $koneksi->query("SELECT * FROM aset WHERE id = '$id_aset'");
    if ($sql_check->num_rows > 0) {
        // Aset ditemukan, lakukan penghapusan
        $sql_delete = $koneksi->query("DELETE FROM aset WHERE id = '$id_aset'");
        if ($sql_delete) {
            // Redirect setelah berhasil menghapus data
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data aset berhasil dihapus',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location.href = '?page=aset';
            });
          </script>";
        } else {
            // Jika terjadi kesalahan dalam penghapusan
            echo "<script>alert('Terjadi kesalahan saat menghapus data aset!'); window.history.back();</script>";
        }
    } else {
        // Jika aset tidak ditemukan
        echo "<script>alert('Aset tidak ditemukan!'); window.history.back();</script>";
    }
} else {
    // Jika parameter 'id' tidak ditemukan di URL
    echo "<script>alert('ID aset tidak ditemukan!'); window.history.back();</script>";
}
?>