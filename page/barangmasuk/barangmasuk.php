<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Asset Management - Barang Masuk</h6>
        </div>
        <div class="card-body">
             <!-- Tambah tombol di atas tabel -->
            <div class="mb-3">
                <a href="?page=barangmasukr&aksi=tambahbarangmasuk" class="btn btn-primary custom-btn">
                    <i class="fas fa-plus me-2"></i> Tambah Barang
                </a>
            </div>
                <table class="table table-bordered" id="barangMasuk" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th style="width: 150px; text-align: center;">Kode Aset</th>
                            <th style="width: 150px; text-align: center;">Nama Aset</th>
                            <th style="width: 150px; text-align: center;">Tanggal Masuk</th>
                            <th style="width: 100px; text-align: center;">Jumlah</th>
                            <th style="width: 150px; text-align: center;">Harga</th>
                            <th style="width: 200px; text-align: center;">Pengaturan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $sql = $koneksi->query("
                            SELECT bm.*, aset.kode_lengkap, aset.nama_aset
                            FROM barang_masuk bm
                            LEFT JOIN aset ON bm.aset_id = aset.id
                        ");
                        
                        if ($sql === false) {
                            die('Error SQL: ' . $koneksi->error); // Tampilkan pesan error jika query gagal
                        } else
                            while ($data = $sql->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td style="text-align: center;"><?php echo $no++; ?></td>
                                    <td style="text-align: center;"><?php echo $data['kode_lengkap']; ?></td>
                                    <td style="text-align: center;"><?php echo $data['nama_aset']; ?></td>
                                    <td style="text-align: center;"><?php echo $data['tanggal_masuk']; ?></td>
                                    <td style="text-align: center;"><?php echo $data['jumlah']; ?></td>
                                    <td style="text-align: center;"><?php echo number_format($data['harga'], 2); ?></td>
                                    <td style="text-align: center;">
                                        <a href="?page=barangmasuk&aksi=ubahbarangmasuk&id=<?php echo $data['id']; ?>"
                                            class="btn btn-info btn-sm custom-btn">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="?page=barangmasuk&aksi=hapusbarangmasuk&id=<?php echo $data['id']; ?>"
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
        var table = $('#barangMasuk').DataTable({
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
    });
</script>
