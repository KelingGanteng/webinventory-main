<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.4/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.4/dist/sweetalert2.min.js"></script>


<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.4/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.4/dist/sweetalert2.min.js"></script>


<!-- Inisialisasi DataTables untuk semua tabel -->
<script>
    $(document).ready(function () {
        // Konfigurasi default untuk semua DataTables
        $.extend(true, $.fn.dataTable.defaults, {
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
            pageLength: 10,
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
            ]
        });

        // Inisialisasi DataTables untuk setiap tabel
        $('#dataGudang, #dataSatuan, #dataJenisBarang').DataTable();
    });

    // Fungsi konfirmasi hapus dengan SweetAlert2
    function confirmDelete(id, nama) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: `Apakah Anda yakin ingin menghapus ${nama}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `?page=satuanbarang&aksi=hapussatuan&id=${id}`;
            }
        });
    }
</script>

<style>
    /* Custom styling untuk DataTables */
    .dataTables_wrapper .dt-buttons {
        margin-bottom: 1rem;
    }

    .dt-buttons .btn {
        margin-right: 0.5rem;
    }

    .table th {
        background-color: #f8f9fc;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .dataTables_filter {
        margin-bottom: 1rem;
    }
</style>