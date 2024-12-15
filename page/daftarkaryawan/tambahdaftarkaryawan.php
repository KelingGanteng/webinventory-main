<?php
// Menghubungkan ke database
include('koneksibarang.php');

// Menyimpan data jika tombol submit ditekan
if (isset($_POST['submit'])) {
    // Ambil data dari form
    $nama_karyawan = $_POST['nama_karyawan'];
    $bagian = $_POST['bagian'];
    $departemen_id = $_POST['departemen_id'];

    // Query untuk menyimpan data
    $sql = $koneksi->query("INSERT INTO daftar_karyawan (nama, bagian, departemen_id) VALUES ('$nama_karyawan', '$bagian', '$departemen_id')");

    // Cek jika berhasil
    if ($sql) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location='?page=daftarkaryawan';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}

// Query untuk mengambil data departemen
$departemen_sql = $koneksi->query("SELECT * FROM departemen");
?>

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Daftar Karyawan</h6>
        </div>
        <div class="card-body">
            <!-- Form untuk menambahkan karyawan -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="nama_karyawan">Nama Karyawan</label>
                    <input type="text" id="nama_karyawan" name="nama_karyawan" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="bagian">Bagian</label>
                    <input type="text" id="bagian" name="bagian" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="departemen_id">Departemen</label>
                    <!-- Select2 digunakan pada elemen ini -->
                    <select name="departemen_id" id="departemen_id" class="form-control select2" required>
                        <option value="">Pilih Departemen</option>
                        <?php
                        while ($departemen = $departemen_sql->fetch_assoc()) {
                            echo "<option value='" . $departemen['id'] . "'>" . htmlspecialchars($departemen['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                    <a href="?page=daftarkaryawan" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Page Content -->

<!-- Tambahkan library Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Tambahkan script Select2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    // Inisialisasi Select2 untuk elemen dengan id "departemen_id"
    $(document).ready(function () {
        $('#departemen_id').select2({
            placeholder: "Pilih Departemen", // Placeholder jika belum ada pilihan
            allowClear: true // Memungkinkan pilihan dikosongkan
        });
    });
</script>

<!-- Styling Custom untuk Select2 -->
<style>
    /* Styling Select2 */
    .select2-container .select2-selection--single {
        height: 38px !important;
        /* Ukuran tinggi input */
        border-radius: 5px;
        /* Border radius untuk sudut melengkung */
        border: 1px solid #ccc;
        /* Warna border */
    }
</style>