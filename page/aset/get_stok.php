<?php
include '../../koneksibarang.php';

if(isset($_POST['gudang_id'])) {
    $gudang_id = $_POST['gudang_id'];
    
    $query = $koneksi->query("SELECT stok FROM gudang WHERE id = '$gudang_id'");
    $data = $query->fetch_assoc();
    
    echo json_encode($data);
}
?>