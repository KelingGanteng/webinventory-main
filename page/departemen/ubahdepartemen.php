<?php
echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
// Menghubungkan ke database
include('koneksibarang.php');

// Mengambil ID departemen dari URL
if (isset($_GET['id'])) {
    $id_departemen = $_GET['id'];

    // Query untuk mendapatkan data departemen berdasarkan ID
    $sql = "SELECT * FROM departemen WHERE id = '$id_departemen'";
    $result = $koneksi->query($sql);

    if ($result->num_rows > 0) {
        // Ambil data departemen
        $data = $result->fetch_assoc();
        $nama_departemen = $data['nama'];
    } else {
        // Jika departemen tidak ditemukan
        echo "<script>alert('Departemen tidak ditemukan!'); window.location='?page=departemen';</script>";
        exit;
    }
} else {
    // Jika ID tidak ada di URL, redirect ke halaman daftar departemen
    echo "<script>window.location='?page=departemen';</script>";
    exit;
}

// Menangani form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $nama = $_POST['nama'];

    // Validasi input
    if (empty($nama)) {
        $error_message = "Nama departemen tidak boleh kosong.";
    } else {
        // Query untuk update data departemen
        $sql_update = "UPDATE departemen SET nama = '$nama' WHERE id = '$id_departemen'";

        // Mengeksekusi query
        if ($koneksi->query($sql_update) === TRUE) {
            // Redirect ke halaman daftar departemen setelah berhasil update data
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data departemen berhasil diubah',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location.href = '?page=departemen';
            });
          </script>";            exit;
        } else {
            $error_message = "Gagal mengubah departemen: " . $koneksi->error;
        }
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah Departemen</h6>
        </div>
        <div class="card-body">
            <!-- Menampilkan pesan error jika ada -->
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Form untuk mengubah departemen -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama">Nama Departemen</label>
                    <input type="text" class="form-control" id="nama" name="nama"
                        value="<?php echo $nama_departemen; ?>" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
                <a href="?page=departemen" class="btn btn-secondary mt-3">Kembali</a>
            </form>
        </div>
    </div>
</div>
<!-- End Page Content -->

<!-- Custom Button and Table Styling -->
<style>
    .btn {
        font-size: 1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-control {
        font-size: 1rem;
    }

    .alert {
        margin-bottom: 1.5rem;
    }
</style>