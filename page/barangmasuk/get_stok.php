<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if(isset($_POST['id_barang'])) {
    $id_barang = $_POST['id_barang'];
    
    $stmt = $koneksi->prepare("SELECT jumlah FROM gudang WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id_barang);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(['jumlah' => 0]);
    }
}

$koneksi->close();
?>