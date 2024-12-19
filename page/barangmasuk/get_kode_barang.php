<?php
include "../../koneksibarang.php";

if(isset($_POST['nama_barang'])) {
    $nama_barang = $_POST['nama_barang'];
    
    // Ambil kode barang terbaru untuk nama barang tersebut
    $query = $koneksi->query("SELECT kode_barang 
                             FROM gudang 
                             WHERE nama_barang = '$nama_barang' 
                             ORDER BY kode_barang DESC 
                             LIMIT 1");
    
    if($data = $query->fetch_assoc()) {
        echo $data['kode_barang'];
    } else {
        // Generate kode barang baru jika belum ada
        // Format: SF/IT/[counter]
        $counter_query = $koneksi->query("SELECT MAX(SUBSTRING_INDEX(kode_barang, '/', -1)) as counter 
                                        FROM gudang 
                                        WHERE kode_barang LIKE 'SF/IT/%'");
        $counter_data = $counter_query->fetch_assoc();
        $counter = intval($counter_data['counter']) + 1;
        
        $new_kode = "SF/IT/" . $counter;
        echo $new_kode;
    }
}
?>