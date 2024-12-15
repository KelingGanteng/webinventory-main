<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

// Query untuk mengambil data barang retur
$query = "SELECT * FROM barang_retur ORDER BY id_retur";
$result = $koneksi->query($query);

// Persiapkan array untuk menyimpan data
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Mengembalikan data dalam format JSON
echo json_encode($data);
?>