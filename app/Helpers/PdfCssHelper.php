<?php

namespace App\Helpers;

class PdfCssHelper
{
    /**
     * Get DOMPDF-compatible CSS
     */
    public static function getCompatibleCss()
    {
        return '
            /* DOMPDF compatible CSS - avoid modern properties */

            /* Use floats instead of flexbox */
            .clearfix::after {
                content: "";
                clear: both;
                display: table;
            }

            /* Use tables for layout instead of grid */
            .table-layout {
                width: 100%;
                border-collapse: collapse;
            }

            /* Use inline-block instead of flex */
            .inline-block {
                display: inline-block;
                vertical-align: top;
            }

            /* Simple positioning */
            .text-left { text-align: left; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }

            /* Margins and padding */
            .m-0 { margin: 0; }
            .p-0 { padding: 0; }
            .mb-1 { margin-bottom: 5px; }
            .mb-2 { margin-bottom: 10px; }
            .mb-3 { margin-bottom: 15px; }

            /* Colors */
            .text-primary { color: #d4af37; }
            .text-success { color: #28a745; }
            .text-danger { color: #dc3545; }
            .text-muted { color: #6c757d; }

            /* Borders */
            .border { border: 1px solid #dee2e6; }
            .border-top { border-top: 1px solid #dee2e6; }
            .border-bottom { border-bottom: 1px solid #dee2e6; }

            /* Backgrounds */
            .bg-light { background-color: #f8f9fa; }
            .bg-white { background-color: #ffffff; }
        ';
    }

    /**
     * Convert modern CSS to DOMPDF-compatible CSS
     */
    public static function convertCss($css)
    {
        // Remove unsupported properties
        $unsupported = [
            '/display:\s*flex;/' => 'display: block;',
            '/display:\s*grid;/' => 'display: block;',
            '/flex-direction:\s*\w+;/' => '',
            '/flex-wrap:\s*\w+;/' => '',
            '/justify-content:\s*\w+;/' => 'text-align: center;',
            '/align-items:\s*\w+;/' => '',
            '/flex-grow:\s*\d+;/' => '',
            '/flex-shrink:\s*\d+;/' => '',
            '/flex-basis:\s*\w+;/' => '',
            '/grid-template-columns:\s*[^;]+;/' => '',
            '/grid-template-rows:\s*[^;]+;/' => '',
            '/grid-gap:\s*[^;]+;/' => '',
            '/gap:\s*[^;]+;/' => '',
            '/box-sizing:\s*\w+;/' => '',
            '/transform:\s*[^;]+;/' => '',
            '/transition:\s*[^;]+;/' => '',
            '/animation:\s*[^;]+;/' => '',
        ];

        foreach ($unsupported as $pattern => $replacement) {
            $css = preg_replace($pattern, $replacement, $css);
        }

        return $css;
    }
}
