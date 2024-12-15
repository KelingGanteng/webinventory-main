<!-- Begin Page Content -->
<div class="container-fluid">
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Data Supplier</h6>
    </div>
    <div class="card-body">
      <!-- Tambah tombol di atas tabel -->
      <div class="mb-3">
        <a href="?page=supplier&aksi=tambahsupplier" class="btn btn-primary">
          <i class="fas fa-plus"></i> Tambah Barang
        </a>
      </div>
      <div class="table-responsive">
        <table class="table table-bordered" id="tb_supplier" width="100%" cellspacing="0">
          <!-- Include DataTables CSS -->
          <thead>
            <tr>

              <th>Kode Supplier</th>
              <th>Nama Supplier</th>
              <th>Alamat</th>
              <th>Telepon</th>
              <th>Pengaturan</th>

            </tr>
          </thead>


          <tbody>
            <?php

            $no = 1;
            $sql = $koneksi->query("SELECT * FROM tb_supplier ORDER BY kode_supplier");
            while ($data = $sql->fetch_assoc()) {

              ?>

              <tr>

                <td><?php echo $data['kode_supplier'] ?></td>
                <td><?php echo $data['nama_supplier'] ?></td>
                <td><?php echo $data['alamat'] ?></td>
                <td><?php echo $data['telepon'] ?></td>


                <td>
                  <a href="?page=supplier&aksi=ubahsupplier&kode_supplier=<?php echo $data['kode_supplier']; ?>"
                    class="btn btn-info btn-sm">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <a onclick="return confirm('Apakah anda yakin akan menghapus data ini?')"
                    href="?page=supplier&aksi=hapussupplier&id=<?php echo $data['kode_supplier'] ?>"
                    class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin?')">
                    <i class="fas fa-trash"></i> Hapus
                </td>
              </tr>
            <?php } ?>

          </tbody>
        </table>
        </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script>

  $(document).ready(function () {
    $('#tb_supplier').DataTable({
      dom: 'Bfrtip',
      buttons: [
        {
          s
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
      order: [[1, 'asc']], // Urutkan berdasarkan kode barang
      pageLength: 10 // Jumlah data per halaman
    });
  });
</script>