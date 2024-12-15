<?php
// Koneksi ke database
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Cek koneksi ke database
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil parameter pencarian dari input
$term = isset($_GET['term']) ? $_GET['term'] : '';

// Ambil data kerusakan dari database
$query = $koneksi->query("SELECT DISTINCT kerusakan FROM barang_retur WHERE kerusakan LIKE '%$term%'");

// Siapkan array untuk hasil
$results = array();
while ($row = $query->fetch_assoc()) {
    $results[] = $row['kerusakan'];
}

// Kembalikan hasil dalam format JSON
echo json_encode($results);
?>



<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<!-- jQuery UI library -->
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>



<script>
    $(document).ready(function () {
        $("#kerusakan").autocomplete({
            source: "fetchKerusakan.php", // Ganti dengan path ke file PHP yang Anda buat
            minLength: 2 // Minimal karakter yang harus dimasukkan sebelum pencarian dimulai
        });
    });
</script>