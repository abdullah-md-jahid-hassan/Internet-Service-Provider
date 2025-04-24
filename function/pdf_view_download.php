<?php
require_once 'vendor/autoload.php';

/**
 * Function definePDF()
 * Initialize the PDF format and return mPDF object
 * @param string $html HTML content to render
 * @return \Mpdf\Mpdf mPDF instance
 */
function definePDF($html) {
    $mpdf = new \Mpdf\Mpdf([
        'mode' => 'utf-8',
        'format' => 'A4',
        'margin_header' => 10,
        'margin_footer' => 10,
        'orientation' => 'P',
        'tempDir' => '../files/temp' 
    ]);

    $mpdf->WriteHTML($html);
    return $mpdf; // Return the mPDF object (not WriteHTML result)
}










/**
 * Function downloadPDF()
 * Generate and force download of a PDF document
 * @param string $html HTML content (supports Bootstrap 5.3/Font Awesome)
 * @param string $filename Name for the downloaded file (without .pdf)
 */
function downloadPDF($html, $filename = 'document') {
    $mpdf = definePDF($html);
    $mpdf->Output($filename . '.pdf', 'D');
}








/**
 * Function viewPDF()
 * Generate and display a PDF in the browser
 * @param string $html HTML content (supports Bootstrap 5.3/Font Awesome)
 * @param string $filename Name for the displayed file (without .pdf)
 */
function viewPDF($html, $filename = 'document') {
    $mpdf = definePDF($html);
    $mpdf->Output($filename . '.pdf', 'I');
}
?>