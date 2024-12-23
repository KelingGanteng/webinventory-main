<?php
// Cek apakah kode_barang ada di URL
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
if (isset($_GET['kode_barang'])) {
    $kode_barang = $_GET['kode_barang'];

    // Hapus data barang berdasarkan kode_barang
    $hapus = $koneksi->query("DELETE FROM gudang WHERE kode_barang='$kode_barang'");

    if ($hapus) {
      echo "<script>
      Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: 'Data gudang berhasil diHapus',
          showConfirmButton: false,
          timer: 1500
      }).then(function() {
          window.location.href = '?page=gudang';
      });
    </script>"; 
    } else {
        echo "<script>
                alert('Gagal menghapus data: " . $koneksi->error . "');
                window.location.href='?page=gudang';
              </script>";
    }
} else {
    // Jika kode_barang tidak ada di URL
    echo "<script>
            alert('Kode barang tidak ditemukan!');
            window.location.href='?page=gudang';
          </script>";
}
?>
