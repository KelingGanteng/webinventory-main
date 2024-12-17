<?php
// Proses Simpan Data Barang Masuk
if (isset($_POST['simpan'])) {
    $id_transaksi = $_POST['id_transaksi'];
    $tanggal = $_POST['tanggal'];
    $kode_barang = $_POST['kode_barang'];
    $kondisi = $_POST['kondisi'];
    $jumlah = $_POST['jumlah'];

    // Ambil satuan dari tabel gudang berdasarkan kode_barang
    $query_satuan = $koneksi->query("SELECT satuan, jumlah FROM gudang WHERE kode_barang = '$kode_barang'");
    $data_satuan = $query_satuan->fetch_assoc();

    if ($data_satuan) {
        $satuan = $data_satuan['satuan'];
        $stok_sekarang = $data_satuan['jumlah']; // Ambil stok barang saat ini di gudang

        // Mulai Transaksi Simpan Data Barang Masuk
        $query_insert = $koneksi->query("INSERT INTO barang_masuk (id_transaksi, tanggal, kode_barang, kondisi, jumlah, satuan) 
                                         VALUES ('$id_transaksi', '$tanggal', '$kode_barang', '$kondisi', '$jumlah', '$satuan')");

        // Update stok barang di tabel gudang
        if ($query_insert) {
            $stok_baru = $stok_sekarang + $jumlah;
            $query_update_gudang = $koneksi->query("UPDATE gudang SET jumlah = '$stok_baru' WHERE kode_barang = '$kode_barang'");

            if ($query_update_gudang) {
                echo "<script>alert('Data berhasil ditambahkan dan stok gudang diperbarui'); window.location.href='?page=barangmasuk';</script>";
            } else {
                echo "<script>alert('Data berhasil ditambahkan, tetapi gagal memperbarui stok gudang');</script>";
            }
        } else {
            echo "<script>alert('Data gagal ditambahkan');</script>";
        }
    } else {
        echo "<script>alert('Kode Barang tidak ditemukan di gudang');</script>";
    }
}
?>


<!-- Form Tambah Barang Masuk -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Barang Masuk</h6>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="id_transaksi" class="form-label">ID Transaksi</label>
                        <input type="text" name="id_transaksi" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal Masuk</label>
                        <input type="date" name="tanggal" class="form-control" required>
                    </div>
                </div>

                <div class="row mb-3">
                   <div class="col-md-6">
                    <label for="kode_barang" class="form-label">Kode Barang</label>
                    <select id="kode_barang" name="kode_barang" class="form-control" required>
                        <option value="">Pilih Kode Barang</option>
                        <?php
                        $query_barang = $koneksi->query("SELECT kode_barang, nama_barang FROM gudang");
                        while ($barang = $query_barang->fetch_assoc()) {
                            echo "<option value='{$barang['kode_barang']}'>{$barang['kode_barang']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="nama_barang" class="form-label">Nama Barang</label>
                    <select id="nama_barang" name="nama_barang" class="form-control" required>
                        <option value="">Pilih Nama Barang</option>
                    </select>

                    <script>
                        $(document).ready(function () {
                            $("#kode_barang").change(function () {
                                var kode_barang = $(this).val(); // Ambil nilai kode_barang
                                if (kode_barang !== "") {
                                    $.ajax({
                                        url: "get_nama_barang.php", // File PHP untuk mengambil data
                                        method: "POST",
                                        data: { kode_barang: kode_barang },
                                        dataType: "json",
                                        success: function (data) {
                                            $("#nama_barang").empty();
                                            $("#nama_barang").append("<option value=''>Pilih Nama Barang</option>");

                                            $.each(data, function (key, value) {
                                                $("#nama_barang").append("<option value='" + value.nama_barang + "'>" + value.nama_barang + "</option>");
                                            });
                                        },
                                        error: function () {
                                            alert("Gagal mengambil data nama barang.");
                                        }
                                    });
                                } else {
                                    $("#nama_barang").empty();
                                    $("#nama_barang").append("<option value=''>Pilih Nama Barang</option>");
                                }
                            });
                        });
                    </script>

                </div>

                    <div class="col-md-6">
                        <label for="kondisi" class="form-label">Kondisi</label>
                        <select name="kondisi" class="form-control" required>
                            <option value="">Pilih Kondisi</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jumlah" class="form-label">Jumlah Masuk</label>
                        <input type="number" name="jumlah" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <button type="submit" name="simpan" class="btn btn-success">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="?page=barangmasuk" class="btn btn-danger">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
