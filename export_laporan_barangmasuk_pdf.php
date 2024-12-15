<?php
// Mengimpor library Dompdf
require 'vendor/autoload.php';  // Pastikan path sesuai dengan struktur proyek Anda

use Dompdf\Dompdf;
use Dompdf\Options;

// Fungsi untuk menghasilkan PDF
function generatePDF($htmlContent)
{
    // Buat instance Dompdf
    $dompdf = new Dompdf();

    // Atur opsi jika diperlukan
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    $dompdf->setOptions($options);

    // Muat konten HTML
    $dompdf->loadHtml($htmlContent);

    // Mengatur ukuran halaman
    $dompdf->setPaper('A4', 'portrait'); // Anda bisa memilih 'landscape' jika ingin orientasi horizontal

    // Render PDF (tanpa output langsung)
    $dompdf->render();

    // Output file PDF ke browser
    $dompdf->stream('laporan_barang_masuk.pdf', array('Attachment' => 0));
}

// Cek apakah ID tersedia di URL
if (isset($_GET['id'])) {
    $id_transaksi = $_GET['id'];

    // Koneksi ke database
    include("koneksibarang.php");  // Pastikan file koneksi ada

    // Ambil data barang masuk berdasarkan ID transaksi
    $sql = $koneksi->query("SELECT * FROM barang_masuk WHERE id_transaksi = '$id_transaksi'");
    $dataBarangMasuk = $sql->fetch_assoc(); // Ambil satu data saja

    // Jika data tidak ditemukan
    if (!$dataBarangMasuk) {
        die("Data tidak ditemukan.");
    }

    // Mengonversi gambar menjadi base64
    $imagePath = 'Samco.png'; // Path gambar
    $imageData = base64_encode(file_get_contents($imagePath));

    // Membuat HTML untuk laporan dengan menggunakan heredoc
    $htmlContent = <<<HTML
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Laporan Barang Masuk</title>

        <!-- Menggunakan Google Fonts untuk font yang lebih menarik -->
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Open Sans', sans-serif;
                line-height: 1.6;
                margin: 0;
                padding: 0;
                background-color: #f9f9f9;
                color: #333;
            }
            .header {
                display: flex;
                flex-direction: column; /* Mengubah layout menjadi kolom untuk logo dan teks */
                align-items: center; /* Menyusun semua elemen di tengah */
                justify-content: center;
                padding: 30px;
                background-color: #fff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                margin-bottom: 20px;
                text-align: center; /* Membuat teks terpusat */
            }
            .header .logo img {
                max-width: 120px;
                display: block;
                margin-bottom: 10px; /* Memberikan jarak antara logo dan teks */
            }
            .header .company-details h2 {
                font-size: 28px;
                font-weight: 700;
                margin: 0;
                color: #dc3545;
            }
            .header .company-details p {
                font-size: 14px;
                margin: 5px 0;
                line-height: 1.5;
                color: #666;
            }
            .container {
                width: 90%;
                margin: 0 auto;
                padding: 30px;
                background-color: #fff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }
            h1 {
                text-align: center;
                font-size: 24px;
                font-weight: 600;
                color: #333;
                margin-bottom: 20px;
            }
            .details-list {
                list-style-type: none;
                padding: 0;
                margin: 0;
                font-family: 'Open Sans', sans-serif;
            }
            .details-list li {
                display: flex;
                justify-content: space-between;
                padding: 12px 0;
                border-bottom: 1px solid #eee;
                font-size: 14px;
            }
            .details-list li:last-child {
                border-bottom: none;
            }
            .details-list .label {
                font-weight: 600;
                color: #333;
            }
            .details-list .value {
                color: #555;
            }
            .section-title {
                font-size: 18px;
                font-weight: 600;
                margin-top: 30px;
                margin-bottom: 15px;
                color: #333;
            }
            .section-content {
                margin-left: 20px;
                font-size: 14px;
                color: #555;
            }
            .footer {
                margin-top: 30px;
                font-size: 12px;
                color: #888;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <div class="logo">
                <img src="data:image/png;base64,{$imageData}" alt="Logo">
            </div>
            <div class="company-details">
                <h2>PT SAMCO FARMA</h2>
                <p>(PHARMACEUTICAL & CHEMICAL INDUSTRIES)</p>
                <p>Jl. Jend Gatot Subroto Km. 1,2 No. 27 Cibodas – Tangerang, Banten 15138</p>
                <p>Telp. : (021) 5525810 ext 270, Fax. : (021) 5537097</p>
                <p>Website : <a href="https://www.samcofarma.co.id">www.samcofarma.co.id</a></p>
                <p>E-mail : <a href="mailto:cs@samcofarma.co.id">cs@samcofarma.co.id</a></p>
            </div>
        </div>

        <div class="container">
            <h1>Laporan Barang Masuk</h1>

            <!-- Detail Laporan -->
            <div class="details-list">
                <li><span class="label">Periode:</span> <span class="value">January 2024</span></li>
                <li><span class="label">Jumlah Barang:</span> <span class="value">1</span></li> <!-- Ganti dengan data dinamis -->
            </div>

            <!-- Detail Transaksi -->
            <div class="section-title">Detail Transaksi</div>
            <div class="section-content">
                <div><strong>No:</strong> 1</div>
                <div><strong>ID Transaksi:</strong> {$dataBarangMasuk['id_transaksi']}</div>
                <div><strong>Tanggal Masuk:</strong> {$dataBarangMasuk['tanggal']}</div>
                <div><strong>Kode Barang:</strong> {$dataBarangMasuk['kode_barang']}</div>
                <div><strong>Nama Barang:</strong> {$dataBarangMasuk['nama_barang']}</div>
                <div><strong>Kondisi:</strong> {$dataBarangMasuk['kondisi']}</div>
                <div><strong>Jumlah Masuk:</strong> {$dataBarangMasuk['jumlah']}</div>
                <div><strong>Satuan:</strong> {$dataBarangMasuk['satuan']}</div>
            </div>

            <div class="footer">
                <p>&copy; 2024 PT SAMCO FARMA. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
HTML;

    // Panggil fungsi untuk menghasilkan PDF
    generatePDF($htmlContent);

} else {
    echo "ID transaksi tidak ditemukan.";
}
