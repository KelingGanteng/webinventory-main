<?php
include 'koneksibarang.php'; // Koneksi database

if (isset($_POST['kode_barang'])) {
    $kode_barang = $_POST['kode_barang'];
    $query = $koneksi->query("SELECT nama_barang FROM gudang WHERE kode_barang = '$kode_barang'");

    $result = [];
    while ($row = $query->fetch_assoc()) {
        $result[] = $row;
    }
    echo json_encode($result); // Kembalikan data dalam format JSON
}
?>
