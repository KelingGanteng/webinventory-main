<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }

        .header .logo {
            flex-shrink: 0;
            margin-right: 20px;
        }

        .header .logo img {
            max-width: 80px;
            /* Ukuran logo yang lebih kecil */
        }

        .header .company-details {
            text-align: center;
        }

        .header .company-details h2 {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            color: #dc3545;
            /* Merah */
        }

        .header .company-details p {
            font-size: 14px;
            margin: 5px 0;
            line-height: 1.5;
            /* Menyesuaikan jarak antar baris */
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            /* border: 1px solid #dee2e6; */
            /* border-radius: 8px; */
            box-shadow: none;
            /* Hilangkan shadow */
        }

        h1 {
            text-align: center;
            color: #000;
            font-size: 20px;
            margin-bottom: 30px;
        }

        .details-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .details-list li {
            display: flex;
            justify-content: flex-start;
            /* Posisi isi dari kiri */
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .details-list li:last-child {
            border-bottom: none;
        }

        .details-list .label {
            font-weight: bold;
            color: #000;
            width: 200px;
            /* Lebar label yang tetap */
        }

        .details-list .value {
            color: #393b3d;
        }

        .border {
            border-color: #000000;
            border-style: double;
            border-top-width: 3px;
            border-bottom-width: 1.5px;
            border-left-width: 0px;
            border-right-width: 0px;
            margin-top: 5px;
        }

        .border-dua {
            border-color: #000000;
            border-style: solid;
            margin-left: auto;
            margin-right: auto;

            border-width: 1px;
            margin-top: 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <table style="text-align: center">
        <tr>
            <th><img style="width:120px;" src="{{ public_path('images/Samco-Logo.png') }}"></th>
            <th style="font-family: arial; font-size: 25px; padding-left: 30px; color:"><span style="color: red">PT
                    SAMCO
                    FARMA</span><br>
                <small style="font-size: 14px; font-weight: normal; margin-bottom: 0;">
                    (PHARMACEUTICAL & CHEMICAL INDUSTRIES)
                </small><br>
                <small style="font-size: 12px; font-weight: normal; margin-bottom: 0;">
                    Jl. Jend Gatot Subroto Km. 1,2 No. 27 Cibodas – Tangerang, Banten 15138
                </small><br>
                <small style="font-size: 12px; font-weight: normal; margin-bottom: 0;">
                    Telp. : (021) 5525810 ext 270, Fax. : (021) 5537097
                </small><br>
                <small style="font-size: 12px; font-weight: normal; margin-top: 0;">
                    Website : <a href="www.samcofarma.co.id">www.samcofarma.co.id</a> E-mail : <a
                        href="mailto:cs@samcofarma.co.id">cs@samcofarma.co.id</a>
                </small>
            </th>
            <th><img style="width:100px;" src="{{ public_path('images/certificate.jpg') }}"></img></th>
        </tr>
    </table>
    <div class="border"></div>
    <div class="border-dua"></div>
    <div class="container">
        <h1>Detail Laporan Capa</h1>
        <ul class="details-list">
        </ul>
    </div>
</body>

</html>