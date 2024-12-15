<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Check connection
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

// Query untuk mengambil data dari tabel barang_keluar dengan JOIN untuk mendapatkan data satuan dari tabel gudang
$query = "SELECT barang_keluar.*, gudang.satuan 
          FROM barang_keluar 
          INNER JOIN gudang ON barang_keluar.kode_barang = gudang.kode_barang";
$result = $koneksi->query($query);

// Prepare an array to store the data
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Return data in JSON format
echo json_encode($data);
?>