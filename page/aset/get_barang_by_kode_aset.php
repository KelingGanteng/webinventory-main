<?php
include('koneksibarang.php');

if (isset($_GET['kode_aset'])) {
    $kode_aset = $_GET['kode_aset'];

    // Query untuk mendapatkan barang berdasarkan kode aset
    $sql = $koneksi->query("SELECT * FROM gudang WHERE kode_aset = '$kode_aset' ORDER BY nama_barang");

    // Menyiapkan response dalam bentuk array
    $barang = [];
    while ($data = $sql->fetch_assoc()) {
        $barang[] = [
            'id' => $data['id'],
            'nama_barang' => $data['nama_barang']
        ];
    }

    // Mengembalikan data sebagai JSON
    echo json_encode($barang);
}
?>
