<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");
?>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Gudang</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .header {
            text-align: center;
            margin-top: 20px;
        }

        .logo {
            width: 100px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 30px;
            font-weight: bold;
            color: #007BFF;
        }

        .subheading {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }

        .btn-primary {
            background-color: #007BFF;
            border-color: #007BFF;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #aaa;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header with logo and title -->
        <div class="header">
            <!-- Logo -->
            <img src="Samco.png" alt="Logo" class="logo">
            <!-- Title -->
            <div class="title">Laporan Stock Gudang</div>
            <div class="subheading">Inventory Gudang Barang</div>
        </div>

        <!-- Generate PDF Button -->
        <button id="generatePdf" class="btn btn-primary">Generate Surat PDF</button>

        <!-- Footer (Optional) -->
        <div class="footer">
            <p>&copy; 2024, Inventory Management System</p>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Handle PDF generation when button is clicked
            $('#generatePdf').click(function () {
                // Fetch the data from the server using AJAX
                $.ajax({
                    url: 'fetch_data.php',  // PHP file that fetches data from the database
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Define the PDF document structure
                        var docDefinition = {
                            content: [
                                { text: 'Laporan Stock Gudang', style: 'header' },
                                { text: 'Tanggal: ' + new Date().toLocaleDateString(), style: 'subheader' },
                                { text: '\n' },
                                { text: 'Berikut Laporan Stok Gudang:', style: 'body' },
                                { text: '\n' },

                                // Loop through data and create a list for each item
                                ...data.map(item => ({
                                    text:
                                        'Kode Barang: ' + item.kode_barang + '\n' +

                                        'Nama Barang: ' + item.nama_barang + '\n' +

                                        'Kondisi: ' + item.kondisi + '\n' +

                                        'Jenis Barang: ' + item.jenis_barang + '\n' +

                                        'Jumlah: ' + item.jumlah + '\n' +

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
                        pdfMake.createPdf(docDefinition).download('Laporan_Stock_Gudang.pdf');
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