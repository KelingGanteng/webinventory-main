<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");
?>
<html>

<head>
    <title>Stock Gudang</title>
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
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body>
    <div class="container">
        <h2>Stock Gudang</h2>
        <h4>Inventory</h4>
        <div class="data-tables datatable-dark">

            <table class="table table-bordered" id="gudang" width="100%" cellspacing="0">
                <!-- Include DataTables CSS -->
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kondisi</th>
                        <th>Jenis Barang</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
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
                            <td><?php echo htmlspecialchars($data['kondisi']); ?></td>
                            <td><?php echo htmlspecialchars($data['jenis_barang']); ?></td>
                            <td><?php echo htmlspecialchars($data['jumlah']); ?></td>
                            <td><?php echo htmlspecialchars($data['satuan']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#gudang').DataTable({
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