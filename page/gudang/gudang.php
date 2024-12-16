<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Stok Gudang</h6>
    </div>
    <div class="card-body">
      <!-- Tombol Tambah Barang dan Export Table -->
      <div class="mb-3">
        <a href="?page=gudang&aksi=tambahgudang" class="btn btn-primary custom-btn">
          <i class="fas fa-plus me-2"></i> Tambah Barang
        </a>
      </div>


      <!-- Tabel Data -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="gudang">
          <thead>
            <tr>
              <th style="width: 50px; text-align: center; vertical-align: middle;">No</th>
              <th style="width: 200px; text-align: center; vertical-align: middle;">Kode Barang</th>
              <th style="width: 150px; text-align: center; vertical-align: middle;">Nama Barang</th>
              <th style="width: 120px; text-align: center; vertical-align: middle;">Jenis Barang</th>
              <th style="width: 120px; text-align: center; vertical-align: middle;">Jumlah</th>
              <th style="width: 120px; text-align: center; vertical-align: middle;">Satuan Barang</th>
              <th style="width: 120px; text-align: center; vertical-align: middle;">Pengaturan</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1;
            $sql = $koneksi->query("SELECT * FROM gudang ORDER BY kode_barang");
            while ($data = $sql->fetch_assoc()) {
              ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo htmlspecialchars($data['kode_barang']); ?></td>
                <td><?php echo htmlspecialchars($data['nama_barang']); ?></td>
                <td><?php echo htmlspecialchars($data['jenis_barang']); ?></td>
                <td><?php echo htmlspecialchars($data['jumlah']); ?></td>
                <td><?php echo htmlspecialchars($data['satuan']); ?></td>
                <td class="text-center">
                  <a href="?page=gudang&aksi=ubahgudang&kode_barang=<?php echo $data['kode_barang']; ?>"
                    class="btn btn-info btn-sm custom-btn">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <a href="?page=gudang&aksi=hapusgudang&kode_barang=<?php echo $data['kode_barang']; ?>"
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

<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  $(document).ready(function () {
    $('#gudang').DataTable({
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'copy',
          text: '<i class="fas fa-copy"></i> Copy',
          className: 'btn btn-secondary btn-sm custom-btn'
        },
        {
          extend: 'csv',
          text: '<i class="fas fa-file-csv"></i> CSV',
          className: 'btn btn-secondary btn-sm custom-btn'
        },
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
      order: [[1, 'asc']], // Urutkan berdasarkan kode barang
      pageLength: 10 // Jumlah data per halaman
    });
  });
</script>

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

  .custom-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
  }

  .custom-btn:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.5);
  }

  /* DataTable buttons styling */
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

  /* Table styling */
  .table th,
  .table td {
    padding: 12px;
    text-align: center;
    vertical-align: middle;
  }

  /* Column width */
  .table th:nth-child(1),
  .table td:nth-child(1) {
    width: 50px;
  }

  .table th:nth-child(2),
  .table td:nth-child(2) {
    width: 200px;
  }

  .table th:nth-child(3),
  .table td:nth-child(3) {
    width: 150px;
  }

  .table th:nth-child(4),
  .table td:nth-child(4) {
    width: 120px;
  }

  /* Table header styling */
  .table thead th {
    background-color: #f8f9fc;
    font-weight: bold;
  }

  /* Pagination button styling */
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

  .dataTables_wrapper .dataTables_info {
    font-size: 0.875rem;
    color: #6c757d;
  }
</style>