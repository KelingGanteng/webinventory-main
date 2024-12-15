<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");
?>
<html>

<head>
    <title>Laporan Barang Masuk</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body>
    <div class="container">
        <h2>Laporan Barang Masuk</h2>
        <h4>Inventory</h4>
        <button id="export-pdf" class="btn btn-primary">Generate Laporan PDF</button>
    </div>

    <script>
        $(document).ready(function () {
            // Handle PDF generation for a specific row when the button is clicked
            $('#export-pdf').click(function () {
                var idTransaksi = $(this).data('id');  // Get the ID of the selected row

                // Fetch the data for the selected row using AJAX
                $.ajax({
                    url: 'fetch_data_barang_masuk.php',  // PHP file to fetch data
                    method: 'GET',
                    data: { id_transaksi: idTransaksi },  // Send the ID to the server
                    dataType: 'json',
                    success: function (data) {
                        // Define the PDF structure
                        var docDefinition = {
                            content: [
                                // Header with company details
                                {
                                    text: 'PT SAMCO FARMA\nPharmaceutical & Chemical Industries',
                                    fontSize: 14,
                                    bold: true,
                                    alignment: 'center',
                                    margin: [0, 5]
                                },

                                // Divider line
                                { text: '', style: 'line' },

                                // Title and date
                                { text: 'Laporan Barang Masuk', style: 'header' },
                                { text: 'Tanggal: ' + new Date().toLocaleDateString(), style: 'subheader' },

                                // Description
                                { text: 'Berikut adalah laporan barang yang masuk ke gudang:', style: 'body' },

                                // Display the selected data in a simple layout (not a table)
                                {
                                    text: 'Id Transaksi: ' + data.id_transaksi,
                                    style: 'body'
                                },
                                {
                                    text: 'Tanggal Masuk: ' + data.tanggal,
                                    style: 'body'
                                },
                                {
                                    text: 'Kode Barang: ' + data.kode_barang,
                                    style: 'body'
                                },
                                {
                                    text: 'Nama Barang: ' + data.nama_barang,
                                    style: 'body'
                                },
                                {
                                    text: 'Kondisi: ' + data.kondisi,
                                    style: 'body'
                                },
                                {
                                    text: 'Jumlah Masuk: ' + data.jumlah,
                                    style: 'body'
                                }
                            ],
                            styles: {
                                header: { fontSize: 18, bold: true, alignment: 'center', color: '#dc3545' },
                                subheader: { fontSize: 14, italics: true, alignment: 'center', margin: [0, 10] },
                                body: { fontSize: 12, alignment: 'left', margin: [0, 5] },
                                line: { borderBottom: '1px solid #000', margin: [0, 10] }
                            }
                        };

                        // Generate and download the PDF
                        pdfMake.createPdf(docDefinition).download('Laporan_Barang_Masuk_' + data.id_transaksi + '.pdf');
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