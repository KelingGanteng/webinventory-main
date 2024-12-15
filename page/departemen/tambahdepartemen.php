<?php
// Menghubungkan ke database
include('koneksibarang.php');

// Menangani form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $nama = $_POST['nama'];

    // Validasi input
    if (empty($nama)) {
        $error_message = "Nama departemen tidak boleh kosong.";
    } else {
        // Menambahkan data ke dalam tabel departemen
        $sql = "INSERT INTO departemen (nama) VALUES ('$nama')";

        // Mengeksekusi query
        if ($koneksi->query($sql) === TRUE) {
            // Redirect ke halaman daftar departemen setelah berhasil menambah data
            echo "<script>alert('Departemen berhasil ditambahkan!'); window.location='?page=departemen';</script>";
            exit;
        } else {
            $error_message = "Gagal menambahkan departemen: " . $koneksi->error;
        }
    }
}
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Departemen</h6>
        </div>
        <div class="card-body">
            <!-- Menampilkan pesan error jika ada -->
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Form untuk menambah departemen -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama">Nama Departemen</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Simpan Departemen</button>
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