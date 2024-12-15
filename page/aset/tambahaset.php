<?php
// Koneksi ke database
include('koneksibarang.php');

// Mengecek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Memeriksa apakah 'kode_aset' dan 'nomor_urut' ada dalam form
    $kode_aset = isset($_POST['kode_aset']) ? $_POST['kode_aset'] : '';
    $nomor_urut = isset($_POST['nomor_urut']) ? $_POST['nomor_urut'] : '';

    // Pastikan nomor urut diberikan jika kode aset dipilih
    if ($kode_aset && $nomor_urut) {
        // Format nomor urut dengan padding 4 digit
        $nomor_urut = str_pad($nomor_urut, 4, '0', STR_PAD_LEFT);
        
        // Ambil nama jenis barang untuk digunakan dalam kode lengkap
        $sql_jenis_barang = $koneksi->query("SELECT jenis_barang FROM jenis_barang WHERE code_barang = '$kode_aset'");
        $data_jenis_barang = $sql_jenis_barang->fetch_assoc();
        $jenis_barang = $data_jenis_barang['jenis_barang'];

        // Gabungkan kode aset, jenis barang, dan nomor urut untuk membentuk kode lengkap
        $kode_lengkap = $kode_aset . '/' . $jenis_barang . '/' . $nomor_urut; // Format: CBA001/Laptop/0001
    } else {
        $kode_lengkap = ''; // Tidak ada kode lengkap jika tidak ada kode aset atau nomor urut
    }

    // Ambil data lainnya dari form
    $nama_aset = $_POST['nama_aset'];
    $departemen_id = $_POST['departemen_id'];
    $lokasi = $_POST['lokasi'];
    $status = $_POST['status'];
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $karyawan_id = $_POST['karyawan_id'];
    $kondisi = $_POST['kondisi'];

    // Validasi karyawan_id
    $karyawan_check = $koneksi->query("SELECT id FROM daftar_karyawan WHERE id = '$karyawan_id'");
    if ($karyawan_check->num_rows == 0) {
        echo "<script>alert('Karyawan dengan ID tersebut tidak ditemukan!'); window.location.href = '?page=tambah-aset';</script>";
        exit; // Menghentikan proses jika karyawan tidak ditemukan
    }

    // Mengecek apakah kode aset sudah ada di database
    $cek_kode_aset = $koneksi->query("SELECT * FROM aset WHERE kode_lengkap = '$kode_lengkap'");
    if ($cek_kode_aset->num_rows > 0) {
        echo "<script>alert('Kode aset sudah ada, harap pilih nomor urut yang berbeda.'); window.location.href = '?page=tambah-aset';</script>";
        exit;
    }

    // Query untuk menyimpan data ke database
    $sql = "INSERT INTO aset (kode_aset, kode_lengkap, nama_aset, departemen_id, lokasi, status, tanggal_pembelian, karyawan_id, kondisi) 
            VALUES ('$kode_aset', '$kode_lengkap', '$nama_aset', '$departemen_id', '$lokasi', '$status', '$tanggal_pembelian', '$karyawan_id', '$kondisi')";

    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Data aset berhasil ditambahkan!'); window.location.href='?page=aset';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}
?>



