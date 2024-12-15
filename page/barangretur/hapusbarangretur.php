<?php
// Pastikan koneksi database sudah tersedia
if (!isset($koneksi)) {
    die("Koneksi database tidak tersedia!");
}

// Pastikan kode_barang diterima dengan benar
if (isset($_GET['id_retur']) && !empty($_GET['id_retur'])) {
    // Amankan data inputan dari user untuk menghindari SQL Injection
    $id_retur = mysqli_real_escape_string($koneksi, $_GET['id_retur']);

    // Cek apakah kode_barang ada di dalam tabel gudang
    $check = $koneksi->query("SELECT tanggal_retur FROM barang_retur WHERE id_retur='$id_retur'");

    // Jika data ditemukan
    if ($check && $check->num_rows > 0) {
        // Hapus data barang dari tabel gudang
        $sql = $koneksi->query("DELETE FROM barang_retur WHERE id_retur='$id_retur'");

        // Jika penghapusan berhasil
        if ($sql) {
            echo "<script type='text/javascript'>
                    alert('Data Barang Berhasil Dihapus');
                    window.location.href = '?page=barangretur';
                  </script>";
        } else {
            // Jika gagal menghapus data, tampilkan error
            echo "<script type='text/javascript'>
                    alert('Gagal Menghapus Data Barang');
                    window.location.href = '?page=barangretur';
                  </script>";
        }
    } else {
        // Jika kode_barang tidak ditemukan di database
        echo "<script type='text/javascript'>
                alert('Data Barang Tidak Ditemukan');
                window.location.href = '?page=barangretur';
              </script>";
    }
} else {
    // Jika tidak ada kode_barang yang diterima dari URL
    echo "<script type='text/javascript'>
            alert('Kode Barang Tidak Ditemukan');
            window.location.href = '?page=barangretur';
          </script>";
}
?>