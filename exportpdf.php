<?php
$koneksi = new mysqli("localhost", "root", "", "webinventory");

// Fungsi untuk mengonversi gambar ke base64
function base64_encode_image($image_path)
{
    $image_data = file_get_contents($image_path);
    return 'data:image/png;base64,' . base64_encode($image_data);
}

$logo_base64 = base64_encode_image("Samco.png"); // Ganti dengan jalur gambar yang benar
?>

<html>

<head>
    <title>Laporan Barang Masuk</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header img {
            width: 150px;
            /* Ukuran logo */
            margin-bottom: 15px;
        }

        .header h2 {
            font-size: 28px;
            font-weight: bold;
        }

        .header h4 {
            font-size: 18px;
            font-weight: normal;
            color: #555;
        }

        .report-title h3 {
            text-align: center;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .report-subtitle h5 {
            text-align: center;
            font-size: 18px;
            font-weight: normal;
            margin-bottom: 40px;
            color: #777;
        }

        .text-center button {
            font-size: 16px;
            padding: 10px 20px;
            margin: 5px;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header with Logo -->
        <div class="header">
            <img src="Samco.png" alt="Logo">
            <h2>Laporan Barang Masuk</h2>
            <h4>Inventory Management</h4>
        </div>

        <!-- Report Title and Subtitle -->
        <div class="report-title">
            <h3>Daftar Barang Masuk</h3>
        </div>
        <div class="report-subtitle">
            <h5>Menampilkan data barang yang masuk ke dalam inventori</h5>
        </div>

        <!-- Data List -->
        <div class="item-list">
            <?php
            $no = 1;
            $barangMasukData = [];
            $sql = $koneksi->query("SELECT * FROM barang_masuk");
            while ($data = $sql->fetch_assoc()) {
                $barangMasukData[] = $data;
            }
            ?>
            <?php foreach ($barangMasukData as $item): ?>
                <div class="item">
                    <h5>Transaksi ID: <?php echo $item['id_transaksi']; ?></h5>
                    <p><strong>Tanggal Masuk:</strong> <?php echo $item['tanggal']; ?></p>
                    <p><strong>Kode Barang:</strong> <?php echo $item['kode_barang']; ?></p>
                    <p><strong>Nama Barang:</strong> <?php echo $item['nama_barang']; ?></p>
                    <p><strong>Kondisi:</strong> <?php echo $item['kondisi']; ?></p>
                    <p><strong>Jumlah Masuk:</strong> <?php echo $item['jumlah']; ?></p>
                    <p><strong>Satuan Barang:</strong> <?php echo $item['satuan']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Export Buttons -->
        <div class="text-center">
            <button class="btn btn-success" id="exportCsv">Export CSV</button>
            <button class="btn btn-danger" id="exportPdf">Export PDF</button>
            <button class="btn btn-info" id="printReport">Print</button>
        </div>
    </div>

    <script>
        var barangMasukData = <?php echo json_encode($barangMasukData); ?>;

        $('#exportPdf').click(function () {
            const { jsPDF } = window.jspdf;
            var doc = new jsPDF();
            doc.setFont("helvetica");
            doc.setFontSize(12);

            var logo = '<?php echo $logo_base64; ?>';

            var img = new Image();
            img.src = logo;
            img.onload = function () {
                var width = img.width;
                var height = img.height;
                var ratio = width / height;

                var desiredWidth = 40;
                var desiredHeight = desiredWidth / ratio;
                doc.addImage(logo, 'PNG', 20, 20, desiredWidth, desiredHeight);

                doc.text('Laporan Barang Masuk', 20, 60);

                let y = 70;
                barangMasukData.forEach(function (item) {
                    doc.text("Transaksi ID: " + item.id_transaksi, 20, y);
                    doc.text("Tanggal Masuk: " + item.tanggal, 20, y + 10);
                    doc.text("Kode Barang: " + item.kode_barang, 20, y + 20);
                    doc.text("Nama Barang: " + item.nama_barang, 20, y + 30);
                    doc.text("Kondisi: " + item.kondisi, 20, y + 40);
                    doc.text("Jumlah Masuk: " + item.jumlah, 20, y + 50);
                    doc.text("Satuan Barang: " + item.satuan, 20, y + 60);

                    y += 70;


                    if (y > 250) {
                        doc.addPage();
                        y = 20;
                    }
                });

                doc.save('laporan_barang_masuk.pdf');
            };
        });


        $('#exportCsv').click(function () {
            let csvContent = "ID Transaksi,Tanggal Masuk,Kode Barang,Nama Barang,Kondisi,Jumlah Masuk,Satuan Barang\n";
            barangMasukData.forEach(function (item) {
                csvContent += item.id_transaksi + "," + item.tanggal + "," + item.kode_barang + "," + item.nama_barang + "," + item.kondisi + "," + item.jumlah + "," + item.satuan + "\n";
            });

            let hiddenElement = document.createElement('a');
            hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csvContent);
            hiddenElement.target = '_blank';
            hiddenElement.download = 'laporan_barang_masuk.csv';
            hiddenElement.click();
        });

        $('#printReport').click(function () {
            window.print();
        });
    </script>

</body>

</html>