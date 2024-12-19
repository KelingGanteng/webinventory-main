<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Barang Keluar</h6>
        </div>
        <div class="card-body">
            <!-- Tombol Tambah Barang -->
            <div class="mb-3">
                <a href="?page=barangkeluar&aksi=tambahbarangkeluar" class="btn btn-primary custom-btn">
                    <i class="fas fa-plus me-2"></i> Tambah Barang Keluar
                </a>
            </div>

            <!-- Filter Tanggal -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text">Dari</span>
                        <input type="date" id="min" name="min" class="form-control">
                        <span class="input-group-text">Sampai</span>
                        <input type="date" id="max" name="max" class="form-control">
                    </div>
                </div>
            </div>

            <!-- Tabel Barang Keluar -->
            <div class="table-responsive">
                <table class="table table-bordered" id="barangkeluar" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th style="width: 150px; text-align: center;">Tanggal Keluar</th>
                            <th style="width: 150px; text-align: center;">Kode Barang</th>
                            <th style="width: 200px; text-align: center;">Nama Barang</th>
                            <th style="width: 150px; text-align: center;">Jumlah Keluar</th>
                            <th style="width: 100px; text-align: center;">Satuan</th>
                            <th style="width: 200px; text-align: center;">Pengaturan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = $koneksi->query("SELECT * FROM barang_keluar");
                        while ($data = $sql->fetch_assoc()) {
                            ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $no++; ?></td>
                                <td style="text-align: center;"><?php echo $data['tanggal'] ?></td>
                                <td style="text-align: center;"><?php echo $data['kode_barang'] ?></td>
                                <td style="text-align: center;"><?php echo $data['nama_barang'] ?></td>
                                <td style="text-align: center;"><?php echo $data['jumlah'] ?></td>
                                <td style="text-align: center;"><?php echo $data['satuan'] ?></td>
                                <td style="text-align: center;">
                                    <a href="?page=barangkeluar&aksi=ubahbarangkeluar&id=<?php echo $data['id']; ?>"
                                        class="btn btn-info btn-sm custom-btn">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="?page=barangkeluar&aksi=hapusbarangkeluar&id=<?php echo $data['id']; ?>"
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

<!-- DataTable Initialization Script -->
<script>
    $(document).ready(function () {
        var table = $('#barangkeluar').DataTable({
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
            order: [[1, 'desc']] // Urutkan berdasarkan tanggal keluar
        });

        // Filter tanggal
        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var min = $('#min').val();
                var max = $('#max').val();
                var date = data[1]; // Index 1 adalah kolom tanggal

                if (min === "" && max === "") return true;
                if (min === "") return date <= max;
                if (max === "") return date >= min;
                return date >= min && date <= max;
            }
        );

        $('#min, #max').on('change', function () {
            table.draw();
        });
    });
</script>