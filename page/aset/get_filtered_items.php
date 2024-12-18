<?php
include '../../koneksibarang.php';

if(isset($_POST['kode_aset'])) {
    $kode_aset = $_POST['kode_aset'];
    
    // Query untuk mengambil barang yang sesuai dengan kode_aset
    $sql = "SELECT id, nama_barang 
            FROM gudang 
            WHERE kode_barang = ?";
            
    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("s", $kode_aset);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $options = '<option value="">Pilih Nama Barang</option>';
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $options .= '<option value="'.$row['id'].'">'.htmlspecialchars($row['nama_barang']).'</option>';
        }
    } else {
        $options .= '<option value="" disabled>Tidak ada data barang</option>';
    }
    
    echo $options;
    exit;
}

// Debug: Tampilkan error jika ada
if ($stmt->error) {
    echo "Error: " . $stmt->error;
}
?>