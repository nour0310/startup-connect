<?php
require_once __DIR__ . '/../Model/startup.php';

// Update TCPDF path to use lib directory
$tcpdfPath = __DIR__ . '/../lib/tcpdf/tcpdf.php';
if (file_exists($tcpdfPath)) {
    require_once $tcpdfPath;
} else {
    die('TCPDF not found. Please make sure it is installed in: ' . $tcpdfPath);
}

// Define TCPDF configuration constants if not already defined
if (!defined('PDF_PAGE_ORIENTATION')) {
    define('PDF_PAGE_ORIENTATION', 'P');
}
if (!defined('PDF_UNIT')) {
    define('PDF_UNIT', 'mm');
}
if (!defined('PDF_PAGE_FORMAT')) {
    define('PDF_PAGE_FORMAT', 'A4');
}
if (!defined('PDF_MARGIN_LEFT')) {
    define('PDF_MARGIN_LEFT', 15);
}
if (!defined('PDF_MARGIN_TOP')) {
    define('PDF_MARGIN_TOP', 27);
}
if (!defined('PDF_MARGIN_RIGHT')) {
    define('PDF_MARGIN_RIGHT', 15);
}
if (!defined('PDF_MARGIN_HEADER')) {
    define('PDF_MARGIN_HEADER', 5);
}
if (!defined('PDF_MARGIN_FOOTER')) {
    define('PDF_MARGIN_FOOTER', 10);
}
if (!defined('PDF_MARGIN_BOTTOM')) {
    define('PDF_MARGIN_BOTTOM', 25);
}
if (!defined('PDF_FONT_NAME_MAIN')) {
    define('PDF_FONT_NAME_MAIN', 'helvetica');
}
if (!defined('PDF_FONT_SIZE_MAIN')) {
    define('PDF_FONT_SIZE_MAIN', 10);
}
if (!defined('PDF_FONT_NAME_DATA')) {
    define('PDF_FONT_NAME_DATA', 'helvetica');
}
if (!defined('PDF_FONT_SIZE_DATA')) {
    define('PDF_FONT_SIZE_DATA', 8);
}
if (!defined('PDF_FONT_MONOSPACED')) {
    define('PDF_FONT_MONOSPACED', 'courier');
}
if (!defined('PDF_HEADER_LOGO')) {
    define('PDF_HEADER_LOGO', '');
}
if (!defined('PDF_HEADER_LOGO_WIDTH')) {
    define('PDF_HEADER_LOGO_WIDTH', 0);
}

// Ensure the TCPDF class is loaded
if (!class_exists('TCPDF')) {
    die('TCPDF library is not loaded. Please check the path or installation.');
}

class PDFExportController {
    private $model;

    public function __construct() {
        $this->model = new StartupModel();
    }

    public function exportStartupsToPDF() {
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator('StartupConnect');
        $pdf->SetAuthor('StartupConnect Admin');
        $pdf->SetTitle('Liste des Startups');

        // Set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'StartupConnect', 'Liste des Startups - ' . date('d/m/Y'));

        // Set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Add a page
        $pdf->AddPage();

        // Get all startups
        $startups = $this->model->getAllStartups();

        // Create the HTML content
        $html = '<h1>Liste des Startups</h1>';
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr style="background-color: #f8f9fa;">
                    <th><b>Nom</b></th>
                    <th><b>Description</b></th>
                    <th><b>Catégorie</b></th>
                    <th><b>Note moyenne</b></th>
                  </tr>';

        foreach ($startups as $startup) {
            $rating = isset($startup['average_rating']) ? number_format($startup['average_rating'], 1) : 'N/A';
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($startup['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars(substr($startup['description'], 0, 100)) . '...</td>';
            $html .= '<td>' . htmlspecialchars($startup['category_name']) . '</td>';
            $html .= '<td>' . $rating . '/5</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('liste_startups.pdf', 'D');
    }
}

// Handle export request
if (isset($_GET['action']) && $_GET['action'] === 'export_pdf') {
    $controller = new PDFExportController();
    $controller->exportStartupsToPDF();
}
?>
