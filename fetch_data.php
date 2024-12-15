<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query untuk mengambil data dari database
$query = "SELECT kode_barang, nama_barang, kondisi, jenis_barang, jumlah, satuan FROM stock_gudang";
$result = $koneksi->query($query);

// Membuat array untuk menyimpan data
$data = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// Mengembalikan data dalam format JSON
echo json_encode($data);

// Tutup koneksi
$koneksi->close();
?>