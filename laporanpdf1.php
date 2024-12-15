<?php
include "vendor/autoload.php";
$mpdf = new \Mpdf\Mpdf()
    ?>

<!-- data -->

<?php
$html = ob_get_contents();
$mpdf->WritenHTML(utf8_decode(html));