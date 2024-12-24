<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Barang Masuk</h6>
        </div>
        <div class="card-body">
            <!-- Tombol Tambah Barang Masuk -->
            <div class="mb-3">
            <a href="?page=barangmasuk&aksi=tambahbarangmasuk" class="btn btn-primary custom-btn">
                    <i class="fas fa-plus me-2"></i> Tambah Barang Masuk
                </a>
            </div>

            <!-- Tabel Data -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="barangMasuk">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Tanggal Masuk</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Jenis Barang</th>
                            <th>Jumlah Masuk</th>
                            <th>Keterangan</th>
                            <th>Satuan</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $no = 1;
                    $sql = $koneksi->query("
                        SELECT bm.*, g.kode_barang, g.nama_barang, g.jenis_barang, g.satuan 
                        FROM barang_masuk bm
                        LEFT JOIN gudang g ON bm.id_barang = g.id
                        ORDER BY bm.tanggal_masuk DESC
                    ");

                    if ($sql) {
                        while ($data = $sql->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($data['tanggal_masuk'])); ?></td>
                                <td><?php echo htmlspecialchars($data['kode_barang']); ?></td>
                                <td><?php echo htmlspecialchars($data['nama_barang']); ?></td>
                                <td><?php echo htmlspecialchars($data['jenis_barang']); ?></td>
                                <td><?php echo htmlspecialchars($data['jumlah_masuk']); ?></td>
                                <td><?php echo htmlspecialchars($data['keterangan']); ?></td>
                                <td><?php echo htmlspecialchars($data['satuan']); ?></td>
                                <td class="text-center">
                                    <a href="?page=barangmasuk&aksi=ubahbarangmasuk&id=<?php echo $data['id_barang_masuk']; ?>" 
                                    class="btn btn-info btn-sm custom-btn" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="?page=barangmasuk&aksi=hapusbarangmasuk&id=<?php echo $data['id_barang_masuk']; ?>" 
                                    class="btn btn-danger btn-sm custom-btn" 
                                    onclick="return confirm('Apakah anda yakin akan menghapus data ini?')" 
                                    title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php 
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'>Tidak ada data</td></tr>";
                    }
                    ?> 
                </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#barangMasuk').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel"></i> Excel',
                    className: 'btn btn-secondary btn-sm custom-btn'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf"></i> PDF',
                    className: 'btn btn-secondary btn-sm custom-btn'
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print"></i> Print',
                    className: 'btn btn-secondary btn-sm custom-btn'
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
            order: [[1, 'desc']], // Urutkan berdasarkan tanggal masuk
            pageLength: 10
        });
    });
</script>
<style>
    /* Styling yang sudah ada */
    .custom-btn {
        margin: 0 2px;
        padding: 0.25rem 0.5rem;
        transition: all 0.3s ease;
    }
    
    .custom-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    .btn-info.custom-btn {
        background: #36b9cc;
        border-color: #36b9cc;
        color: white;
    }
    
    .btn-danger.custom-btn {
        background: #e74a3b;
        border-color: #e74a3b;
        color: white;
    }
    
    .btn-info.custom-btn:hover {
        background: #2fa1b3;
        border-color: #2fa1b3;
    }
    
    .btn-danger.custom-btn:hover {
        background: #d52a1a;
        border-color: #d52a1a;
    }
    
    /* Tooltip styling */
    [title] {
        position: relative;
    }
    
    .table td {
        vertical-align: middle;
        padding: 0.75rem;
    }
</style>>