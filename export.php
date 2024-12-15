<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");
?>
<html>

<head>
    <title>Laporan Barang Masuk</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>
    <div class="container">
        <h2>Barang Masuk</h2>
        <h4>(Inventory)</h4>
        <div class="data-tables datatable-dark">

            <table class="table table-bordered" id="barangmasuk" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th style="width: 150px; text-align: center;">Id Transaksi</th>
                        <th style="width: 150px; text-align: center;">Tanggal Masuk</th>
                        <th style="width: 150px; text-align: center;">Kode Barang</th>
                        <th style="width: 200px; text-align: center;">Nama Barang</th>
                        <th style="width: 100px; text-align: center;">Kondisi</th>
                        <th style="width: 150px; text-align: center;">Jumlah Masuk</th>
                        <th style="width: 100px; text-align: center;">Satuan</th>
                        <th style="width: 200px; text-align: center;">Pengaturan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = $koneksi->query("SELECT * FROM barang_masuk");
                    while ($data = $sql->fetch_assoc()) {
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $no++; ?></td>
                            <td style="text-align: center;"><?php echo $data['id_transaksi'] ?></td>
                            <td style="text-align: center;"><?php echo $data['tanggal'] ?></td>
                            <td style="text-align: center;"><?php echo $data['kode_barang'] ?></td>
                            <td style="text-align: center;"><?php echo $data['nama_barang'] ?></td>
                            <td style="text-align: center;"><?php echo $data['kondisi'] ?></td>
                            <td style="text-align: center;"><?php echo $data['jumlah'] ?></td>
                            <td style="text-align: center;"><?php echo $data['satuan'] ?></td>
                            <td style="text-align: center;">
                                </a>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#barangmasuk').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });

    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>



</body>

</html>