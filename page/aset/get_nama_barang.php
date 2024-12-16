<?php
// Koneksi ke database
include('koneksibarang.php');

// Cek jika kode_aset ada di POST
if (isset($_POST['kode_aset'])) {
    $kode_aset = $_POST['kode_aset'];

    // Query untuk mengambil nama barang berdasarkan kode aset
    $sql = $koneksi->query("SELECT nama_barang FROM gudang WHERE kode_aset = '$kode_aset'");

    if ($sql->num_rows > 0) {
        // Ambil nama barang dan kirim sebagai respons
        $data = $sql->fetch_assoc();
        echo $data['nama_barang'];
    } else {
        echo ""; // Jika tidak ditemukan, kembalikan string kosong
    }
}
?>
