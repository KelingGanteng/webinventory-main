<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");
?>
<html>

<head>
    <title>Laporan Barang Retur</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

</head>

<body>
    <div class="container">
        <h2>Laporan Barang Retur</h2>
        <h4>Inventory</h4>
        <button id="generatePdf" class="btn btn-primary mb-3">Generate Laporan PDF</button>
        <div class="data-tables datatable-dark">
            <table class="table table-bordered" id="barangretur" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                            <th>ID Retur</th>
                        <th>Tanggal Retur</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kondisi</th>
                        <th>Kerusakan</th>
                        <th>Jumlah Retur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $sql = $koneksi->query("SELECT * FROM barang_retur ORDER BY id_retur");
                    while ($data = $sql->fetch_assoc()) {
                        ?>
                            <tr>
                        <td>
                            <?php echo $no++; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($data['id_retur']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($data['tanggal_retur']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($data['kode_barang']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($data['nama_barang']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($data['kondisi']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($data['kerusakan']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($data['jumlah']); ?>
                        </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#barangretur').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pdf'
                ]
            });

            // Handle the Generate PDF button click
            $('#generatePdf').click(function () {
                // Fetch the data from the server using AJAX
                $.ajax({
                    url: 'fetch_data_barang_retur.php',  // PHP file to fetch data
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Check if data is available
                        if (data.length > 0) {
                            // Prepare the PDF document
                            var docDefinition = {
                                content: [
                                    { text: 'Laporan Barang Retur', style: 'header' },
                                    { text: 'Tanggal: ' + new Date().toLocaleDateString(), style: 'subheader' },
                                    { text: '\n' },
                                    { text: 'Berikut adalah laporan barang retur:', style: 'body' },
                                    { text: '\n' },

                                    // Add the data into the PDF
                                    ...data.map(item => ({
                                        text:
                                            'ID Retur: ' + item.id_retur + '\n' +
                                            'Tanggal Retur: ' + item.tanggal_retur + '\n' +
                                            'Kode Barang: ' + item.kode_barang + '\n' +
                                            'Nama Barang: ' + item.nama_barang + '\n' +
                                            'Kondisi: ' + item.kondisi + '\n' +
                                            'Kerusakan: ' + item.kerusakan + '\n' +
                                            'Jumlah Retur: ' + item.jumlah + '\n' +
                                            '\n',
                                        margin: [0, 0, 0, 10]  // Add margin after each entry
                                    }))
                                ],
                                styles: {
                                    header: { fontSize: 18, bold: true, alignment: 'center' },
                                    subheader: { fontSize: 14, italics: true, alignment: 'center' },
                                    body: { fontSize: 12 },
                                }
                            };

                            // Generate and download the PDF
                            pdfMake.createPdf(docDefinition).download('Laporan_Barang_Retur.pdf');
                        } else {
                            alert('Tidak ada data untuk laporan!');
                        }
                    },
                    error: function () {
                        alert('Terjadi kesalahan saat mengambil data!');
                    }
                });
            });
        });
    </script>
</body>

</html>