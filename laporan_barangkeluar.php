<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Laporan Barang Keluar</h6>
    </div>
    <div class="card-body">

      <!-- Filter Tanggal -->
      <form action="laporan_barangkeluar.php" method="post">

        <!-- Date Range Filter -->
        <div class="mb-3">
          <div class="input-group">
            <span class="input-group-text">Dari</span>
            <input type="date" id="min" name="min" class="form-control">
            <span class="input-group-text">Sampai</span>
            <input type="date" id="max" name="max" class="form-control">
          </div>
        </div>

        <!-- Export All Button -->
        <div class="mb-3">
          <a href="export2.php" class="btn btn-primary custom-btn">
            <i class="fas fa-download me-2"></i> Export All
          </a>
        </div>

        <!-- Data Table -->
        <div class="table-responsive">
          <table class="table table-bordered" id="barangkeluar" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th style="width: 50px; text-align: center;">No</th>
                <th style="width: 150px; text-align: center;">Id Transaksi</th>
                <th style="width: 150px; text-align: center;">Tanggal Keluar</th>
                <th style="width: 150px; text-align: center;">Kode Barang</th>
                <th style="width: 200px; text-align: center;">Nama Barang</th>
                <th style="width: 100px; text-align: center;">Kondisi</th>
                <th style="width: 150px; text-align: center;">Jumlah Keluar</th>
                <th style="width: 100px; text-align: center;">Satuan</th>
                <th style="width: 200px; text-align: center;">Pengaturan</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Start Numbering
              $no = 1;

              // Fetch data from database with proper ordering
              $sql = $koneksi->query("
                SELECT barang_keluar.*, 
                       gudang.satuan, 
                       daftar_karyawan.nama AS nama_karyawan, 
                       departemen.nama AS nama_departemen
                FROM barang_keluar 
                INNER JOIN gudang ON barang_keluar.kode_barang = gudang.kode_barang
                LEFT JOIN daftar_karyawan ON barang_keluar.karyawan_id = daftar_karyawan.id
                LEFT JOIN departemen ON daftar_karyawan.departemen_id = departemen.id
                ORDER BY barang_keluar.tanggal DESC
              ");

              // Loop through each data row
              while ($data = $sql->fetch_assoc()) {
                // Format the date (if needed)
                $tanggalKeluar = $data['tanggal'];
                ?>
                <tr>
                  <td style="text-align: center;"><?php echo $no++; ?></td> <!-- Auto-increment number -->
                  <td style="text-align: center;"><?php echo $data['id_transaksi']; ?></td> <!-- Display Id Transaksi -->
                  <td style="text-align: center;"><?php echo $tanggalKeluar; ?></td> <!-- Display Tanggal Keluar -->
                  <td style="text-align: center;"><?php echo $data['kode_barang']; ?></td> <!-- Display Kode Barang -->
                  <td style="text-align: center;"><?php echo $data['nama_barang']; ?></td> <!-- Display Nama Barang -->
                  <td style="text-align: center;"><?php echo $data['kondisi']; ?></td> <!-- Display Kondisi -->
                  <td style="text-align: center;"><?php echo $data['jumlah']; ?></td> <!-- Display Jumlah Keluar -->
                  <td style="text-align: center;"><?php echo $data['satuan']; ?></td> <!-- Display Satuan -->
                  <td style="text-align: center;">
                    <!-- Export to PDF -->
                    <a href="export_laporan_barangkeluar_pdf.php?id=<?php echo $data['id_transaksi']; ?>"
                      class="btn btn-sm btn-primary custom-btn">
                      <i class="fas fa-file-pdf me-2"></i> Export PDF
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
</style>

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
      order: [[2, 'desc']] // Urutkan berdasarkan tanggal (Tanggal Keluar)
    });

    // Custom search functionality for date range filter
    $.fn.dataTable.ext.search.push(
      function (settings, data, dataIndex) {
        var min = $('#min').val();
        var max = $('#max').val();
        var date = data[2]; // column index 2 (Tanggal Keluar)

        // Jika min dan max ada, bandingkan tanggal
        if (min && max) {
          return date >= min && date <= max;
        }
        if (min && !max) {
          return date >= min;
        }
        if (!min && max) {
          return date <= max;
        }
        return true; // Tidak ada filter tanggal
      }
    );

    // Trigger filter when changing the dates
    $('#min, #max').on('change', function () {
      table.draw();
    });
  });
</script>