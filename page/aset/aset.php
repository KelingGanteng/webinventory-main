<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Asset Inventaris Karyawan</h6>
        </div>
        <div class="card-body">
            <!-- Tombol Tambah Barang -->
            <div class="mb-3">
                <a href="?page=aset&aksi=tambahaset" class="btn btn-primary custom-btn">
                    <i class="fas fa-plus me-2"></i> Tambah Aset
                </a>
            </div>


             <!-- Filter Tanggal dan Status -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">Dari</span>
                        <input type="date" id="min" name="min" class="form-control">
                        <span class="input-group-text">Sampai</span>
                        <input type="date" id="max" name="max" class="form-control">
                    </div>
                </div>
                <!-- Filter Status -->
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">Status</span>
                        <select id="statusFilter" name="status" class="form-control">
                        <option value="">Semua</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>

                    </div>
                </div>
            </div>
            <!-- Tabel Barang Masuk -->
            <div class="table-responsive">
                <table class="table table-bordered" id="aset" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th style="width: 150px; text-align: center;">Kode Aset</th>
                            <th style="width: 150px; text-align: center;">Jenis Barang</th>
                            <th style="width: 150px; text-align: center;">Nama Barang Aset</th>
                            <th style="width: 150px; text-align: center;">Departemen</th>
                            <th style="width: 100px; text-align: center;">Karyawan</th>
                            <th style="width: 100px; text-align: center;">Bagian</th>
                            <th style="width: 100px; text-align: center;">Status</th>
                            <th style="width: 150px; text-align: center;">Tanggal Penyerahan</th>
                            <th style="width: 200px; text-align: center;">Pengaturan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = $koneksi->query("
                        SELECT aset.*, 
                                daftar_karyawan.nama AS nama_karyawan, 
                                daftar_karyawan.bagian, 
                                departemen.nama AS nama_departemen, 
                                jenis_barang.jenis_barang,
                                gudang.nama_barang as gudang_id
                            FROM aset
                            LEFT JOIN daftar_karyawan ON aset.karyawan_id = daftar_karyawan.id
                            LEFT JOIN departemen ON aset.departemen_id = departemen.id
                            LEFT JOIN jenis_barang ON aset.jenis_barang_id = jenis_barang.id
                            LEFT JOIN gudang ON aset.gudang_id = gudang.id
                        ") ;        

                    
                        if ($sql === false) {
                            die('Error SQL: ' . $koneksi->error); // Tampilkan pesan error jika query gagal
                        } else
                            while ($data = $sql->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $no++; ?></td>
                                    <td style="text-align: center;"><?php echo $data['kode_lengkap'] ?></td>
                                    <td style="text-align: center;"><?php echo $data['jenis_barang'] ?></td>
                                    <td style="text-align: center;"><?php echo $data['gudang_id']; ?></td>
                                    <td style="text-align: center;"><?php echo $data['nama_departemen'] ?></td>
                                    <td style="text-align: center;"><?php echo $data['nama_karyawan'] ?></td>
                                    <td style="text-align: center;"><?php echo $data['bagian'] ?></td>
                                    <td style="text-align: center;"><?php echo $data['status'] ?></td>
                                    <td style="text-align: center;"><?php echo $data['tanggal_pembelian'] ?></td>
                                    <td style="text-align: center;">
                                        <a href="?page=aset&aksi=ubahaset&id=<?php echo $data['id']; ?>"
                                            class="btn btn-info btn-sm custom-btn">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="?page=aset&aksi=hapusaset&id=<?php echo $data['id']; ?>"
                                            class="btn btn-danger btn-sm custom-btn"
                                            onclick="return confirm('Apakah anda yakin akan menghapus data ini?')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>

                                    </td>
                                </tr>
                            <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- CSS for Custom Button Styling -->
<style>
    /* Custom button styling */
    .custom-btn {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    /* Button hover effect */
    .custom-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    }

    /* Button focus effect */
    .custom-btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
    }

    /* Tooltip */
    .custom-btn[data-bs-toggle="tooltip"] {
        position: relative;
    }

    /* Style for the table buttons */
    .btn-sm {
        font-size: 0.9rem;
    }

    /* Button spacing in table */
    .btn-sm i {
        margin-right: 5px;
    }

    /* Buttons for the DataTable */
    .dt-buttons .btn {
        background-color: #007bff;
        color: white;
        border: none;
        font-size: 0.875rem;
        padding: 5px 10px;
        margin: 0 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .dt-buttons .btn:hover {
        background-color: #0056b3;
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .dt-buttons .btn i {
        margin-right: 5px;
    }
</style>

<!-- Tooltip Initialization (Bootstrap 5) -->
<script>
    var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>

<!-- DataTable Initialization Script -->
<script>
    $(document).ready(function () {
        var table = $('#aset').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy"></i> Copy',
                    className: 'btn btn-secondary btn-sm'
                },
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv"></i> CSV',
                    className: 'btn btn-secondary btn-sm'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-secondary btn-sm'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-secondary btn-sm'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-secondary btn-sm'
                }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Data tidak ditemukan",
                info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                infoEmpty: "Tidak ada data yang tersedia",
                infoFiltered: "(difilter dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            order: [[2, 'desc']]
        });

        // Menambahkan filter berdasarkan tanggal
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var min = $('#min').val();
                var max = $('#max').val();
                var date = data[6]; // Kolom Tanggal Pembelian (index 6)

                if (min === "" && max === "") return true;
                if (min === "") return date <= max;
                if (max === "") return date >= min;
                return date >= min && date <= max;
            }
        );

      // Menambahkan filter berdasarkan status
      // Menambahkan filter berdasarkan status
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var statusFilter = $('#statusFilter').val(); // Ambil nilai filter
                var status = data[6]; // Kolom Status (index 6 dalam tabel)

                if (statusFilter === "") return true; // Tampilkan semua jika tidak ada filter
                return status === statusFilter; // Cocokkan nilai status secara case-sensitive
            }
        );

// Event listener untuk filter status
$('#statusFilter').on('change', function () {
    table.draw();
});

        // Event listener untuk filter tanggal
        $('#min, #max').on('change', function () {
            table.draw();
        });

        // Event listener untuk filter status
        $('#statusFilter').on('change', function () {
            table.draw();
        });
    });
</script>