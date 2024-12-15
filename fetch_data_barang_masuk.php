<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Check connection
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

// Check if id_transaksi is provided
if (isset($_GET['id_transaksi'])) {
    $id_transaksi = $_GET['id_transaksi'];

    // Query to fetch the specific data based on id_transaksi
    $query = "SELECT * FROM barang_masuk WHERE id_transaksi = '$id_transaksi'";
    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        // Fetch the data and return it as JSON
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}
?>