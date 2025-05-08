<?php
require '../../lib/dompdf/autoload.inc.php'; // ou le chemin vers dompdf/autoload.inc.php si tu n’utilises pas Composer
use Dompdf\Dompdf;

ob_start();
include 'templateContrat.php'; // c'est ton modèle HTML avec données
$html = ob_get_clean();

$dompdf = new Dompdf();
$dompdf->loadHtml($html);

// Format A4, portrait
$dompdf->setPaper('A4', 'portrait');

// Génère le PDF
$dompdf->render();

// Affiche dans le navigateur (sans téléchargement automatique)
$dompdf->stream("contrat.pdf", ["Attachment" => false]);
exit;
