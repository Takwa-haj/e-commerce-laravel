<?php

namespace App\Helpers;

use Barryvdh\DomPDF\Facade\Pdf;

class PdfFontHelper
{
    /**
     * Register barcode fonts with DOMPDF
     */
    public static function registerBarcodeFonts()
    {
        $fontDir = storage_path('fonts/');

        // Ensure font directory exists
        if (!file_exists($fontDir)) {
            mkdir($fontDir, 0755, true);
        }

        // Copy fonts from resources if needed
        $fonts = [
            'LibreBarcode128-Regular.ttf',
            'LibreBarcode128Text-Regular.ttf',
            'LibreBarcode39-Regular.ttf',
            'LibreBarcode39Text-Regular.ttf',
        ];

        foreach ($fonts as $font) {
            $source = resource_path('fonts/' . $font);
            $destination = $fontDir . $font;

            if (file_exists($source) && !file_exists($destination)) {
                copy($source, $destination);
            }
        }

        return $fontDir;
    }

    /**
     * Get PDF with barcode fonts pre-loaded
     */
    public static function getPdfWithBarcodeFonts($view, $data = [])
    {
        self::registerBarcodeFonts();

        $pdf = Pdf::loadView($view, $data);

        // Set font directory
        $pdf->setOption('font_dir', storage_path('fonts/'));
        $pdf->setOption('font_cache', storage_path('fonts/'));
        $pdf->setOption('default_font', 'sans-serif');

        return $pdf;
    }
}
