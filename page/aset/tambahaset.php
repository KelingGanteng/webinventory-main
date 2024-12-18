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
        $gudang_id = $_POST['gudang_id']; // Ambil jenis barang dari form

        // Gabungkan kode aset, jenis barang, dan nomor urut untuk membentuk kode lengkap
        $kode_lengkap = $kode_aset . '/' . $gudang_id . '/' . $nomor_urut; // Format: CBA001/Laptop/0001
    } else {
        $kode_lengkap = ''; // Tidak ada kode lengkap jika tidak ada kode aset atau nomor urut
    }

    // Ambil data lainnya dari form
    $departemen_id = $_POST['departemen_id'];
    $gudang_id = $_POST['gudang_id'];
    $status = $_POST['status'];
    $tanggal_pembelian = $_POST['tanggal_pembelian'];
    $karyawan_id = $_POST['karyawan_id'];


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
    $sql = "INSERT INTO aset (kode_aset, kode_lengkap, departemen_id, status, tanggal_pembelian, karyawan_id, gudang_id) 
            VALUES ('$kode_aset', '$kode_lengkap', '$departemen_id', '$status', '$tanggal_pembelian', '$karyawan_id', '$gudang_id')";

    if ($koneksi->query($sql) === TRUE) {
        echo "<script>alert('Data aset berhasil ditambahkan!'); window.location.href='?page=aset';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $koneksi->error;
    }
}
?>

<!-- Halaman Form Tambah Aset -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Aset</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="kode_aset" class="form-label">Kode Aset</label>
                    <select class="form-control" id="kode_aset" name="kode_aset" required>
                        <option value="">Pilih Kode Aset</option>
                        <?php
                        // Ambil data Kode Aset dari tabel jenis_barang
                        $sql = $koneksi->query("SELECT * FROM jenis_barang");
                        while ($data = $sql->fetch_assoc()) {
                            echo "<option value='" . $data['code_barang'] . "'>" . $data['code_barang'] . ' '. $data['nama_barang']. "</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <!-- Input untuk Gudang -->
                <div class="mb-3">
                    <label for="gudang_id" class="form-label">Nama Barang</label>
                    <select class="form-control select2" id="gudang_id" name="gudang_id" required>
                        <option value="">Pilih Nama Barang</option>
                        <?php
                        $sql = $koneksi->query("SELECT * FROM gudang ORDER BY nama_barang");
                        while ($data = $sql->fetch_assoc()) {
                            echo "<option value='" . $data['id'] . "'>" . htmlspecialchars($data['nama_barang']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                
               

                <div class="mb-3">
                    <label for="nomor_urut" class="form-label">Nomor Urut</label>
                    <input type="number" class="form-control" id="nomor_urut" name="nomor_urut" placeholder="Masukkan Nomor Urut" min="1" required>
                </div>

                <div class="mb-3">
                    <label for="kode_lengkap" class="form-label">Kode Aset Lengkap</label>
                    <input type="text" class="form-control" id="kode_lengkap" name="kode_lengkap" readonly>
                </div>

                <script>
$(document).ready(function() {
    // Ketika kode_aset dipilih
    $('#kode_aset').change(function() {
        var kodeAset = $(this).val();
        
        // Reset dan disable dropdown nama barang
        $('#gudang_id').empty().append('<option value="">Pilih Nama Barang</option>');
        
        if(kodeAset) {
            // Ajax call untuk mengambil data nama barang sesuai kode
            $.ajax({
                url: 'page/aset/get_filtered_items.php',
                type: 'POST',
                data: {kode_aset: kodeAset},
                success: function(response) {
                    $('#gudang_id').html(response);
                    updateKodeLengkap();
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        }
    });

    // Fungsi lainnya tetap sama
    $('#gudang_id').change(updateKodeLengkap);
    $('#nomor_urut').on('input', updateKodeLengkap);

    function updateKodeLengkap() {
        var kodeAset = $('#kode_aset').val();
        var gudangText = $('#gudang_id option:selected').text();
        var nomorUrut = $('#nomor_urut').val();

        if (nomorUrut) {
            nomorUrut = nomorUrut.toString().padStart(4, '0');
        }

        if (kodeAset && gudangText && nomorUrut) {
            var kodeLengkap = kodeAset + '/' + gudangText + '/' + nomorUrut;
            $('#kode_lengkap').val(kodeLengkap);
        } else {
            $('#kode_lengkap').val('');
        }
    }

    // Inisialisasi Select2
    $('.select2').select2({
        width: '100%',
        placeholder: 'Pilih opsi'
    });
});
</script>

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
                    <label for="tanggal_pembelian" class="form-label">Tanggal Penyerahan</label>
                    <input type="date" class="form-control" id="tanggal_pembelian" name="tanggal_pembelian" required>
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

    .form-control {
        border-radius: 0.375rem;
    }

    .form-label {
        font-weight: bold;
    }
</style>
