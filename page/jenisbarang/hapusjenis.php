<?php
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
// Cek koneksi
if (!isset($koneksi)) {
    die("Koneksi database tidak tersedia!");
}

// Cek parameter id
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Ambil nama jenis barang untuk konfirmasi
    $check = $koneksi->query("SELECT jenis_barang FROM jenis_barang WHERE id='$id'");
    $data = $check->fetch_assoc();

    if ($data) {
        // Lakukan penghapusan
        $sql = $koneksi->query("DELETE FROM jenis_barang WHERE id='$id'");

        if ($sql) {
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data jenis barang berhasil dihapus',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location.href = '?page=jenisbarang';
            });
          </script>"; 
        } else {
            echo "<script>
                alert('Gagal menghapus data: " . $koneksi->error . "');
                window.location.href='?page=jenisbarang';
            </script>";
        }
    } else {
        echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href='?page=jenisbarang';
        </script>";
    }
} else {
    echo "<script>
        alert('ID tidak ditemukan!');
        window.location.href='?page=jenisbarang';
    </script>";
}
?>