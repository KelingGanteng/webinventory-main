<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");
?>
<html>

<head>
    <title>Laporan Barang Keluar</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body>
    <div class="container">
        <h2>Laporan Barang Keluar</h2>
        <h4>(Inventory)</h4>
        <button id="generatePdf" class="btn btn-primary">Generate Laporan PDF</button>
    </div>

    <script>
        $(document).ready(function () {
            // Handle PDF generation when button is clicked
            $('#generatePdf').click(function () {
                // Fetch the data from the server using AJAX
                $.ajax({
                    url: 'fetch_data_barang_keluar.php',  // PHP file that fetches data from the database
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Define the PDF document structure
                        var docDefinition = {
                            content: [
                                { text: 'Laporan Barang Keluar', style: 'header' },
                                { text: 'Tanggal: ' + new Date().toLocaleDateString(), style: 'subheader' },
                                { text: '\n' },
                                { text: 'Berikut adalah laporan barang yang keluar dari gudang:', style: 'body' },
                                { text: '\n' },

                                // Loop through data and create a list for each item
                                ...data.map(item => ({
                                    text: 
                                        'Id Transaksi: ' + item.id_transaksi + '\n' +
                                        'Tanggal Keluar: ' + item.tanggal + '\n' +
                                        'Kode Barang: ' + item.kode_barang + '\n' +
                                        'Nama Barang: ' + item.nama_barang + '\n' +
                                        'Kondisi: ' + item.kondisi + '\n' +
                                        'Jumlah Keluar: ' + item.jumlah + '\n' +
                                        'Satuan: ' + item.satuan + '\n' +
                                        '\n',
                                    margin: [0, 0, 0, 10]  // Add some space after each entry
                                }))
                            ],
                            styles: {
                                header: { fontSize: 18, bold: true, alignment: 'center' },
                                subheader: { fontSize: 14, italics: true, alignment: 'center' },
                                body: { fontSize: 12 },
                            }
                        };

                        // Generate and download the PDF
                        pdfMake.createPdf(docDefinition).download('Laporan_Barang_Keluar.pdf');
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
