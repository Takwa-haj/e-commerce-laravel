<?php

namespace App\Helpers;

class CssBarcodeHelper
{
    /**
     * Generate CSS-based barcode that ALWAYS shows lines
     */
    public static function generateCssBarcode($number, $height = 60)
    {
        // Convert number to binary pattern for barcode lines
        $binaryPattern = self::numberToBinaryPattern($number);

        $html = '<div style="text-align: center; margin: 20px 0;">';
        $html .= '<div style="display: inline-block; padding: 15px; background: white; border: 1px solid #ddd; border-radius: 5px;">';

        // Barcode bars container
        $html .= '<div style="display: flex; align-items: flex-end; justify-content: center; height: ' . $height . 'px; margin-bottom: 10px;">';

        // Generate bars based on binary pattern
        $bars = str_split($binaryPattern);
        foreach ($bars as $bar) {
            $barHeight = $bar == '1' ? $height : ($height * 0.4); // Tall or short bar
            $barWidth = 2; // Width of each bar
            $margin = 1; // Space between bars

            $html .= '<div style="';
            $html .= 'width: ' . $barWidth . 'px;';
            $html .= 'height: ' . $barHeight . 'px;';
            $html .= 'background: #000;';
            $html .= 'margin: 0 ' . $margin . 'px;';
            $html .= 'display: inline-block;';
            $html .= '"></div>';
        }

        $html .= '</div>';

        // Start/Stop markers
        $html .= '<div style="display: flex; justify-content: center; margin-bottom: 5px;">';
        $html .= '<div style="width: 4px; height: ' . ($height + 10) . 'px; background: #000; margin: 0 10px;"></div>';
        $html .= '<div style="width: 4px; height: ' . ($height + 10) . 'px; background: #000; margin: 0 10px;"></div>';
        $html .= '</div>';

        // Number display
        $html .= '<div style="';
        $html .= 'font-family: \'Courier New\', monospace;';
        $html .= 'font-size: 16px;';
        $html .= 'letter-spacing: 8px;';
        $html .= 'font-weight: bold;';
        $html .= 'margin-top: 10px;';
        $html .= '">';
        $html .= str_pad($number, 8, '0', STR_PAD_LEFT);
        $html .= '</div>';

        $html .= '</div>';
        $html .= '<div style="font-size: 12px; color: #666; margin-top: 10px;">Code-barres de suivi</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Convert number to binary pattern for barcode visualization
     */
    private static function numberToBinaryPattern($number)
    {
        $str = (string)$number;
        $binary = '';

        for ($i = 0; $i < strlen($str); $i++) {
            $digit = $str[$i];
            // Create unique pattern for each digit
            $patterns = [
                '0' => '101000',
                '1' => '100010',
                '2' => '100100',
                '3' => '100110',
                '4' => '101010',
                '5' => '101100',
                '6' => '101110',
                '7' => '110010',
                '8' => '110100',
                '9' => '110110'
            ];
            $binary .= $patterns[$digit] ?? '101010';
        }

        // Add start/stop codes
        $binary = '1101' . $binary . '1101';

        return $binary;
    }

    /**
     * Simple vertical bars barcode (always works)
     */
    public static function generateSimpleBarcode($number, $height = 60)
    {
        $html = '<div style="text-align: center; margin: 20px 0;">';
        $html .= '<div style="display: inline-block; padding: 20px; background: white; border: 1px solid #ddd; border-radius: 5px;">';

        // Generate alternating bars
        $html .= '<div style="display: flex; align-items: flex-end; justify-content: center; height: ' . $height . 'px;">';

        $bars = 20; // Number of bars
        for ($i = 0; $i < $bars; $i++) {
            // Vary height based on position and number
            $digit = substr($number, $i % strlen($number), 1);
            $barHeight = $height * (0.3 + ($digit / 15));
            $barWidth = 3;
            $color = $i % 3 == 0 ? '#000' : '#333'; // Alternating darkness

            $html .= '<div style="';
            $html .= 'width: ' . $barWidth . 'px;';
            $html .= 'height: ' . $barHeight . 'px;';
            $html .= 'background: ' . $color . ';';
            $html .= 'margin: 0 1px;';
            $html .= 'display: inline-block;';
            $html .= '"></div>';
        }

        $html .= '</div>';

        // Number below
        $html .= '<div style="';
        $html .= 'font-family: \'Courier New\', monospace;';
        $html .= 'font-size: 18px;';
        $html .= 'letter-spacing: 6px;';
        $html .= 'font-weight: bold;';
        $html .= 'margin-top: 15px;';
        $html .= 'border-top: 2px solid #000;';
        $html .= 'padding-top: 10px;';
        $html .= '">';
        $html .= str_pad($number, 8, '0', STR_PAD_LEFT);
        $html .= '</div>';

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    /**
     * EAN-13 style barcode
     */
    public static function generateEanStyleBarcode($number, $height = 80)
    {
        $formattedNumber = str_pad($number, 8, '0', STR_PAD_LEFT);

        $html = '<div style="text-align: center; margin: 20px 0;">';
        $html .= '<div style="display: inline-block; padding: 20px; background: white; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">';

        // Top quiet zone
        $html .= '<div style="height: 10px; background: white;"></div>';

        // Start pattern
        $html .= '<div style="display: flex; height: ' . $height . 'px; margin-bottom: 5px;">';
        $html .= '<div style="width: 3px; height: 100%; background: #000;"></div>';
        $html .= '<div style="width: 2px; height: 100%; background: #fff;"></div>';
        $html .= '<div style="width: 3px; height: 100%; background: #000;"></div>';

        // Number bars
        $digits = str_split($formattedNumber);
        foreach ($digits as $digit) {
            // Left side pattern
            $patterns = [
                '0' => [1,1,1,0,0,1,0],
                '1' => [1,1,0,0,1,1,0],
                '2' => [1,1,0,1,1,0,0],
                '3' => [1,0,0,0,0,1,0],
                '4' => [1,0,1,1,1,0,0],
                '5' => [1,0,0,1,1,1,0],
                '6' => [1,0,1,0,0,0,0],
                '7' => [1,0,0,0,1,0,0],
                '8' => [1,0,0,1,0,0,0],
                '9' => [1,1,1,0,1,0,0]
            ];

            $pattern = $patterns[$digit] ?? $patterns['0'];
            foreach ($pattern as $bar) {
                $barHeight = $bar == 1 ? $height : ($height * 0.7);
                $html .= '<div style="width: 2px; height: ' . $barHeight . 'px; background: ' . ($bar == 1 ? '#000' : '#fff') . ';"></div>';
            }
            $html .= '<div style="width: 1px; height: 100%; background: #fff;"></div>'; // Space
        }

        // Stop pattern
        $html .= '<div style="width: 3px; height: 100%; background: #000;"></div>';
        $html .= '<div style="width: 2px; height: 100%; background: #fff;"></div>';
        $html .= '<div style="width: 3px; height: 100%; background: #000;"></div>';

        $html .= '</div>';

        // Number display
        $html .= '<div style="display: flex; justify-content: center; font-family: monospace; font-size: 16px; margin-top: 10px;">';
        $html .= '<div style="margin: 0 5px;">' . substr($formattedNumber, 0, 1) . '</div>';
        $html .= '<div style="margin: 0 15px;">' . substr($formattedNumber, 1, 6) . '</div>';
        $html .= '<div style="margin: 0 5px;">' . substr($formattedNumber, 7, 1) . '</div>';
        $html .= '</div>';

        $html .= '</div>';
        $html .= '<div style="font-size: 12px; color: #666; margin-top: 10px;">EAN-13 Style Barcode</div>';
        $html .= '</div>';

        return $html;
    }
}
