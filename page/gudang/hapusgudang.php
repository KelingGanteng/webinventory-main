<?php
// Pastikan koneksi database sudah tersedia
if (!isset($koneksi)) {
    die("Koneksi database tidak tersedia!");
}

// Pastikan kode_barang diterima dengan benar
if (isset($_GET['kode_barang']) && !empty($_GET['kode_barang'])) {
    // Amankan data inputan dari user untuk menghindari SQL Injection
    $kode_barang = mysqli_real_escape_string($koneksi, $_GET['kode_barang']);

    // Cek apakah kode_barang ada di dalam tabel gudang
    $check = $koneksi->query("SELECT nama_barang FROM gudang WHERE kode_barang='$kode_barang'");

    // Jika data ditemukan
    if ($check && $check->num_rows > 0) {
        // Hapus data barang dari tabel gudang
        $sql = $koneksi->query("DELETE FROM gudang WHERE kode_barang='$kode_barang'");

        // Jika penghapusan berhasil
        if ($sql) {
            echo "<script type='text/javascript'>
                    alert('Data Barang Berhasil Dihapus');
                    window.location.href = '?page=gudang';
                  </script>";
        } else {
            // Jika gagal menghapus data, tampilkan error
            echo "<script type='text/javascript'>
                    alert('Gagal Menghapus Data Barang');
                    window.location.href = '?page=gudang';
                  </script>";
        }
    } else {
        // Jika kode_barang tidak ditemukan di database
        echo "<script type='text/javascript'>
                alert('Data Barang Tidak Ditemukan');
                window.location.href = '?page=gudang';
              </script>";
    }
} else {
    // Jika tidak ada kode_barang yang diterima dari URL
    echo "<script type='text/javascript'>
            alert('Kode Barang Tidak Ditemukan');
            window.location.href = '?page=gudang';
          </script>";
}
?>