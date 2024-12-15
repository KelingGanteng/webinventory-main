<?php
require_once 'vendor/autoload.php';  // Pastikan untuk memuat DOMPDF

use Dompdf\Dompdf;
use Dompdf\Options;

// Ambil HTML yang dikirim dari frontend
$html = $_POST['html'];

// Setup DOMPDF options
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

// Inisialisasi DOMPDF
$dompdf = new Dompdf($options);

// Load HTML ke DOMPDF
$dompdf->loadHtml($html);

// (Opsional) Atur ukuran kertas dan orientasi
$dompdf->setPaper('A4', 'portrait');

// Render PDF dari HTML
$dompdf->render();

// Output PDF ke file
$pdfOutput = $dompdf->output();

// Simpan file PDF di server
$pdfFileName = 'Laporan_Stock_Gudang_' . time() . '.pdf';
file_put_contents($pdfFileName, $pdfOutput);

// Kirim URL file PDF untuk didownload
echo $pdfFileName;
?>