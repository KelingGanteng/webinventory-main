<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Periksa apakah koneksi berhasil
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil kode barang dari parameter POST
$kode_barang = $_POST['kode_barang'];

// Query untuk mengambil stok barang berdasarkan kode barang
$sql = $koneksi->query("SELECT jumlah FROM gudang WHERE kode_barang = '$kode_barang'");

// Cek apakah data ditemukan
if ($sql->num_rows > 0) {
    $data = $sql->fetch_assoc();
    echo $data['jumlah']; // Mengembalikan jumlah stok barang
} else {
    echo 0; // Jika tidak ditemukan, kembalikan stok 0
}

$koneksi->close();
?>