<!-- Halaman Form Tambah Aset -->
<!-- Halaman Form Tambah Aset -->
<!-- Halaman Form Tambah Aset -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Aset</h6>
        </div>
        <div class="card-body">
            <form method="POST">
              <!-- Input untuk Kode Aset -->
        <div class="mb-3">
            <label for="kode_aset" class="form-label">Kode Aset</label>
            <select class="form-control" id="kode_aset" name="kode_aset" required>
                <option value="">Pilih Kode Aset</option>
                <?php
                // Ambil data Kode Aset dari tabel jenis_barang
                $sql = $koneksi->query("SELECT * FROM jenis_barang");
                while ($data = $sql->fetch_assoc()) {
                    echo "<option value='" . $data['code_barang'] . "'>" . $data['code_barang'] . " - " . $data['jenis_barang'] . "</option>";
                }
                ?>
            </select>
        </div>

                <!-- Tempat untuk menampilkan input Nomor Urut -->
                <div id="nomor_urut_container" class="mb-3" style="display:none;">
                    <label for="nomor_urut" class="form-label">Nomor Urut Kode Aset</label>
                    <input type="number" class="form-control" id="nomor_urut" name="nomor_urut"
                        placeholder="Masukkan Nomor Urut" min="1" required>
                </div>

                <!-- Tempat untuk menampilkan Kode Aset Lengkap -->
                <div class="mb-3">
                    <label for="kode_lengkap" class="form-label">Kode Aset Lengkap</label>
                    <input type="text" class="form-control" id="kode_lengkap" name="kode_lengkap" readonly>
                </div>

                <script>
                    $(document).ready(function() {
                        // Inisialisasi Select2 pada dropdown kode_aset
                        $('.select2').select2({
                            placeholder: "Pilih Kode Aset", // Placeholder jika tidak ada pilihan
                            width: '100%' // Memastikan lebar dropdown 100%
                        });

                        // JavaScript untuk menampilkan input nomor urut ketika memilih kode aset
                        document.getElementById('kode_aset').addEventListener('change', function () {
                            var kodeAset = this.value;
                            var nomorUrutContainer = document.getElementById('nomor_urut_container');
                            var kodeLengkap = document.getElementById('kode_lengkap');

                            if (kodeAset !== "") {
                                // Tampilkan input nomor urut jika kode aset dipilih
                                nomorUrutContainer.style.display = "block";
                                kodeLengkap.value = kodeAset + "/"; // Set kode aset yang dipilih pada bagian kode lengkap
                            } else {
                                // Sembunyikan input nomor urut jika tidak ada kode aset yang dipilih
                                nomorUrutContainer.style.display = "none";
                                kodeLengkap.value = "";
                            }
                        });

                        // Menangani perubahan pada input nomor urut
                        document.getElementById('nomor_urut').addEventListener('input', function () {
                            var kodeAset = document.getElementById('kode_aset').value;
                            var nomorUrut = this.value;
                            var kodeLengkap = document.getElementById('kode_lengkap');

                            if (kodeAset && nomorUrut) {
                                // Format nomor urut menjadi 4 digit (misalnya 0001, 0002, ...)
                                var nomorUrutFormatted = nomorUrut.padStart(4, '0');
                                kodeLengkap.value = kodeAset + "/" + nomorUrutFormatted;

                                // Mengecek apakah kode lengkap sudah ada di database
                                checkIfKodeAsetExists(kodeLengkap.value);
                            }
                        });

                        // Fungsi untuk memeriksa apakah kode aset lengkap sudah ada di database
                        function checkIfKodeAsetExists(kodeLengkap) {
                            // Melakukan request ke server untuk memeriksa apakah kode lengkap sudah ada
                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "check_kode_aset.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onload = function () {
                                if (xhr.status === 200) {
                                    var response = xhr.responseText.trim();
                                    if (response === "exists") {
                                        alert("Kode Aset ini sudah ada. Silakan pilih nomor urut yang berbeda.");
                                        document.getElementById('nomor_urut').value = ''; // Reset nomor urut
                                        document.getElementById('kode_lengkap').value = kodeAset + "/";
                                    }
                                }
                            };
                            xhr.send("kode_lengkap=" + encodeURIComponent(kodeLengkap));
                        }
                    });
                </script>


                <!-- Input untuk Nama Aset -->
                <div class="mb-3">
                    <label for="nama_aset" class="form-label">Nama Aset</label>
                    <input type="text" class="form-control" id="nama_aset" name="nama_aset" required>
                </div>

                <!-- Input untuk Departemen -->
                <div class="mb-3">
                    <label for="departemen_id" class="form-label">Departemen</label>
                    <select class="form-control select2" id="departemen_id" name="departemen_id" required>
                        <option value="">Pilih Departemen</option>
                        <?php
                        $sql = $koneksi->query("SELECT * FROM departemen ORDER BY nama");
                        while ($data = $sql->fetch_assoc()) {
                            echo "<option value='" . $data['id'] . "'>" . htmlspecialchars($data['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Input untuk Karyawan -->
                <div class="mb-3">
                    <label for="karyawan_id" class="form-label">Karyawan</label>
                    <select class="form-control select2" id="karyawan_id" name="karyawan_id" required>
                        <option value="">Pilih Karyawan</option>
                        <?php
                        // Ambil data Karyawan dari tabel daftar_karyawan
                        $sql = $koneksi->query("SELECT * FROM daftar_karyawan");
                        while ($data = $sql->fetch_assoc()) {
                            echo "<option value='" . $data['id'] . "'>" . htmlspecialchars($data['nama']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Input untuk Lokasi -->
                <div class="mb-3">
                    <label for="lokasi" class="form-label">Lokasi</label>
                    <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                </div>

                <!-- Input untuk Status -->
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>

                <!-- Input untuk Tanggal Pembelian -->
                <div class="mb-3">
                    <label for="tanggal_pembelian" class="form-label">Tanggal Pembelian</label>
                    <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" required>
                </div>

                <!-- Input untuk Kondisi -->
                <div class="mb-3">
                    <label for="kondisi" class="form-label">Kondisi</label>
                    <select id="kondisi" name="kondisi" class="form-control" required>
                        <option value="Baik">Baik</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>

                <!-- Tombol Submit -->
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary custom-btn">
                        <i class="fas fa-save me-2"></i> Simpan Aset
                    </button>
                    <a href="?page=aset" class="btn btn-secondary custom-btn">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS untuk Styling Form -->
<style>
    .custom-btn {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .custom-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }

    .custom-btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
    }

    .form-control {
        border-radius: 0.375rem;
    }

    /* Styling untuk form label */
    .form-label {
        font-weight: bold;
    }
</style>