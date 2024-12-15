<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Cek jika ada ID karyawan
if (isset($_POST['karyawan_id'])) {
    $karyawan_id = $_POST['karyawan_id'];

    // Query untuk mengambil Departemen berdasarkan Karyawan
    $sql = $koneksi->query("SELECT d.id, d.nama FROM departemen d 
                            JOIN daftar_karyawan dk ON dk.departemen_id = d.id
                            WHERE dk.id = '$karyawan_id'");

    // Cek apakah data ditemukan
    if ($sql->num_rows > 0) {
        while ($data = $sql->fetch_assoc()) {
            echo "<option value='{$data['id']}'>{$data['nama']}</option>";
        }
    } else {
        echo "<option value=''>Departemen tidak ditemukan</option>";
    }
} else {
    echo "<option value=''>-- Pilih Karyawan Terlebih Dahulu --</option>";
}

$koneksi->close();
?>