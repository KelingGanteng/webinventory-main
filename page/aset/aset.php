    <div class="modal fade" id="detailModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Aset</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Kode Aset</th>
                                    <td id="modal-kode"></td>
                                </tr>
                                <tr>
                                    <th>Nama Barang</th>
                                    <td id="modal-barang"></td>
                                </tr>
                                <tr>
                                    <th>Departemen</th>
                                    <td id="modal-departemen"></td>
                                </tr>
                                <tr>
                                    <th>Karyawan</th>
                                    <td id="modal-karyawan"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Bagian</th>
                                    <td id="modal-bagian"></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td id="modal-status"></td>
                                </tr>
                             <!-- Di dalam modal -->
                                <tr>
                                    <th>
                                        <span id="modal-tanggal-label">Tanggal</span>
                                    </th>
                                    <td id="modal-tanggal"></td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td id="modal-keterangan"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

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
                                <th style="width: 150px; text-align: center;">Nama Barang Aset</th>
                                <th style="width: 150px; text-align: center;">Departemen</th>
                                <th style="width: 100px; text-align: center;">Karyawan</th>
                                <th style="width: 100px; text-align: center;">Bagian</th>
                                <th style="width: 100px; text-align: center;">Status</th>
                                <th style="width: 150px; text-align: center;">Tanggal</th>
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
                                    jenis_barang.jenis_barang AS nama_jenis_barang,  
                                    gudang.nama_barang as gudang_id
                                FROM aset
                                LEFT JOIN daftar_karyawan ON aset.karyawan_id = daftar_karyawan.id
                                LEFT JOIN departemen ON aset.departemen_id = departemen.id
                                LEFT JOIN jenis_barang ON aset.jenis_barang_id = jenis_barang.id
                                LEFT JOIN gudang ON aset.gudang_id = gudang.id
                            ");        

                            if ($sql === false) {
                                die('Error SQL: ' . $koneksi->error);
                            } else
                                while ($data = $sql->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $no++; ?></td>
                                        <td style="text-align: center;"><?php echo $data['kode_lengkap'] ?></td>
                                        <td style="text-align: center;"><?php echo $data['gudang_id']; ?></td>
                                        <td style="text-align: center;"><?php echo $data['nama_departemen'] ?></td>
                                        <td style="text-align: center;"><?php echo $data['nama_karyawan'] ?></td>
                                        <td style="text-align: center;"><?php echo $data['bagian'] ?></td>
                                        <td style="text-align: center;"><?php echo $data['status'] ?></td>
                                        <td style="text-align: center;">
                                            <?php 
                                            // Menentukan label dan tanggal berdasarkan status
                                            if ($data['status'] == 'Aktif') {
                                                $tanggal_label = "Tanggal Penyerahan";
                                                $tanggal = !empty($data['tanggal_pembelian']) ? date('d-m-Y', strtotime($data['tanggal_pembelian'])) : '-';
                                            } else if ($data['status'] == 'Tidak Aktif') {
                                                $tanggal_label = "Tanggal Pengembalian";
                                                $tanggal = !empty($data['tanggal_keluar']) ? date('d-m-Y', strtotime($data['tanggal_keluar'])) : '-';
                                            } else {
                                                $tanggal_label = "Tanggal";
                                                $tanggal = '-';
                                            }
                                            echo $tanggal;
                                            ?>
                                        </td>   
                                        <td style="text-align: center;">
                                       <!-- Update button view -->
                                        <button type="button" 
                                            class="btn btn-primary btn-sm custom-btn view-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#detailModal"
                                            data-kode="<?php echo htmlspecialchars($data['kode_lengkap']); ?>"
                                            data-barang="<?php echo htmlspecialchars($data['gudang_id']); ?>"
                                            data-departemen="<?php echo htmlspecialchars($data['nama_departemen']); ?>"
                                            data-karyawan="<?php echo htmlspecialchars($data['nama_karyawan']); ?>"
                                            data-bagian="<?php echo htmlspecialchars($data['bagian']); ?>"
                                            data-status="<?php echo htmlspecialchars($data['status']); ?>"
                                            data-tanggal="<?php echo $tanggal; ?>"
                                            data-tanggal-label="<?php echo $tanggal_label; ?>"
                                            data-keterangan="<?php echo htmlspecialchars($data['keterangan_keluar'] ?? '-'); ?>">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                            <a href="?page=aset&aksi=ubahaset&id=<?php echo $data['id']; ?>"
                                                class="btn btn-info btn-sm custom-btn">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <?php if($data['status'] == 'Aktif'): ?>
                                                <a href="?page=aset&aksi=return&id=<?php echo $data['id']; ?>"
                                                    class="btn btn-warning btn-sm custom-btn">
                                                    <i class="fas fa-undo"></i> Return
                                                </a>
                                            <?php else: ?>
                                                <a href="?page=aset&aksi=serahkan&id=<?php echo $data['id']; ?>"
                                                    class="btn btn-success btn-sm custom-btn">
                                                    <i class="fas fa-share"></i> Serahkan
                                                </a>
                                            <?php endif; ?>
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


        /* Tambahkan style ini */
        .modal {
            pointer-events: none;
        }

        .modal-dialog {
            pointer-events: all;
        }

        .modal-content {
            pointer-events: all;
        }

        /* Perbaikan untuk tombol close */
        .modal-header .btn-close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            z-index: 1;
            background-color: transparent;
            border: none;
            color: white;
        }

        .modal-header .btn-close:focus {
            box-shadow: none;
            outline: none;
        }

        /* Mencegah modal berkedip */
        .modal.fade .modal-dialog {
            transition: transform .3s ease-out;
            transform: translate(0, -50px);
        }

        .modal.show .modal-dialog {
            transform: none;
        }


        .modal-header {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        color: white;
        padding: 1rem;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        }

        .modal-header .btn-close {
            color: white;
            opacity: 0.8;
            transition: opacity 0.3s;
            padding: 1rem;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
            color: white;
        }


        .modal-body table th {
            width: 150px;
            color: #6a11cb;
        }

        .view-btn {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
        }

        .modal-content {
        border-radius: 15px;
        }


        .modal-footer {
        border-top: 1px solid #dee2e6;
        padding: 1rem;
        }

        .modal-footer .btn-secondary {
        background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        border: none;
        padding: 0.5rem 1.5rem;
        }

        .table-borderless th, .table-borderless td {
            padding: 8px 0;
        }

        /* Hapus pointer-events dari modal */
        .modal {
            z-index: 1050;
        }

        .modal-backdrop {
        z-index: 1040;
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

        /* Perbaikan untuk tombol dalam tabel */
        .table td .btn-sm {
            margin: 2px;
            padding: 0.25rem 0.5rem;
            display: inline-block;
        }

        .table td {
            vertical-align: middle;
        }

        /* Mengatur lebar kolom aksi */
        .table th:last-child,
        .table td:last-child {
            min-width: 200px;
            white-space: nowrap;
        }    
    </style>

    <!-- Tooltip Initialization (Bootstrap 5) -->
    <script>
    // Perbaikan Tooltip Initialization
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi semua tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover'
            });
        });
    });

    // Atau jika menggunakan jQuery
    $(document).ready(function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover'
            });
        });
    });
    </script>

    <!-- Setelah semua HTML, sebelum closing body -->
    <script>
   // Update script untuk modal
    $(document).ready(function() {
        // Inisialisasi modal
        var detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
        
        // Event handler untuk tombol view
        $(document).on('click', '.view-btn', function(e) {
            e.preventDefault();
            
            // Ambil data dari button
            var kode = $(this).data('kode');
            var barang = $(this).data('barang');
            var departemen = $(this).data('departemen');
            var karyawan = $(this).data('karyawan');
            var bagian = $(this).data('bagian');
            var status = $(this).data('status');
            var tanggal = $(this).data('tanggal');
            var tanggalLabel = $(this).data('tanggal-label');
            var keterangan = $(this).data('keterangan');
            
            // Set nilai ke dalam modal
            $('#modal-kode').text(kode || '-');
            $('#modal-barang').text(barang || '-');
            $('#modal-departemen').text(departemen || '-');
            $('#modal-karyawan').text(karyawan || '-');
            $('#modal-bagian').text(bagian || '-');
            $('#modal-status').text(status || '-');
            $('#modal-tanggal-label').text(tanggalLabel || 'Tanggal');
            $('#modal-tanggal').text(tanggal || '-');
            $('#modal-keterangan').text(keterangan || '-');
            
            // Tampilkan modal
            $('#detailModal').modal('show');
        });

        // Event handler untuk tombol close
        $('.btn-close, .btn-secondary').on('click', function() {
            $('#detailModal').modal('hide');
        });
    });
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

            // Filter berdasarkan tanggal
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var min = $('#min').val();
                    var max = $('#max').val();
                    var date = data[7]; // Sesuaikan dengan index kolom tanggal

                    if (min === "" && max === "") return true;
                    if (date === '-') return false;
                    
                    if (date) {
                        var parts = date.split('-');
                        date = parts[2] + '-' + parts[1] + '-' + parts[0];
                    }

                    if (min === "") return date <= max;
                    if (max === "") return date >= min;
                    return date >= min && date <= max;
                }
            );

            // Filter berdasarkan status
            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var statusFilter = $('#statusFilter').val();
                    var status = data[6]; // Kolom Status

                    if (statusFilter === "") return true;
                    return status === statusFilter;
                }
            );

            // Event listeners untuk filter
            $('#statusFilter').on('change', function () {
                table.draw();
            });

            $('#min, #max').on('change', function () {
                table.draw();
            });
        });
    </script>