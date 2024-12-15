<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Karyawan</h6>
        </div>
        <div class="card-body">
            <!-- Tambah tombol di atas tabel -->
            <div class="mb-3">
                <a href="?page=daftarkaryawan&aksi=tambahdaftarkaryawan" class="btn btn-primary custom-btn">
                    <i class="fas fa-plus me-2"></i> Tambah Daftar Karyawan
                </a>
            </div>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="daftarkaryawan">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center; vertical-align: middle;">No</th>
                            <th style="width: 200px; text-align: center; vertical-align: middle;">Nama Karyawan</th>
                            <th style="width: 200px; text-align: center; vertical-align: middle;">Bagian</th>
                            <th style="width: 150px; text-align: center; vertical-align: middle;">Departemen</th>
                            <th style="width: 120px; text-align: center; vertical-align: middle;">Pengaturan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query untuk mengambil data dari tabel daftar_karyawan dan departemen
                        $sql = $koneksi->query("
                        SELECT dk.id, dk.nama AS daftar_karyawan, dk.bagian, d.nama AS departemen
                        FROM daftar_karyawan dk
                        LEFT JOIN departemen d ON dk.departemen_id = d.id
                        ORDER BY dk.id ASC
                    ");

                        while ($data = $sql->fetch_assoc()) {
                            ?>
                            <tr>
                                <td style="text-align: center;"></td> <!-- Biarkan DataTable yang mengatur nomor urut -->
                                <td style="text-align: center;"><?php echo htmlspecialchars($data['daftar_karyawan']); ?>
                                </td>
                                <td style="text-align: center;"><?php echo htmlspecialchars($data['bagian']); ?>
                                </td>
                                <td style="text-align: center;"><?php echo htmlspecialchars($data['departemen']); ?></td>
                                <td style="text-align: center;">
                                    <a href="?page=daftarkaryawan&aksi=ubahdaftarkaryawan&id=<?php echo $data['id']; ?>"
                                        class="btn btn-info btn-sm custom-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?page=daftarkaryawan&aksi=hapusdaftarkaryawan&id=<?php echo $data['id']; ?>"
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

<!-- Tooltip Initialization (Bootstrap 5) -->
<script>
    var tooltipTriggerList = Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
</script>

<!-- DataTable Initialization -->
<script>
    $(document).ready(function () {
        // Inisialisasi DataTable dengan tombol export
        var table = $('#daftarkaryawan').DataTable({
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
            order: [[1, 'asc']], // Urutkan berdasarkan nama karyawan
            pageLength: 10, // Jumlah data per halaman
            rowCallback: function (row, data, index) {
                // Menambahkan nomor urut berdasarkan index (1-based index)
                $('td:eq(0)', row).html(index + 1);
            }
        });

        // Inisialisasi Select2 pada input pencarian DataTable
        $('#daftarkaryawan_filter input').select2({
            placeholder: "Cari Karyawan",
            allowClear: true,
            width: '100%' // Agar seleksi input memenuhi lebar kolom pencarian
        });
    });
</script>

<!-- Tambahkan library Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Custom Button and Table Styling -->
<style>
    /* Custom button styling for "Tambah Daftar Karyawan" */
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

    .custom-btn:focus {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
    }

    /* DataTable button styling */
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

    /* Styling for table headers and rows */
    .table th,
    .table td {
        padding: 12px;
        text-align: center;
        vertical-align: middle;
    }

    .table thead th {
        background-color: #f8f9fc;
        font-weight: bold;
    }

    /* Styling for pagination */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 5px 10px;
        margin: 2px;
        background-color: #007bff;
        color: white;
        border-radius: 5px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #0056b3;
    }
</style>